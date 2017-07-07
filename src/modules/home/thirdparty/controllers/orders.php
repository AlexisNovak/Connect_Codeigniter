<?php

class Orders extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->language('english');
		$this->load->model(array(
			'third_party_model',
			'orders/orders_model',
			'couriers/couriers_model',
			'orders/consignment_messages_model',
			'orders/consignment_activity_log_model',
			'orders/consignment_pod_model',
			'couriers/jobstates_model'
		));
	}

	public function view($id = 0, $slug = NULL)
	{
		$data = array();
		$order = $this->third_party_model->get_order($id, $slug);
		if ($order) {
			$order->collection_address = implode(' ', json_decode($order->collection_address));
			$order->delivery_address = implode(' ', json_decode($order->delivery_address));
		}
		$data['order'] = $order;
		if ($pods = $this->consignment_pod_model->get_pods($id)) {
			$data['pods'] = array();
			foreach ($pods as $pod) {
				if ($pod->is_signature) {
					$data['signature'] = $pod;
				} else {
					$data['pods'][] = $pod;
				}
			}
		}
		$data['messages'] = $this->third_party_model->listjobmessages($id);
		
		$data['id'] = isset($order->consignment_id) ? $order->consignment_id : 0;
		$this->load->view('view', $data);
	}

	public function get_pods()
	{
		$result = array();
		$podData = json_decode(file_get_contents('php://input'));
		if (isset($podData->order_id) && !empty($podData->order_id)) {
			$order_id = $podData->order_id;
			if ($pods = $this->consignment_pod_model->get_pods($order_id)) {
				$result['pods'] = array();
				foreach ($pods as $pod) {
					if ($pod->is_signature) {
						$result['signature'] = $pod;
					} else {
						$result['pods'][] = $pod;
					}
				}
			}
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function loglist_json()
	{
		$perpage = '';
		$search = '';
		$loglistData = json_decode(file_get_contents('php://input'));
		if (isset($loglistData->perpage_value)) {
			
			$perpage = $loglistData->perpage_value;
		} else {
			$perpage = 5;
		}
		if (isset($loglistData->order_id)) {
			
			$order_id = $loglistData->order_id;
		} else {
			$order_id = 0;
		}
		if (isset($loglistData->currentPage)) {
			
			$page = $loglistData->currentPage;
		} else {
			$page = 1;
		}
		if (isset($loglistData->filter)) {
			if ($loglistData->filter != NULL) {
				$search = $loglistData->filter;
			} else {
				$search = NULL;
			}
		}
		$total_result = $this->consignment_activity_log_model->getloglist_count_for_courier($order_id, $search);
		$lastpage = ceil($total_result / $perpage);
		if ($page > $lastpage) {
			$page = 1;
		}
		$start = ($page - 1) * $perpage;
		$result['total'] = $total_result;
		$result['start'] = $start + 1;
		$result['page'] = $page;
		$loglist = array();
		$loglist = $this->consignment_activity_log_model->getloglist_by_orderid_for_courier($order_id, $perpage, $search, $start);
		
		$result['loglist'] = $loglist;
		$result['end'] = (int) ($start + count($result['loglist']));
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function add_comment()
	{
		$result = array();
		$biddersData = json_decode(file_get_contents('php://input'));
		if (isset($biddersData->order_id)) {
			
			$order_id = $biddersData->order_id;
		} else {
			exit();
		}
		if (isset($biddersData->comment)) {
			
			$comment = $biddersData->comment;
		} else {
			$comment = "";
		}
		$data = array(
			'courier_id' => 0,
			'job_id' => $order_id,
			'question' => $comment,
			'reply' => NULL
		);
		$send_mail = FALSE;
		if ($this->consignment_messages_model->addmessage($data)) {
			$send_mail = TRUE;
			$result['status'] = 1;
			$result['last'] = '<div class="question">' . '<div class="q_head">' . '<div class="q_title">Ask by you</div>' . '<div class="q_time">' . date('Y-m-d h:i A', now()) . '</div>' . '</div>' . '<div class="q_text">' . '<p>' . $comment . '</p>' . '<div class="q_response">' . '<div class="q_text">' . '<p><strong>Customer not yet responded to your question.</strong></p>' . '</div>' . '</div>' . '</div>' . '</div>';
		}
		
		echo json_encode($result);
		if ($send_mail) {
			$this->send_mail_for_member($order_id, N_COMMENT_FROM_COURIER);
		}
		exit();
	}

	public function statusList()
	{
		$result = array();
		$result['status'] = $this->couriers_model->get_statuslist();
		echo json_encode($result);
		exit();
	}

	public function accept()
	{
		$result = array();
		$error = false;
		$errors = array();
		
		$post_data = json_decode(file_get_contents("php://input"));
		if (isset($post_data->order_id) && !empty($post_data->order_id)) {
			$job_id = $post_data->order_id;
		} else {
			$error = true;
			$errors['order_id'] = "Order id missing";
		}
		if (isset($post_data->private_id) && !empty($post_data->private_id)) {
			$consignment_id = $post_data->private_id;
		} else {
			$error = true;
			$errors['private_id'] = "Assigned id missing";
		}
		$confirm = 1;
		$status = C_PENDING;
		if (isset($post_data->price) && !empty($post_data->price)) {
			$price = $post_data->price;
			$this->orders_model->updateOrder(array(
				'price' => $price
			), $job_id);
		} else {
			$error = true;
			$errors['price'] = "Price missing";
		}
		
		if (!$error) {
			$data = array(
				'private_id' => $consignment_id,
				'consignment_status_id' => $status,
				'is_confirmed' => $confirm
			);
			
			if ($this->orders_model->updateOrder($data, $job_id)) {
				if ($status == C_PENDING) {
					$message = "Order status updated to - " . C_PENDING_NAME;
				} else {
					$message = "Order status updated to - " . C_PRICE_APPROVAL_NAME;
				}
				$this->consignment_activity_log_model->add_activity(array(
					"order_id" => $job_id,
					"activity" => $message
				));
				header('Content-Type: image/jpg');
				$this->load->library("barcode39", TRUE);
				$bc = new Barcode39($consignment_id);
				$bc->draw("./filebox/barcode/consignment_document_" . $consignment_id . ".png");
				$result['status'] = 1;
				$result['class'] = 'alert-success';
				$result['msg'] = "Order accepted";
				if (isset($threshold_break)) {
					$result['class'] = 'alert-warning';
					$result['message'] = "delivery will be pending customer\'s approval on the price to proceed";
				}
			}
		} else {
			$result['status'] = 0;
			$result['class'] = 'alert-danger';
			$result['msg'] = lang('clear_error');
			$result['errors'] = $errors;
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function update_status()
	{
		$result = array();
		$error = false;
		$errors = array();
		
		$post_data = json_decode(file_get_contents("php://input"));
		if (isset($post_data->order_id) && !empty($post_data->order_id)) {
			$job_id = $post_data->order_id;
		} else {
			$error = true;
			$errors['order_id'] = "Order id missing";
		}
		if (isset($post_data->status) && !empty($post_data->status)) {
			$status = json_decode($post_data->status);
			$status_code = $status->id;
			$status_name = $status->display_name;
			if (!in_array($status_code, array(
				'101',
				'102',
				'301',
				'401',
				'501',
				'601'
			))) {
				$error = true;
				$errors['status'] = "Invalid status";
			}
		} else {
			$error = true;
			$errors['status'] = "Status missing";
		}
		if (isset($post_data->remarks) && !empty($post_data->remarks)) {
			$description = $post_data->remarks;
		} else {
			if (isset($status_code) && $status_code == C_OTHERS) {
				$error = true;
				$errors['remarks'] = "Give details if status is 'Others'";
			} else {
				$description = "";
			}
		}
		if (!$error) {
			$data = array(
				'job_id' => $job_id,
				'status_code' => $status_code,
				'status_name' => $status_name,
				'status_description' => $description,
				'user_type' => 2
			);
			if ($this->jobstates_model->addjobtrack($data)) {
				$this->orders_model->updateOrder(array(
					'consignment_status_id' => $status_code
				), $job_id);
				$this->consignment_activity_log_model->add_activity(array(
					"order_id" => $job_id,
					"activity" => "Order status updated to - " . $status_name
				));
				$this->send_mail_for_member($job_id, N_ORDER_STATUS_UPDATE);
				$result['status'] = 1;
				$result['class'] = 'alert-success';
				$result['msg'] = "Status updated";
			} else {
				$result['status'] = 0;
				$result['class'] = 'alert-warning';
				$result['msg'] = lang('try_again');
			}
		} else {
			$result['status'] = 0;
			$result['class'] = 'alert-danger';
			$result['msg'] = lang('clear_error');
			if (isset($message)) {
				$result['msg'] = $message;
			}
			$result['errors'] = $errors;
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function change_price()
	{
		$result = array();
		$error = false;
		$errors = array();
		$post_data = json_decode(file_get_contents("php://input"));
		if (isset($post_data->order_id) && !empty($post_data->order_id)) {
			$job_id = $post_data->order_id;
		} else {
			$error = true;
			$errors['order_id'] = "Order id missing";
		}
		if (isset($post_data->price) && !empty($post_data->price)) {
			if (is_numeric($post_data->price)) {
				$price = $post_data->price;
			} else {
				$error = true;
				$errors['price'] = "Price must be numeric";
			}
		} else {
			$error = true;
			$errors['price'] = "Price missing";
		}
		if (isset($post_data->remarks) && !empty($post_data->remarks)) {
			$description = $post_data->remarks;
		} else {
			
			$description = "";
		}
		
		if (!$error) {
			$threshold = $this->orders_model->get_threshold($job_id);
			$cprice = $this->orders_model->get_price($job_id);
			if ($price > $threshold && $price > $cprice) {
				$data = array(
					'change_price' => $price
				);
				if ($this->orders_model->updateOrder($data, $job_id)) {
					$this->consignment_activity_log_model->add_activity(array(
						"order_id" => $job_id,
						"activity" => "Price change requested by the courier. New price $" . number_format($price, 2)
					));
					$result['status'] = 1;
					$result['class'] = 'alert-success';
					$result['msg'] = "Price change requested successfully";
				} else {
					$result['status'] = 0;
					$result['class'] = 'alert-warning';
					$result['msg'] = lang('try_again');
				}
			} else {
				$data = array(
					'price' => $price,
					'change_price' => NULL
				);
				if ($this->orders_model->updateOrder($data, $job_id)) {
					$this->consignment_activity_log_model->add_activity(array(
						"order_id" => $job_id,
						"activity" => "Price changed by the courier. New price $" . number_format($price, 2)
					));
					$result['status'] = 1;
					$result['class'] = 'alert-success';
					$result['msg'] = "Price updated successfully";
				} else {
					$result['status'] = 0;
					$result['class'] = 'alert-warning';
					$result['msg'] = lang('try_again');
				}
			}
		} else {
			$result['status'] = 0;
			$result['class'] = 'alert-danger';
			$result['msg'] = lang('clear_error');
			if (isset($message)) {
				$result['msg'] = $message;
			}
			$result['errors'] = $errors;
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function upload()
	{
		$data = array();
		$order = time();
		if (isset($_FILES['file'])) {
			$error = false;
			$files = "";
			$uploaddir = "filebox/pod/";
			$uploadPath = "filebox/pod";
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath, 0777, TRUE);
			}
			$ext = end(explode(".", basename($_FILES['file']['name'])));
			$config['image_library'] = 'gd2';
			$config['source_image'] = $_FILES['file']['tmp_name'];
			$config['new_image'] = $uploaddir . $order . "." . $ext;
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = "";
			$config['maintain_ratio'] = TRUE;
			$config['width'] = 200;
			$config['height'] = 200;
			
			$this->load->library('image_lib', $config);
			
			if ($this->image_lib->resize()) {
				$files = $order . "." . $ext;
			} else {
				$errors = $this->image_lib->display_errors();
				$error = true;
			}
			$data = ($error) ? array(
				'error' => $errors
			) : array(
				'files' => base_url($uploaddir . $files)
			);
		} else {
			$data = array(
				'error' => 'File not uploaded'
			);
		}
		
		echo json_encode($data);
		exit();
	}

	public function remove_photo()
	{
		if ($this->input->post('image')) {
			$image_path = $this->input->post('image', TRUE);
			$image = end(explode('/', $image_path));
			if (file_exists("filebox/pod/$image")) {
				unlink("filebox/pod/$image");
			}
		}
		return;
	}

	public function add_new_pod()
	{
		$result = array();
		$error = false;
		$errors = array();
		$podData = json_decode(file_get_contents('php://input'));
		if (isset($podData->order_id) && !empty($podData->order_id)) {
			$consignment_id = $podData->order_id;
		} else {
			$error = TRUE;
			$errors['order_id'] = "invalid";
		}
		
		if (isset($podData->pod_image) && !empty($podData->pod_image)) {
			$pod_image = $podData->pod_image;
		} else {
			$error = TRUE;
			$errors['pod_image'] = "Invalid input - No image";
		}
		if (isset($podData->signature) && !empty($podData->signature)) {
			$is_signature = 1;
		} else {
			$is_signature = 0;
		}
		if (isset($podData->remarks) && !empty($podData->remarks)) {
			$remarks = $podData->remarks;
		} else {
			$remarks = NULL;
		}
		if (!$error) {
			$data = array();
			$data['pod_image_url'] = $pod_image;
			$data['is_signature'] = $is_signature;
			if ($is_signature) {
				$this->consignment_pod_model->add_signature($consignment_id, $data);
			} else {
				if ($this->consignment_pod_model->get_count_by_consignment_id($consignment_id) < 3) {
					$this->consignment_pod_model->add_pod($consignment_id, $data);
				} else {
					$message = "No more images can add";
				}
			}
			$result['status'] = 1;
			$result['class'] = 'alert-success';
			$result['msg'] = "POD updated";
			if (isset($message)) {
				$result['class'] = 'alert-warning';
				$result['msg'] = $message;
			}
		} else {
			$result['status'] = 0;
			$result['class'] = 'alert-danger';
			$result['msg'] = lang('clear_error');
			if (isset($message)) {
				$result['msg'] = $message;
			}
			$result['errors'] = $errors;
		}
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}

	public function send_mail_for_member($order_id = 0, $type = 0, $settings = 0)
	{
		$this->load->model('account/notification_model');
		if ($type == N_NEW_SERVICE_BID) {
			$members = $this->service_requests_model->get_owners($order_id);
		} else {
			$members = $this->orders_model->get_owner($order_id);
		}
		$order = $this->orders_model->getjobdetail($order_id);
		foreach ($members as $member) {
			$where = array(
				'account_id' => $member->user_id,
				'type' => 0,
				'notification_id' => $type
			);
			if ($this->notification_model->get_notification($where) || $settings) {
				
				switch ($type) {
					case N_COMMENT_FROM_COURIER:
						$title = lang('courier_comment_email_title');
						$content = lang('courier_comment_email_content');
						$link_title = lang('courier_comment_email_link_title');
						$link = "";
						break;
					case N_ORDER_STATUS_UPDATE:
						$title = lang('status_update_email_title');
						$content = sprintf(lang('status_update_email_content'), $order->consignment_status);
						$link_title = lang('status_update_email_link_title');
						$link = "";
						break;
					case N_NEW_BID_RECEIVED:
						$title = lang('new_bid_email_title');
						$content = lang('new_bid_email_content');
						$link_title = lang('new_bid_email_link_title');
						$link = "";
						break;
					case N_NEW_SERVICE_BID:
						$title = lang('service_bid_email_title');
						$content = lang('service_bid_email_content');
						$link_title = lang('service_bid_email_link_title');
						$link = "";
						break;
					case N_THRESHOLD:
						$title = lang('threshold_email_title');
						$content = lang('threshold_email_content');
						$link_title = lang('threshold_email_link_title');
						$link = "";
						break;
					default:
						$title = '';
						$content = '';
						$link_title = '';
						$link = '';
				}
				$to = $member->email;
				$to_name = $member->name;
				$subject = '6connect email notification';
				$message = array(
					'title' => $title,
					'name' => $to_name,
					'content' => array(
						$title,
						$content
					),
					'link_title' => $link_title,
					'link' => $link
				);
				save_mail($to, $to_name, $subject, $message,1);
			}
			return;
		}
		
		/*
		 * end email send
		 */
		return;
	}
}
  