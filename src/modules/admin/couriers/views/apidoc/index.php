<!DOCTYPE html>
<html>
     <head>
          <meta charset="utf-8">
          <title>6Connect - Courier API Documentation</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta name="description" content="">
          <meta name="author" content="">
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo base_url(); ?>resource/images/favicons.png" />

          <!-- Le styles -->
          <link href="<?php echo base_url('REST'); ?>/css/bootstrap.css" rel="stylesheet">
          <link href="<?php echo base_url('REST'); ?>/css/bootstrap-responsive.css" rel="stylesheet">
          <style>
               .desp_title{text-transform: capitalize;}
          </style>
          <script type="text/javascript" src="<?php echo base_url('REST'); ?>/js/jquery-1.9.1.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.2.1/mustache.js"></script>

          <script type="text/javascript">
<?php
  $url = "http://" . $_SERVER['HTTP_HOST'] . str_replace("rest/", "", str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME'])) . "index.php/api/";
  $new_url = str_replace("REST/", '', $url);
?>
               var baseUrl = '<?php echo $new_url; ?>';
               var apis = {
                    "login": [
                         'courier/login',
                         '{"email":"", "password":""}',
                         'Login API is used for login courier to 6Connect system. This API requires two parameters and on success it will return the access key.' +
                                 '<h5>Parameter</h5>' +
                                 "<strong>email</strong> : Courier's registered email with 6Connect(mandatory)<br>" +
                                 '<strong>password</strong> : 6Connect password (mandatory)<br>',
                         {"code": 0, "message": "Login success", "id": "1", "AuthCode": "MdIhDNYmtZbOT"}
                    ],
                    "register courier": [
                         'courier/register',
                         '{"email":"","name":"","password":"","url":"","description":""}',
                         'Register courier API is used for register new courier to 6Connect system. ' +
                                 'This API requires 4 mandatory parameters - email, name, password, and description.<h5>Parameters</h5>' +
                                 '<strong>email</strong> : Email address of courier (mandatory)<br>' +
                                 '<strong>name</strong> : Courier display name (mandatory)<br>' +
                                 '<strong>password</strong> : 6Connect password (mandatory)<br>' +
                                 '<strong>url</strong> : The 6parcels URL of courier<br>' +
                                 '<strong>description</strong> : Description about the courier (mandatory)<br>',
                         {"code": 0, "access_key": "zhxsKkwHTB1Yg"}
                    ],
                    "update courier": [
                         'courier/update',
                         '{"company_name":"","url":"","description":"","reg_address":"","billing_address":"","fullname":"","reg_no":"","phone":"","fax":"","support_email":""}',
                         'Update courier API is used for update existing courier data in 6Connect system. ' +
                                 'This API consists of 10 parameters - company_name, url, description, reg_address, billing_address, fullname, reg_no, phone, fax, and support_email.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>company_name</strong> : Company name of the courier (mandatory)<br>' +
                                 '<strong>url</strong> : The 6parcels URL of the courier (mandatory)<br>' +
                                 '<strong>description</strong> : Description about the courier (mandatory)<br>' +
                                 '<strong>reg_address</strong> : Register address of the courier (mandatory)<br>' +
                                 '<strong>billing_address</strong> : Billing address of the courier (mandatory)<br>' +
                                 '<strong>fullname</strong> : The fullname of courier (mandatory)<br>' +
                                 '<strong>reg_no</strong> : Register number of the courier<br>' +
                                 '<strong>phone</strong> : Phone number of the courier<br>' +
                                 '<strong>fax</strong> : Fax number of the courier<br>' +
                                 '<strong>support_email</strong> : Help line email address of the courier<br>',
                         {"code": 0, "access_key": "zhxsKkwHTB1Yg"}
                    ],
                    "get profile": [
                         'courier/getprofile',
                         '{}',
                         'Get profile API is used for get profile info of courier from 6Connect system. It only needs the Access key.',
                         {"code": 0, "courier": {"courier_id": "24", "email": "test1234@test.com", "company_name": "testcompany", "url": "http://www.6parcels.com", "description": "test", "reg_address": "test", "billing_address": "test", "fullname": "test", "reg_no": "123456", "phone": "122324", "fax": "2424", "support_email": "", "is_approved": "0"}}
                    ],
                    "reset access key": [
                         'courier/resetaccesskey',
                         '{}',
                         'Reset access key API is used for reset the current access key. It only needs the access key and return new access key generated from the 6Connect system.',
                         {"code": 0, "access_key": "zhxsKkwHTB1Yg"}
                    ],
                    "push services": [
                         'courier/pushservices',
                         '{"services":[{"service_id":"","company":"","display_name":"","price":"","start_time":"","end_time":"","week":"","description":""}]}',
                         'Push services API is used for add one or multiple services to 6Connect. Each service must be in a JSON array format<br>- If a service has never been used before, it will be replaced permanently.<br>- If a service has been used before, it will suspend the previous old one and create a new record. <br>(Whether a service has been used before is based on the submitted service id by the courier)<br>' +
                                 'Each service must have 9 parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>service_id</strong> : Service ID in 6Parcels (mandatory)<br>' +
                                 '<strong>company</strong> : 6Connect organisation ID, set this parameter if this service need to be assigned to specific organisation. If this is not provided, it means the service is meant for bidding purposes. (mandatory)<br>' +
                                 '<strong>display_name</strong> : Service name (mandatory)<br>' +
                                  '<strong>price</strong> : Default price (mandatory)<br>' +
                                 '<strong>start_time</strong> : Service start time, ex: 08:00 (mandatory)<br>' +
                                 '<strong>end_time</strong> : Service end time, ex: 17:00 (mandatory)<br>' +
                                 '<strong>week</strong> : available days on week, ex: "1,2,3,4,5" ,0 Sunday, 1 Monday, 2 Tuesday, 3 Wednesday, 4 Thursday, 5 Friday, 6 Saturday (mandatory)<br>' +
                                 '<strong>description</strong> : Service description<br>',
                         {"code": 0, "access_key": "PlFp3L2enCJmB"}

                    ],
                    "list courier services": [
                         'courier/listservices',
                         '{}',
                         'List services API is used for list currently added services to 6Connect system. It only needs the access key.',
                         {"code": 0, "services": [{"id": "68", "service_id": "9898", "org_id": "0", "display_name": "service name", "price": "0", "start_time": "10:00:00", "end_time": "22:00:00", "week_0": "0", "week_1": "1", "week_2": "1", "week_3": "0", "week_4": "0", "week_5": "0", "week_6": "0", "origin": "", "destination": "", "description": "", "threshold_price": "0", "is_for_bidding": "1", "status": "1", "org_status": "1", "courier_id": "24", "auto_approve": "0", "is_public": "0"}]}
                    ],
                    "remove service": [
                         'courier/removeservice',
                         '{"service_id":"","company":""}',
                         'Remove service API is used for remove assigned services from a specific organisation. This API requires two parameters.' +
                                 '<h5>Paraeters</h5>' +
                                 '<strong>service_id</strong> : Service id that already added in system (mandatory)<br>' +
                                 '<strong>company </strong> : ID of the organisation to which currently this service is assigned (mandatory)',
                         {"code": 0, "access_key": "PlFp3L2enCJmB"}
                    ],
                    "assign service": [
                         'courier/assignservice',
                         '{"service_id":"","company":""}',
                         'Assign service API is used for assign a service to specific organisation. This API requires two parameters.' +
                                 '<h5>Paraeters</h5>' +
                                 '<strong>service_id</strong> : Service id that already added in system (mandatory)<br>' +
                                 '<strong>company </strong> : ID of the organisation to which you have to assign this service (mandatory)',
                         {"code": 0, "access_key": "PlFp3L2enCJmB"}
                    ],
                    "list statuses": [
                         'courier/liststatuses',
                         '{}',
                         'List statuses API is used for list all statuses used in 6Connect system. This API requires no parameters.',
                         {"code": 0, "statuses": [{"status_id": "1", "display_name": "Accepted"}, {"status_id": "7", "display_name": "Draft"}, {"status_id": "8", "display_name": "Courier Informed"}, {"status_id": "9", "display_name": "Getting Quotes"}]}
                    ],
                    "list jobs": [
                         'courier/listjobs',
                         '{"company":"","username":"","search":"","collection_address":"","delivery_address" :"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to" :"","delivery_date_from":"","delivery_date_to":"","delivery_time_from":"","delivery_time_to":""}',
                         'List jobs API is used for list jobs that are available for bidding. This list includes jobs placed for open bidding and jobs from organisations in which you are a pre-approved courier. This API consists of many parameters to filter the list, but no one is mandatory.<br>' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>company</strong> : Filter results to jobs from a specific company by company name/ oraganisation name<br>' +
                                 '<strong>username</strong> : Filter results to jobs from a specific user by username<br>' +
                                 '<strong>search</strong> : Search string to search in public order ID, username, company name, or order details<br>' +
                                 '<strong>collection_address</strong> : String to search in collection address<br>' +
                                 '<strong>delivery_address</strong> : String to search in delivery address<br>' +
                                 '<strong>collection_date_from</strong> : Date to filter jobs having collection date starts later than provided<br>' +
                                 '<strong>collection_date_to</strong> :  Date to filter jobs having collection date within provided<br>' +
                                 '<strong>collection_time_from</strong> : Time to filter jobs having collection time starts later than provided<br>' +
                                 '<strong>collection_time_to</strong> : Time to filter jobs having collection time within provided<br>' +
                                 '<strong>delivery_date_from</strong> : Date to filter jobs having delivery date starts later than provided<br>' +
                                 '<strong>delivery_date_to</strong> : Date to filter jobs having delivery date within provided<br>' +
                                 '<strong>delivery_time_from</strong> : Time to filter jobs having delivery time starts later than provided<br>' +
                                 '<strong>delivery_time_to</strong> : Time to filter jobs having delivery time within provided<br>'
                                 , {"code": 0, "jobs": [{"id": "198", "public_id": "99313632087843", "display_name": "Extra Small", "username": "Livin", "company_name": "Organisation A", "height": "0", "breadth": "0", "length": "0", "volume": "0", "weight": "0", "collection_address": "335 Woodlands St 32", "crestrict": "0", "collection_date_from": "14-01-2016", "collection_time_from": "01:00 PM", "collection_date_to": "14-01-2016", "collection_time_to": "06:00 PM", "delivery_date_from": "16-01-2016", "delivery_time_from": "10:00 AM", "delivery_date_to": "16-01-2016", "delivery_time_to": "05:00 PM", "collection_country": "Singapore", "delivery_address": "33 Ubi Ave 3", "delivery_post_code": "639339", "delivery_country": "Singapore", "drestrict": "0", "collection_post_code": "730335", "created_date": "14 Jan 2016 03:58 PM"}]}
                    ],
                    "get job detail": [
                         'courier/getjobdetail',
                         '{"job_id":""}',
                         'Get job detail API is used for get all details of a job. This API requires only one parameter ' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)',
                         {"code": 0, "jobs": {"consignment_id": "193", "private_id": "", "public_id": "32683045075475", "org_id": "0", "c_group_id": "568e558123a9b", "is_read": "0", "consignment_type_id": "2", "description": "", "price": "0", "customer_id": "1", "service_id": "67", "is_service_assigned": "1", "is_for_bidding": "0", "is_open_bid": "0", "bidding_deadline": null, "is_confirmed": "0", "quantity": "1", "is_bulk": "0", "length": "0", "breadth": "0", "height": "0", "volume": "0", "weight": "0", "collection_address": "#08-41 335 Woodlands St 32", "is_c_restricted_area": "0", "collection_date": "2016-01-08 10:00:00", "collection_date_to": "2016-01-08 13:00:00", "collection_country": "sg", "collection_timezone": "Asia/Singapore", "delivery_address": "#03-39 33 Ubi Ave 3", "is_d_restricted_area": "0", "delivery_post_code": "639339", "delivery_country": "sg", "delivery_timezone": "Asia/Singapore", "delivery_date": "2016-01-09 10:00:00", "delivery_date_to": "2016-01-09 17:00:00", "delivery_contact_name": "Jason Tey", "delivery_contact_email": "jason.tey@kaisquare.com", "delivery_contact_phone": "98776531", "delivery_company_name": "", "created_date": "2016-01-07 17:39:37", "is_deleted": "0", "deleted_date": null, "created_user_id": "1", "collection_post_code": "730335", "collection_contact_name": "Sean Seah", "collection_contact_number": "96471471", "collection_contact_email": "sean.seah@kaisquare.com", "collection_company_name": "", "send_notification_to_consignee": "1", "consignment_status_id": "8", "remarks": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "ref": null, "tags": null, "picture": null, "display_name": "Extra Small", "from_country": "Singapore", "to_country": "Singapore", "from_zone": "UTC+8", "to_zone": "UTC+8", "consignment_status": "Courier Informed"}}
                    ],
                    "bid for job": [
                         'courier/bidforjob',
                         '{"job_id":"","price":"","service_id":""}',
                         'Bid for job API is used for bid for a current open job/order in the connect platform. Courier may submit a bid multiple times as long as the job is still open.<br>' +
                                 'This API requires three parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>' +
                                 '<strong>price</strong> : bidding price. (mandatory)<br>' +
                                 '<strong>service_id</strong> : Service ID using which you are bidding (mandatory)<br>'
                    ],
                    "withdraw bid": [
                         'courier/withdrawbid',
                         '{"bid_id":""}',
                         'Withdraw bid API is used to give up an existing bid, company won’t see your price in the order. This API only requires the bid ID which you have to withdraw.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>bid_id</strong> : bid ID (mandatory)<br>'
                    ],
                    "List history bidding jobs": [
                         'courier/listbidhistory',
                         '{"company":"","username":"","collection_address":"","delivery_address":"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to":"","delivery_date_from":"","delivery_date_to":"","delivery_time_from":"","delivery_time_to":""}',
                         'List history bidding jobs API is used for retrieve all bids from a courier stored in the platform. This API has several parameters to filter the return list. They are <br>' +
                                 '<strong>company</strong> : Filter results to jobs from a specific company by company name/ oraganisation name<br>' +
                                 '<strong>username</strong> : Filter results to jobs from a specific user by username<br>' +
                                 '<strong>collection_address</strong> : String to search in collection address<br>' +
                                 '<strong>delivery_address</strong> : String to search in delivery address<br>' +
                                 '<strong>collection_date_from</strong> : Date to filter jobs having collection date starts later than provided<br>' +
                                 '<strong>collection_date_to</strong> :  Date to filter jobs having collection date within provided<br>' +
                                 '<strong>collection_time_from</strong> : Time to filter jobs having collection time starts later than provided<br>' +
                                 '<strong>collection_time_to</strong> : Time to filter jobs having collection time within provided<br>' +
                                 '<strong>delivery_date_from</strong> : Date to filter jobs having delivery date starts later than provided<br>' +
                                 '<strong>delivery_date_to</strong> : Date to filter jobs having delivery date within provided<br>' +
                                 '<strong>delivery_time_from</strong> : Time to filter jobs having delivery time starts later than provided<br>' +
                                 '<strong>delivery_time_to</strong> : Time to filter jobs having delivery time within provided<br>'
                    ],
                    "List won jobs": [
                         'courier/listwonjob',
                         '{"company":"","username":"","search":"","collection_address":"","delivery_address":"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to":"","delivery_date_from":"","delivery_date_to":"","delivery_time_from":"","delivery_time_to":""}',
                         'List won jobs API is used for list jobs that are awarded to the courier either by direct assign or by won bid. This API consists of many parameters to filter the list. They are <br>' +
                                 '<strong>company</strong> : Filter results to jobs from a specific company by company name/ oraganisation name<br>' +
                                 '<strong>username</strong> : Filter results to jobs from a specific user by username<br>' +
                                 '<strong>search</strong> : Search string to search in public order ID, username, company name, or order details<br>' +
                                 '<strong>collection_address</strong> : String to search in collection address<br>' +
                                 '<strong>delivery_address</strong> : String to search in delivery address<br>' +
                                 '<strong>collection_date_from</strong> : Date to filter jobs having collection date starts later than provided<br>' +
                                 '<strong>collection_date_to</strong> :  Date to filter jobs having collection date within provided<br>' +
                                 '<strong>collection_time_from</strong> : Time to filter jobs having collection time starts later than provided<br>' +
                                 '<strong>collection_time_to</strong> : Time to filter jobs having collection time within provided<br>' +
                                 '<strong>delivery_date_from</strong> : Date to filter jobs having delivery date starts later than provided<br>' +
                                 '<strong>delivery_date_to</strong> : Date to filter jobs having delivery date within provided<br>' +
                                 '<strong>delivery_time_from</strong> : Time to filter jobs having delivery time starts later than provided<br>' +
                                 '<strong>delivery_time_to</strong> : Time to filter jobs having delivery time within provided<br>' +
                                 '<strong>status_id</strong> : Job status ID to filter by status',
                         {"code": 0, "jobs": [{"id": "194", "public_id": "01889398100259", "private_id": "234234234", "display_name": "Document", "username": "Livin", "company_name": "Organisation A", "height": "0", "breadth": "0", "length": "0", "volume": "0", "weight": "0", "collection_address": "abcgh gh", "is_for_bidding": "0", "is_confirmed": "1", "price": "12", "collection_date_from": "12-01-2016", "collection_time_from": "10:00 AM", "collection_date_to": "12-01-2016", "collection_time_to": "01:00 PM", "delivery_date_from": "14-01-2016", "delivery_time_from": "10:00 AM", "delivery_date_to": "14-01-2016", "delivery_time_to": "05:00 PM", "collection_country": "India", "consignment_status": "Collecting", "delivery_address": "addr1 addr2", "delivery_post_code": "12345", "delivery_country": "India", "delivery_contact_name": "AAA", "delivery_contact_phone": "999999999999", "collection_post_code": "456456", "collection_contact_name": "asdf", "collection_contact_number": "456456456", "service_name": "test with origin", "created_date": "12 Jan 2016 10:44 AM"}]}
                    ],
                    "List job messages": [
                         'courier/listjobmessages',
                         '{"job_id":""}',
                         'List job messages API is used for list all the messages related to a specific job. This API requires only one parameter. This will return all the comments by the person who added this order and all questions by you as well as all the responded questions from other couriers.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>',
                         {"code": 0, "messages": [{"message_id": "51", "username": "Livin", "company_name": "Organisation A", "by_you": "1", "question": "test", "reply": null, "created_date": "2016-01-12 15:53:02", "updated_date": null}, {"message_id": "50", "username": "Livin", "company_name": "Organisation A", "by_you": "1", "question": "tyrtyrtyrty", "reply": null, "created_date": "2016-01-12 15:52:50", "updated_date": null}]}
                    ],
                    "Leave a message": [
                         'courier/leavejobmessages',
                         '{"job_id":"","message":""}',
                         'Leave a message API is used for send message to 6Connect system regarding a specific job. This API requires two parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>' +
                                 '<strong>message</strong> : The actual text you want to send. (mandatory)<br>'
                    ],
                    "Acknowledge Job": [
                         'courier/acknowledgejob',
                         '{"job_id":"","consignment_id":"","price":""}',
                         'Acknowledge job API is used to acknowledge the job, when courier successfully bid a job or a job directly assigned to courier. This API consists of three parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>' +
                                 '<strong>consignment_id </strong> : The consignment id in courier’s own system, so that company is able to inquire the delivery status in courier’s system (mandatory)<br>' +
                                 '<strong>price</strong> : price for the job (mandatory if its an directly assigned job)<br>'
                    ],
                    "Adjust Price": [
                         'courier/adjustprice',
                         '{"job_id":"","price":"","remark":""}',
                         'Adjus price API is used for submit a new price for a won job, but the price needs to be approved by company. If courier sent the price which is same as previous, it will return API_FAILED. This API consists of three parameters. ' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>' +
                                 '<strong>price </strong> : The new total price for the order (mandatory)<br>' +
                                 '<strong>remark </strong> : The reason of changing the price (mandatory)<br>'
                    ],
                    "List price history": [
                         'courier/listpricehistory',
                         '{"job_id":""}',
                         'List price history API is used for list the price that courier submitted before. This API requires only the job_id as parameter.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)'
                    ],
                    "Submit Job track": [
                         'courier/submitjobstate',
                         '{"job_id":"","status_code":"","status_name":"","description":""}',
                         'Submit job track API is used for submit current job status to 6Connect, then company is able to track the actual delivery status. These update will update the order delivery status.<br>' +
                                 ' This API consists of four parameters<br>' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>job_id</strong> : job ID (mandatory)<br>' +
                                 '<strong>status_code </strong> : The status code (mandatory)<br>' +
                                 '<strong>status_name </strong> : The display name of status (mandatory)<br>' +
                                 '<strong>description </strong> : The current status description that needs company to know (mandatory)<br><br>' +
                                 'status_code can only be the following at the moment: request must set the codes correct or it will be an error. <br>Collecting - 101 <br>Collected - 102<br>In-Transit - 301<br>Delivered - 401<br>Failed Delivery - 501<br>Others - 601'
                    ],
                    "Check User exists": [
                         'courier/checkuserexists',
                         '{"username":""}',
                         'Check user exists API is used for check the specific user name whether it exists or not. This API requires one parameter.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>username</strong> : username (mandatory)<br>'
                    ],
                    "Check company exists": [
                         'courier/checkcompanyexists',
                         '{"company_id":""}',
                         'Check company exists API is used for check the specific organisation whether it exists or not. This API requires one parameter.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>company_id</strong> : organisation ID which you have to check whether exist or not. (mandatory)<br>'
                    ],
                    "List service request": [
                         'courier/listservicerequest',
                         '{"search":""}',
                         'List service request API is used for list all service requests available for bidding. This API consists of one parameter. ' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>search</strong> : search string to filter result.<br>'
                    ],
                    "Bid service request": [
                         'courier/bidservicerequest',
                         '{"req_id":"","service_id":""}',
                         'Bid service request API is used for bid for a current open service request in the connect platform. Courier may submit a bid multiple times as long as the request is still open.<br>' +
                                 'This API requires three parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>req_id</strong> : Request ID (mandatory)<br>' +
                                 '<strong>service_id</strong> : Service ID using which you are bidding (mandatory)<br>'

                    ],
                    "Withdraw service request bid": [
                         'courier/withdrawservicerequestbid',
                         '{"bid_id":""}',
                         'Withdraw bid API is used to give up an existing bid, company won’t see your bid no longer. This API only requires the bid ID which you have to withdraw.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>bid_id</strong> : bid ID (mandatory)<br>'

                    ],
                    "List service request bids": [
                         'courier/listservicerequestbids',
                         '{}',
                         'List service request bids API is used for retrieve all service request bids from a courier stored in the platform.<br>'
                    ],
                    "List request messages": [
                         'courier/listrequestmessages',
                         '{"req_id":""}',
                         'List request messages API is used for list all the messages related to a specific service request. This API requires only one parameter. This will return all the comments by the admin member who added this request and all questions by you as well as all the responded questions from other couriers.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>req_id</strong> : Request ID (mandatory)<br>'

                    ],
                    "Leave a message for service request": [
                         'courier/leaverequestmessage',
                         '{"req_id":"","message":""}',
                         'Leave a message for service request API is used for send message to 6Connect system regarding a specific service request. This API requires two parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>req_id</strong> : Request ID (mandatory)<br>' +
                                 '<strong>message</strong> : The actual text you want to send. (mandatory)<br>'

                    ],
                    "List requests for pre-approved services": [
                         'courier/listepreservicerequests',
                         '{"search":""}',
                         'List requests for pre-approved services API is used list the requests from different organisations for pre-approved services. This API requires only the access key. You can pass search string as a parameter for filtering.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>search</strong> : Search string fro service name <br>'

                    ],
                    "Approve request for pre-approved services": [
                         'courier/approvepreservice',
                         '{"req_id":""}',
                         'Approve request for pre-approved services API is used for assign a service to an organisation based on the request from organisation admin member. This action will reject all other requests for this particular service from other organisations automatically. This API requires one parameter.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>req_id</strong> : Request ID (mandatory)<br>'

                    ],
                    "Reject request for pre-approved services": [
                         'courier/rejectpreservice',
                         '{"req_id":"","remarks":""}',
                         'Reject request for pre-approved services API is used for reject request from an organisation admin member to add a service as pre-approved service for their organisation. This API requires two parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>req_id</strong> : Request ID (mandatory)<br>' +
                                 '<strong>remarks</strong> : Reason for reject request '

                    ],
                    "Update Proof of Delivery": [
                         'courier/updatePOD',
                         '{"consignment_id":"","is_sign":"","remarks":""}',
                         'Update Proof of Delivery API is to update any files related to the POD. If the image is a signature, it should be indicated. This API requires five parameters.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>consignment_id</strong> : Consignment ID (mandatory)<br>' +
                                 '<strong>pod_image</strong> : Image that is going to attach through api , this will automatically generated when you choose a file to upload. (Mandatory)<br> ' +
                                 ' <strong>filename</strong> : Original file name. This will automatically generated when you choose a file to upload. (Mandatory)<br>' +
                                 '<strong>is_sign</strong> : Flag to indicate signature or othe image (mandatory)<br>' +
                                 '<strong>remarks</strong> : Any remarks' +
                                 '<br><br>' +
                                 '<h5>Test API Upload</h5><input type="file" id="api_image" />'

                    ]

               };
               $(function () {
                    $('#request-url').val('');
                    $('#request-data').val('');
                    $('#request-send').on('click', function () {

                         var valid = true;
                         if ($('#request-url').val() == '') {
                              $('#request-error').append('<p>Please enter URL</p>');
                              valid = false;
                         }
                         if (!valid)
                              return;
                         $.ajax({
                              url: '<?php echo base_url('REST'); ?>/curl.php',
                              type: 'post',
                              data: $.parseJSON($.trim($('#request-data').val())),
                              async: true,
                              success: function (response) {
                                   $('#curl-response-data').html(response);
                              }
                         });
                         var request_data = $.trim($('#request-data').val());
                         var token = $.trim($('#request-token').val());
                         if (request_data != '') {
                              var dataToSend = $.parseJSON(request_data);
                              //alert(dataToSend);
                              if (token != '') {
                                   dataToSend.access_key = token;
                              }
                         } else {
                              var dataToSend = {};
                              if (token != '') {
                                   dataToSend.access_key = token;
                              }
                         }

                         $.ajax({
                              url: $('#request-url').val(),
                              type: $('#request-method').val(),
                              dataType: 'json',
                              crossDomain: true,
                              jsonp: true,
                              // username: "sshrestha",
                              // password: "password1",
                              data: dataToSend,
                              async: false,
                              beforeSend: function () {
                                   $('#response-data').html('Loading...');
                              },
                              success: function (response) {
                                   var html = print_r(response);
                                   $('#response-data').html("<pre>" + html + "</pre>");
                              },
                              error: function () {

                                   $('#response-data').html('Error');
                              }
                         });
                    });
                    $.each(apis, function (api) {
                         $('#select_api').append('<option value="' + api + '">' + api + '</option>');
                         //$("#test").append(apis[api][2]+'<br>');
                    });
                    $('#select_api').change(function () {
                         var api = $(this).val();
                         if (api == "") {
                              $('#request-url').val('');
                              $('#request-data').val('');
                              $('#description-data .desp_title').text('');
                              $('#description-data div').html('');
                         } else {
                              $('#request-url').val(baseUrl + apis[api][0]);
                              $('#request-data').val(apis[api][1]);
                              $('#description-data .desp_title').text(api);
                              $('#description-data div').html(apis[api][2]);
                              if (apis[api][3]) {
                                   $('#description-data div').append('<h5>Sample Response</h5><pre>' + JSON.stringify(apis[api][3], null, "\t") + '</pre>');
                              }
                         }
                    });
               });
               String.prototype.repeat = function (times) {
                    return (new Array(times + 1)).join(this);
               };
               function print_r(variable, level) {
                    level = 0;
                    level++;
                    var tab = "\t".repeat(level);
                    var tabend = "\t".repeat((level - 1));
                    var html = "";
                    if ($.isArray(variable)) {

                         html += "[\n";
                         $.each(variable, function (key, val) {
                              html += tab + print_r(val, level);
                              html += (variable.length == key + 1) ? "\n" : ",\n";
                         });
                         html += tabend + "]";
                    }
                    else if ($.isPlainObject(variable)) {

                         html += "{\n";
                         var len = 0;
                         $.each(variable, function (i, elem) {
                              len++;
                         });
                         var index = 0
                         $.each(variable, function (key, val) {
                              html += tab + '"' + key + '": ' + print_r(val, level);
                              html += (len == index + 1) ? "\n" : ",\n";
                              index++;
                         });
                         html += tabend + "}";
                    } else if (typeof variable === "string") {

                         return '"' + variable + '"';
                    } else {

                         return variable;
                    }
                    return html;
               }
               function readImage() {
                    if (this.files && this.files[0]) {
                         var FR = new FileReader();
                         var filename = this.files[0].name;
                         FR.onload = function (e) {
                              var base64 = e.target.result;
                              var data = base64.split(',');
                              var encoded = data[1];
                              var file = {
                                   file: encoded,
                                   filename: filename
                              };
                              var template = $("#api_upload_image").html();
                              Mustache.parse(template);
                              var newRequestData = Mustache.render(template, file);
                              var requestData = $("#request-data");
                              requestData.val(requestData.val().slice(0, -1) + $('<div>').html(newRequestData).text());

                         };
                         FR.readAsDataURL(this.files[0]);
                    }
               }
               $(document).on('change', "#api_image", readImage);
          </script>

     </head>

     <body style="margin-top: 30px;">

          <div id="test"></div>
          <div class="container-fluid">

               <div class="row-fluid">

                    <div class="span12 well">

                         <h2>REQUEST</h2>

                         <div id="request-error"></div>

                         <div id="request-form">
                              <form class="form-inline" id="request_form">
                                   <select name="request-method" id="request-method" class="span2">
                                        <option value="POST">POST</option>
                                        <!--<option value="GET">GET</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>-->
                                   </select>
                                   <select id="select_api" class="span3">
                                        <option value="">select API</option>
                                   </select>
                                   <input type="text" id="request-token" placeholder="Token" class="span2" value="<?= $access_key ?>" />
                                   <input type="text" name="request-url" id="request-url" placeholder="url" class="span3" value="" />
                                   <a href="javascript:;" class="btn span2 pull-right" id="request-send">Send</a>
                                   <textarea name="request-data" id="request-data" class="span12" style="margin-top: 10px; height: 50px;"></textarea>
                              </form>
                         </div>

                    </div>

               </div>
               <div class="row-fluid">

                    <div class="span12 well">

                         <h3>DESCRIPTION</h3>

                         <div id="description-data">
                              <h3 class="desp_title"></h3>
                              <div></div>
                         </div>

                    </div>

               </div>             

               <div class="row-fluid">

                    <div class="span12 well">

                         <h3>RESPONSE</h3>

                         <div id="response-data"></div>

                    </div>

               </div>
               <div class="row-fluid">

                    <div class="span12 well">

                         <h3>API RESULT CODES</h3>

                         <div id="outputs-data">
                              <strong>0</strong> : API access successfully<br />
                              <strong>100</strong> : Failed to access API<br />
                              <strong>999</strong> : Error occurred during API call <br /> 
                         </div>

                    </div>

               </div>
               <div class="row-fluid">

                    <div class="span12 well">

                         <h3>CURL-RESPONSE</h3>

                         <div id="curl-response-data"></div>

                    </div>

               </div>

          </div>
          <script>
               var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";
               var reload_w = function () {
                    setTimeout(function () {
                         $.post(BASE_URL + 'couriers/courier_reloader').success(function (data) {
                              if (data === 1) {
                                   location.reload();
                              }
                         });
                         reload_w();
                    }, 5000);
               };
               $(function () {
                    reload_w();
               });
          </script>
          <script id="api_upload_image" type="x-tmpl-mustache">,"filename":"{{filename}}","pod_image":"{{file}}"}</script>
     </body>

</html>