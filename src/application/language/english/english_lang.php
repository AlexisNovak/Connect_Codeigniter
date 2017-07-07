<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
   */
  $lang['6connect_email_notification'] = '6connect email notification';
  $lang['website_title'] = '6Connect';
  $lang['website_welcome'] = '6Connect';
  $lang['website_intro'] = "Deliveries simplified for you.";
  $lang['website_copy_right'] = "Copyright &copy; 2015 - Another fine product of Phokki Pte. Ltd.";
  $lang['website_copy_right_phokki'] = "Copyright &copy; 2015 - Another fine product of <strong>Phokki Pte. Ltd.</strong>";
  $lang['website_footer'] = " - Another fine product of Phokki Pte. Ltd.";
  $lang['website_welcome_username'] = 'Welcome, %s.';
  $lang['website_account'] = 'Account Settings';
  $lang['website_profile'] = 'Manage Profile';
  $lang['website_linked'] = 'Linked Accounts';
  $lang['website_password'] = 'Password';
  $lang['website_manage_users'] = 'Manage Users';
  $lang['website_manage_permissions'] = 'Manage Permissions';
  $lang['website_manage_roles'] = 'Manage Roles';
  $lang['website_sign_out'] = 'Sign out';
  $lang['website_sign_in'] = 'Sign in';
  $lang['website_sign_up'] = 'Register';
  $lang['website_connect_with_facebook'] = 'Connect with Facebook';
  $lang['website_page_rendered_in_x_seconds'] = 'Rendered in %s seconds';
  $lang['website_create'] = 'Create';
  $lang['website_update'] = 'Update';
  $lang['website_cancel'] = 'Cancel';
  $lang['user_dashboard'] = 'User Dashboard';
  $lang['admin_dashboard'] = 'Admin Dashboard';
  $lang['password_pattern'] = "Password must be at least 8 characters long and contain no space. Only alphanumeric letters and the following special characters are allowed !@#$^*.";
  $lang['username_info'] = "Your registered email is your unique username to 6Connect.";
  $lang['facebook_no_email'] = "Sorry, we couldn't get your email from your facebook account, please check your facebook privacy";

  $lang['loading'] = "loading&hellip;";
  $lang['nothing_to_display'] = "no data to display";
  $lang['back_btn'] = "Back";
  $lang['add_btn'] = "Add";
  $lang['save_btn'] = "Save";
  $lang['edit_btn'] = "Edit";
  $lang['save_as_draft_btn'] = "Save as Draft";
  $lang['update_btn'] = "Update";
  $lang['submit_btn'] = "Submit";
  $lang['confirm_btn'] = "Confirm";
  $lang['upload_btn'] = "Upload";
  $lang['cancel_btn'] = "Cancel";
  $lang['print_btn'] = "Print Note";
  $lang['find_btn'] = "Find";
  $lang['search_btn'] = "Search";
  $lang['warning'] = "Warning";
  $lang['yes'] = "Yes";
  $lang['no'] = "No";
  $lang['ok'] = "OK";
  $lang['next'] = "Next";
  $lang['action'] = "Action";
  $lang['confirm'] = "Confirm";
  $lang['search_label'] = "Search";
  $lang['go_back_login'] = "Go back to login page";
  $lang['request_for_change'] = "Request for change";
  $lang['request_for_change_title'] = "Request for change";

  $lang['delete_confirm'] = "Are you sure ? Deleted data can't be rollback.";
  $lang['cancel_confirm'] = "Are you sure you want to cancel this order?";
  $lang['reject_order_confirm'] = "Please provide a reason for your customers on why you want to cancel this job?";
  $lang['approve_confirm'] = "Are you sure you want to approve this service?";
  $lang['reject_confirm'] = "Are you sure you want to reject this service?";
  $lang['suspend_member_confirm'] = "Are you sure you want to suspend this member? ";
  $lang['suspend_group_confirm'] = "Are you sure you want to suspend this team? ";
  $lang['suspend_service_confirm'] = "Are you sure you want to suspend this service? ";
  $lang['activate_member_confirm'] = "Are you sure you want to activate this member? ";
  $lang['activate_group_confirm'] = "Are you sure you want to activate this team? ";
  $lang['activate_service_confirm'] = "Are you sure you want to activate this service? ";

  $lang['congrats_service_tender'] = "Congratulation for your successful <strong>Service Tender!</strong>";
  $lang['congrats_service_tender_info'] = "You have awarded this contract to";
  $lang['use_info'] = "You and your members can select the awarded service directly from service directory, or when creating a new delivery order.";
  $lang['manage_info'] = "Manage the services setting such as setting the permission for the usage of this service by members and teams.";
  $lang['use_link'] = "Go to Service Directory";
  $lang['manage_link'] = "Click here to manage";

  $lang['no_org_title'] = "Sorry, there is a little issue...";
  $lang['no_org_info'] = "We noticed that you have not created or join an organisation yet. To create delivery request, you had to be a member of at least one organisation.";
  $lang['new_org_info'] = "You can click below to create your own organisation";
  $lang['add_org_btn'] = "Add Organisation";
  /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
   */

  $lang['group_name_error'] = "Name must be given";
  $lang['group_code_error'] = "Team code must be given";
  $lang['group_org_error'] = "Organisation must be given";
  $lang['group_add_success'] = "New team added successfully";
  $lang['group_edit_success'] = "Team data edited successfully";
  $lang['group_errors'] = "Please clear the errors.";
  $lang['assigned_services_to_group'] = "Services assigned to this team";
  /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
   */

  $lang['service_name_error'] = "Name must be given";
  $lang['service_code_error'] = "Service code must be given";
  $lang['service_org_error'] = "Organisation must be given";
  $lang['service_add_success'] = "New service added successfully";
  $lang['service_edit_success'] = "Service data edited successfully";
  $lang['service_errors'] = "Please clear the errors.";
  $lang['limit_use'] = "Limits the use of this service by members and teams";
  $lang['allow_limit_use_confirm'] = "Are you sure you want to limits the use of this service by members and teams?";
  $lang['not_limit_use_confirm'] = "Are you sure you want to allow the use of this service by all members of this organisation?";
  $lang['allow_limit_use'] = "Limited the use of this service by members and teams";
  $lang['not_allow_limit_use'] = "Allowed the use of this service by all members of this organisation";

  /*
    |--------------------------------------------------------------------------
    | Members
    |--------------------------------------------------------------------------
   */
  $lang['member_details_title'] = 'Member Details';
  $lang['member_email'] = 'Email';
  $lang['member_fullname'] = 'Full Name';
  $lang['member_firstname'] = 'First Name';
  $lang['member_lastname'] = 'Last Name';
  $lang['member_dateofbirth'] = 'Date of Birth';
  $lang['member_description'] = 'Description';
  $lang['member_postalcode'] = 'Postal Code / Zip Code';
  $lang['member_country'] = 'Country';
  $lang['member_phone_number'] = 'Phone Number';
  $lang['member_fax_no'] = 'Fax Number';
  $lang['member_save'] = 'Save changes';
  $lang['member_note'] = 'Note';
  $lang['member_scheme'] = 'Scheme';
  $lang['member_status'] = 'Status';
  $lang['member_role'] = 'Role';
  $lang['member_group'] = 'Team';
  $lang['member_edit'] = 'Edit Member';
  $lang['new_member'] = 'New Member';
  $lang['add_member'] = 'Add Member';
  $lang['invite_member'] = 'Invite';
  $lang['unknown_member'] = 'Invalid Email';
  $lang['unknown_group'] = 'Unknown team';
  $lang['add_new_member'] = 'Add New Member';
  $lang['invite_title'] = "Email is not found but you still can add this good fellow!";
  $lang['invite_info'] = "We will send an email invitation to this fellow and invite him to join. Once he/she joined, it will auto appear in your member list.";
  $lang['member_register_success'] = "<p>Thank you for registering with 6Connect! We gladly welcome you to join our platform which can provide you with quality delivery services. To ensure all delivery requests are genuine, we will be sending you an email to verify your account first. </p><p>Please check your email inbox for the verification email that we will be sending you shortly. In case you did not receive it, please do check your spam box as it sometimes accidentally slipped into there</p>";
  /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
   */
  $lang['manage_profile'] = "Manage Profile";
  $lang['manage_settings'] = "Account Setting";
  $lang['manage_password'] = "Change Password";
  $lang['logout'] = "Log out";
  $lang['organizations'] = "Organisations";
  $lang['my_organizations'] = "My Organisations";
  $lang['orders'] = "Orders";
  $lang['delivery_request'] = "Delivery Request";
  $lang['new_order'] = "New Delivery Request";
  $lang['multiple_order'] = "Multiple Deliveries";
  $lang['partner'] = "Partners";
  $lang['couriers'] = "Couriers";
  $lang['my_contacts'] = "Address Book";
  $lang['available_services'] = "Available Services";
  $lang['services'] = "Services";
  $lang['tip_of_the_day'] = "Tip of the day";
  $lang['tender_request'] = "Tender Requests";
  $lang['t_r_delivery'] = "Delivery";
  $lang['t_r_service'] = "Service";
  $lang['accounts'] = "Accounts";
  $lang['ac_members'] = "Members";
  $lang['ac_organisations'] = "Organisations";
  $lang['manage_payment_account'] = "Payment Methods";
  /*
    |--------------------------------------------------------------------------
    | orders
    |--------------------------------------------------------------------------
   */

  $lang['order_filter_org'] = "All Organisations";

  $lang['orders_table_consignment_id'] = "Public ID";
  $lang['orders_table_private_id'] = "Private ID";
  $lang['orders_table_username'] = "User";
  $lang['orders_table_collection'] = "Collection";
  $lang['orders_table_delivery'] = "Delivery";
  $lang['orders_table_services'] = "Service";
  $lang['orders_table_organisation'] = "Organisation";
  $lang['orders_table_date'] = "Create On";
  $lang['orders_table_status'] = "Status";

  $lang['order_tile'] = "Orders";
  $lang['view_order_title'] = "View Orders";
  $lang['new_delivery_tender'] = "New Delivery Tender";
  $lang['new_order_title'] = "New Delivery Request";
  $lang['edit_order_title'] = "Edit Request";
  $lang['order_public_id'] = "Public Order ID";
  $lang['order_private_id'] = "Private Order ID";
  $lang['order_tracking_id'] = "Tracking ID";
  $lang['order_assigned_id'] = "Courier assigned ID";
  $lang['order_date'] = "Create On";
  $lang['orders_table_bid'] = "Your bid";
  $lang['order_caption'] = "What do you want to deliver?";
  $lang['order_caption_c_d'] = "Where do you want us to collect and deliver to?";
  $lang['order_caption_d_confirm'] = "Shall we proceed with the following?";
  $lang['order_caption_service'] = "What type of delivery service you will like to use?";
  $lang['order_item_ph'] = "What is the item? Letter? Parcel?";
  $lang['order_item'] = "Delivery Item(s)";
  $lang['order_quantity'] = "Quantity";
  $lang['order_quantity_ph'] = "Quantity";
  $lang['order_remark_ph'] = "Delivery remarks for couriers i.e. product description";
  $lang['order_ref_ph'] = "Other tracking no. or reference order no.";
  $lang['order_remark'] = "Delivery Remarks";
  $lang['order_picture_info'] = "Add a photo of the parcel item by drag-n-drop into here or click here to upload.";
  $lang['order_file_info_a'] = "Drag and drop your file into this panel";
  $lang['order_file_info_attachments'] = "Drop your file here to upload";
  $lang['drag_enter_info'] = "Yup, drop here";
  $lang['order_file_info_a_sub'] = "(or click here to open file dialog)";
  $lang['order_file_info_attachments_sub'] = "(Note that only the following file types are supported: xls, xlsx, doc, docx, pdf, png, jpg)";
  $lang['order_file_info_b'] = "(B) Upload delivery locations using your own excel file format.";
  $lang['order_dimension_caption'] = "Provide the total dimension and physical weight of item(s) if it is bulky. As a guide if you are unable to hold all the items with one hand, it probably a bulky item. (up to 3 decimal points are allowed for length, breadth, height and weight)";
  $lang['order_length'] = "Length";
  $lang['order_breadth'] = "Breadth";
  $lang['order_height'] = "Height";
  $lang['order_v_weight'] = "Volumetric Weight";
  $lang['order_actual_weight'] = "Actual Physical Weight";
  $lang['cm'] = "cm";
  $lang['cm3'] = "cubic cm";
  $lang['kg'] = "KG";
  $lang['contact_person'] = "Contact Person";
  $lang['pickup_time'] = "Pickup Time";
  $lang['delivery_period'] = "Delivery Time";
  $lang['collect_from'] = "Collect From";
  $lang['custom-collection-period'] = "Custom";
  $lang['custom-delivery-period'] = "Custom";
  $lang['collect_from_sub'] = "Where to collect";
  $lang['collection_address'] = "Collection Address";
  $lang['collect_add_l1'] = "Block No., Unit No., Floor No.";
  $lang['order_add_l1_info'] = "This will not be displayed to bidders.";
  $lang['collect_add_l2'] = "Road Name, Building Name, City/Town etc.";
  $lang['collect_zipcode'] = "Postal/Zip Code";
  $lang['collect_country'] = "Collection country";
  $lang['restricted_area_tooltip'] = "Restricted Area";
  $lang['collect_restrict'] = "Restricted Area";
  $lang['deliver_restrict'] = "Restricted Area";
  $lang['collect_date'] = "Collection date &amp; time";
  $lang['collect_period'] = "Collection Period";
  $lang['deliver_to'] = "Deliver To";
  $lang['deliver_from_sub'] = "Where to deliver";
  $lang['delivery_address'] = "Delivery Address";
  $lang['deliver_add_l1'] = "Block No., Unit No., Floor No.";
  $lang['deliver_add_l2'] = "Road Name, Building Name, City/Town etc.";
  $lang['deliver_zipcode'] = "Postal/Zip Code";
  $lang['deliver_country'] = "Delivery country";
  $lang['deliver_date'] = "Delivery by";
  $lang['primary_contact'] = "Primary Contact";
  $lang['change_timezone'] = "change timezone";
  $lang['based_on'] = "Based on";
  $lang['postalcode'] = "Postal Code";
  $lang['contact_name'] = "Contact Name";
  $lang['order_email'] = "Email (optional)";
  $lang['phone_number'] = "Phone number e.g. +65 63975818";
  $lang['contact_number'] = "Contact Number";
  $lang['order_notify'] = "Keep recipient aware of the status via email once it has been collected";
  $lang['service_assignment'] = "Get Quotation Information";
  $lang['assignment_description'] = "You may assign the request directly to an pre-approved courier's service that your organisation has existing arrangement with. If you select &quot;Request for Bid&quot;, the order will be submitted for bidding by couriers in 6Connect logistic marketplace.";
  $lang['assignment_description'] = "Choose from a list of recommended services or get a price quote from the couriers. Remember to select your billing organisation first.<br> (Note: There might be restrictions imposed by your billing organisation which may limit your delivery options.)";
  $lang['assign_service_info'] = "Select the Service to assign the request to";
  $lang['assign_service'] = "Please select the organisation right at the top of this page first";
  $lang['assign_service_empty'] = "You have not been assigned any service. Please contact your Organisation Admin.";
  $lang['assignment_info'] = 'Service description and Active period';
  $lang['deadline'] = "set a tender deadline";
  $lang['deadline_title'] = "Tender Deadline";
  $lang['order_volume'] = "Total volume";
  $lang['order_open_bid'] = "Open Bid (Any couriers can bid for this job)";
  $lang['order_weight'] = "Weight";
  $lang['logout'] = "Log out";
  $lang['timezone'] = "Timezone";
  $lang['service_list'] = "Services";
  $lang['pending_order'] = "Draft";
  $lang['confirm_order'] = "Confirm Request";
  $lang['approve_order'] = "Approve Price";
  $lang['reject_price'] = "Reject";
  $lang['confirm_order_success'] = "Your delivery request Confirmed Successfully";
  $lang['confirm_mass_order_success'] = "All delivery orders are added.";
  $lang['order_processing'] = "Your delivery request is processing, please wait for a moment";
  $lang['uploadattach_ment'] = "Processing your request";
  $lang['try_again'] = "Something went wrong. Please try again.";
  $lang['clear_error'] = "Please clear errors";
  $lang['order_detail_tab'] = "Details";
  $lang['order_bidders_tab'] = "Tenders";
  $lang['order_msg_tab'] = "Messages";
  $lang['order_log_tab'] = "Activity Log";
  $lang['order_pod_tab'] = "Proof of Delivery";
  $lang['order_deleted'] = "Your delivery request Deleted Successfully";
  $lang['order_criteria'] = "You are responsible for making sure your Parcel meet the criteria. Additional charges might incur if parcel does not meet the criteria.<br> <br>Perishable product delivery is not available.";
  $lang['your_organisation'] = "Choose the Billing Organisation";
  $lang['empty_org_info'] = "Leave blank if its personal delivery";
  $lang['select_org_ph'] = "This is a personal delivery order";
  $lang['bid_market_info'] = "Place your order into our courier marketplace and receive instant quotation for the job";
  $lang['open_bid_info'] = "Allow all couriers in the market to see this delivery and submit their quote";
  $lang['open_bid'] = "Open Tender";
  $lang['closed_tender'] = "Restricted";
  $lang['open_tender_info'] = "Allow all couriers in our marketplace to bid for this job.<br>If unchecked, only pre-approved couriers by the organisation will be allowed to bid.";
  $lang['closed_tender_info'] = "Your organisation has a list of pre-approved couriers that is allowed to take the job. <br>You are only allowed to get quotes from these couriers.";
  $lang['open_tender_info_service'] = "Allow all couriers in our marketplace to bid for this request. If unchecked, only pre-approved couriers by the organisation will be allowed to bid.";
  $lang['closed_tender_info_service'] = "Your organisation has a list of pre-approved couriers that is allowed to take the request. You are only allowed to get quotes from these couriers.";
  $lang['no-approved-couriers'] = "Your organisation does not have any pre-approved couriers to get quote from. <br>Please contact your organisation admin.";
  $lang['estimated_price'] = "Estimated Price";
  $lang['deliver_before'] = "Deliver before";
  $lang['billing_org'] = "Billing Organisation";
  $lang['confirm_info'] = "Please confirm that the delivery request is correct and click &quot;Confirm Request&quot;.";
  $lang['confirm_info'] = "Please review your order and click &quot;Confirm&quot; to submit the order to the courier(s).";
  $lang['confirm_info_sub'] = "(Remember to <strong>PRINTOUT</strong> the consignment note on the next page.)";
  $lang['item_detail'] = "Order Summary";
  $lang['requested_service'] = "Requested Service Information";
  $lang['collection_from_h'] = "Collect From";
  $lang['delivery_to_h'] = "Deliver To";
  $lang['collection_window'] = "Collection Window";
  $lang['delivery_window'] = "Delivery Window";
  $lang['item'] = "Item";
  $lang['remarks'] = "Remarks";
  $lang['contact_info'] = "Contact info";
  $lang['from'] = "From";
  $lang['to'] = "To";
  $lang['no_email'] = "No email provided";
  $lang['no_company'] = "No company name provided";
  $lang['back_to_service'] = "Back to Select Service";
  $lang['available_service_intro'] = " A list of available services that are available publicaly, or approved by your billing organisatio to use.";
  $lang['available_service_intro'] = "Select from a list of available services to deliver your delivery order";
  $lang['use_available_service'] = "Choose a Service";
  $lang['no_available_service_info'] = "No suitable services available for your delivery request. You may wish to consider putting up your delivery request for bidding by the couriers.";
  $lang['not_service_available'] = "Your selected service do not match the delivery requirements. Please choose from the current list instead.";
  $lang['get_a_quote'] = "Get a Quote";
  $lang['direct_assign'] = "Assign Direct";
  $lang['direct_assign_info'] = "Assign this order to your own courier that is not registered with 6Connect";
  $lang['new_pod'] = "New POD";
  $lang['new_pod_title'] = "Add New POD";
  $lang['pod_remarks'] = "Remarks";
  $lang['pod_image'] = "POD Image";
  $lang['pod_sign'] = "Signature";
  $lang['pod_picture_info'] = "Providing us with a picture of the proof of delivery.";
  $lang['tags_title'] = "Add Tags";
  $lang['tags_ph'] = "Tags...";
  $lang['tags'] = "Tags";
  $lang['ref_title'] = "Other Reference No.";
  $lang['ref_label'] = "Ref #";
  $lang['no_ref'] = "No reference number";
  $lang['no_tag'] = "No tag";
  $lang['no_remarks'] = "No remarks provided";
  $lang['restricted_area_info'] = "This location is a restricted area.";
  $lang['delivery_time_info'] = "Note - Please note that if a public holiday falls within the requested delivery time, the courier reserve the rights to extend the delivery time with reasonable consideration.";
  $lang['pickup_time_info'] = "Tips - Putting a wider time range will give you more services to choose from.";
  $lang['print_info'] = "Your delivery request is on the way to <strong>%s</strong> for confirmation. We will keep you informed via email on the progress.<br> Please <strong>printout the delivery note</strong> in advance, and pass to the courier when they come to pickup the delivery.";
  $lang['print_info_bid'] = "Your delivery request is on the way to the bidding marketplace. We will keep you informed via email on the progress.<br> Please <strong>printout the delivery note</strong> in advance, and pass to the courier when they come to pickup the delivery.";
  $lang['collection_company_name'] = "Company Name";
  $lang['delivery_company_name'] = "Company Name";
  $lang['threshold_price'] = "Max. Price";
  $lang['threshold_info'] = "If final price is higher than max.price, you will be notify for approval.";
  $lang['estimated_price_shot'] = "Est. Price";
  $lang['approved_service'] = "Approved Service";
  $lang['create'] = "Create";
  $lang['new_billing_org'] = "New Billing Organisation.";
  $lang['delivery_price'] = "Delivery Price";
  $lang['important_note'] = "IMPORTANT NOTE:";
  $lang['print_important_note'] = "Courier informed. Please PRINTOUT the delivery note below and paste on your parcel.";
  $lang['getting_quote'] = "Getting a quote?";
  $lang['getting_quote_info'] = "If you are intending to get couriers' quotations for this multiple delivery job request, please use the %s instead.";
  $lang['getting_quote_info_2'] = "Once you have awarded a service tender, you can use the awarded service for this job.";
  $lang['thank_you'] = "Thank you!";
  $lang['third_party'] = "Third Party";
  $lang['third_party_info'] = "Assign delivery for a third party courier";
  $lang['third_party_email_ph'] = "Email";
  $lang['third_party_email_title'] = "Email ID";
  $lang['request_for_direct'] = "Request for Direct Assign";
  $lang['external'] = "External";
  $lang['view_order_email_title'] = "View order details";
  $lang['delivery_request_change'] = 'WARNING: Are you sure you want to edit this request, it will withdraw all the existing biddings. <br />Do you want to continue ?';
  $lang['nan'] = "Not Applicable";
  $lang['things_to_note'] = "Things to Note";
  $lang['things_to_note_content'] = 'Selected courier will reserve the right to reject/cancel your order at a very last min, if there is no driver to pick up for deliveries of the said items.<br><br>6Connect will not be able to hold liability for any related claims with regards to deliveries of the said items.<br><br>6Connect will not be liable for any change of properties and/or taste of perishable food items prior to pickup, during and upon arrival of the delivery.<br><br>Please ensure that the item will be properlyu wrapped tp minimise the damage during transition.<br><br><br><span style="font-size:13px;">For all enquiries please contact: <br><br> 6Connect Customer Service<br>Tel: (65) 6397 5818, (65) 6397 5817 <br> Email: <a href="mailto:enquiry@6connect.biz14">enquiry@6connect.biz</a><span>';
  $lang['payment_methods'] = "Payment Methods";
  $lang['parcels'] = "Parcels";
  $lang['bidding_type'] = "Bidding Type";
  $lang['bidding_expiry_date'] = "Bidding Expiry Date";
  $lang['no_deadline'] = "No deadline set.";
  $lang['muliple_note'] = "To ensure you get your delivery done properly, please provide enough information in your file for the courier to give you a precise quotation.<br><br>Information such as parcel description, full-addresses for all locations and quantity to be deliver to each location should be clearly stated.";



  $lang['order_bidders_courier'] = "Courier";
  $lang['order_bidders_service'] = "Service";
  $lang['order_bidders_price'] = "Price";
  $lang['order_bidders_remarks'] = "Remarks";
  $lang['order_accept'] = "Accept";
  $lang['order_accepted'] = "Accepted";

  $lang['order_msg_title'] = "Message for couriers";
  $lang['order_msg_title_sub'] = "messages replied so far";

  $lang['order_log_timestamp'] = "Timestamp";
  $lang['order_log_details'] = "Log";
  $lang['multi_order'] = "Multiple Orders";

  $lang['multi_order_title'] = "Upload Multiple Orders";
  $lang['m_upload_title'] = "Upload your deliveries";
  $lang['m_upload_sub'] = "Please use the 6connect template to fill in your delivery detail. You may download the template ";
  $lang['verification'] = "Verification";
  $lang['verfication_suc'] = "Great, uploading is successful!!!";
  $lang['delveries_found'] = "deliveries found.";
  $lang['delveries_exceed'] = "(Note: We have discover more than 250 deliveries inside your file. The remaining delivery orders are discarded as we support up to 250 deliveries per file only.)";
  $lang['error_log_p'] = "errors has been found. Click %s to download error log.<br>Please correct the error(s) in your original file and upload again.";
  $lang['error_log'] = "here";
  $lang['error_log_proceed'] = "If you like to proceed and process the deliveries that has correct proper information, you may do so by clicking the <strong>Next</strong> button.";
  $lang['accept_bid_success'] = "Accepted";
  $lang['accept_bid_failed'] = "Failed to accept bid.";

  $lang['order_price_changed_email_subject'] = 'The delivery price has been changed (delivery id: %s)';
  $lang['order_price_changed_email'] = "The delivery %s price has been changed ($%s -> $%s) by courier %s, please go check the 6Connect to approve the price if you would like to continue the delivery";

  $lang['multiple_order_from_date_invalid'] = 'Invalid delivery date (from)';
  $lang['multiple_order_to_date_invalid'] = 'Invalid delivery date (to)';
  $lang['thridparty_new_job_email_subject'] = 'New Job from 6Connect - Courier Job from %s';
  $lang['thridparty_new_job_email'] = "%s (%s) have assign a courier job to you using<br> 6connect Platform.<br><br>"
          . "Please click the link below to see the order and<br> Also update the status.";

  $lang['restricted_area_applicable'] = 'Kindly indicate if location is within the areas below:';
  $lang['not_applicable'] = 'Not Applicable';
  $lang['free_service'] = "<br><br>This is a free service for you and %s";
  /*
    |--------------------------------------------------------------------------
    | Organisation
    |--------------------------------------------------------------------------
   */
  $lang['organisation_title'] = "Organisations";
  $lang['create_new_organisation'] = 'Create New Organisation';
  $lang['new_organisation'] = 'New Organisation';
  $lang['find_organisation'] = 'Find';
  $lang['search_organisation'] = 'Search';
  $lang['organisation_name'] = 'Organisation / Company Name';
  $lang['organisation_shortname'] = 'Short name';
  $lang['organisation_website'] = 'Website';
  $lang['organisation_description'] = 'Description';
  $lang['organisation_admins'] = 'Admin Users';
  $lang['create_organisation_save'] = 'Create';
  $lang['edit_organisation'] = 'Edit';
  $lang['create_organisation_cancel'] = 'Cancel';
  $lang['edit_organisation_save'] = 'Save Changes';
  $lang['organisation_updated_msg'] = "Organization details updated";
  $lang['table_organisation_name'] = 'Name';
  $lang['members_tab'] = "Members";
  $lang['leads_tab'] = "Leads";
  $lang['settings_tab'] = "Settings";
  $lang['service_req_tab'] = "Service Requests";
  $lang['activity_tab'] = "Activities";
  $lang['schemes_tab'] = "Schemes";
  $lang['group_tab'] = "Teams";
  $lang['advanced_tab'] = "Advanced Setting";
  $lang['service_tab'] = "Pre-approved Services";
  $lang['api_tab'] = "Integration";
  $lang['active_service_tab'] = "Active Services";
  $lang['order_tab'] = "Orders";
  $lang['reports_tab'] = "Reports";
  $lang['pre_bidders_tab'] = "Bidding Configuration";
  $lang['products_tab'] = "Product/Services";
  $lang['products_tab_title'] = "Products";
  $lang['org_picture_info'] = "Add a photo of the organisation by drag-n-drop into here or click here to upload.";

  $lang['services_tab_info'] = "Services are the delivery services that are pre-approved in your organisation for direct use.<br>There are 3 ways to add services into your organisation.";
  $lang['orders_tab_info'] = "Orders are the delivery jobs that are created in this organisation.<br> You can also see the status of your deliveries here.";
  $lang['members_tab_info'] = "Members are staffs/employees of the organisation who will be using 6Connect for making deliveries in the day-to-day operations. E.g. David, the sales personnel, might need to regularly send sample materials or sales agreements to customers for approval.";
  $lang['groups_tab_info'] = "Team help to organise the members with similar functions into the same group i.e. departments or divisions. Admin can assign services to selected teams e.g. Sales Team may have access to &quot;Express Overseas Delivery&quot; service, while Procurement Team only can use &quot;Next day local delivery&quot; service.";
  $lang['service_req_tab_info'] = "Service requests are requests that are put up by you to the pool of couriers in 6Connect to provide a long-term service for your organisation's need. If could be a regular deliver job that you will like to appoint it to a single courier over the next 12 months.";
  $lang['available_tab_info'] = "These are existing services that are put up by the couriers which member can request to add it into their organisation as a pre-approved service for their organisation to use.";
  $lang['pre_bidders_tab_info'] = "These are couriers or freight operators that are pre-approved by the organisation to join in a bidding delivery job or service request. Pre-approved bidders will receive email notifications whenever there is a new bidding job or service request. Organisation Admin can also allow their member to open their jobs/service requests for open bidding by any couriers / freight operators.";

  $lang['scheme_detail_title'] = "Scheme Details";
  $lang['scheme_detail_id'] = "Scheme ID";
  $lang['scheme_detail_name'] = "Scheme Name";
  $lang['scheme_detail_type'] = "Scheme Type";
  $lang['scheme_detail_percentage_rate'] = "Percentage Rate";
  $lang['scheme_detail_fixed_rate'] = "Fixed Rate";
  $lang['scheme_detail_start_date'] = "Start Date";
  $lang['scheme_detail_expiry_date'] = "Expiry Date";
  $lang['scheme_detail_description'] = "Description";
  $lang['scheme_detail_status'] = "Status";
  $lang['scheme_detail_edit'] = "Edit Scheme";
  $lang['add_new_scheme'] = "Add New Scheme";
  $lang['new_scheme'] = "New Scheme";

  $lang['lead_detail_title'] = "Lead Details";
  $lang['lead_detail_ref_no'] = "Ref No";
  $lang['lead_detail_name'] = "Lead Name";
  $lang['lead_detail_note'] = "Note";
  $lang['lead_detail_status'] = "Status";
  $lang['lead_detail_org'] = "Organization";
  $lang['lead_detail_company_name'] = "Company Name";
  $lang['lead_detail_contact_name'] = "Contact Name";
  $lang['lead_detail_country'] = "Country";
  $lang['lead_detail_country_select'] = "select";
  $lang['lead_detail_address'] = "Address";
  $lang['lead_detail_job_title'] = "Job Title";
  $lang['lead_detail_products'] = "Product/Services interested";
  $lang['lead_detail_commission'] = "Commission Awarded";
  $lang['lead_detail_follow_up'] = "Follow Up By";
  $lang['lead_detail_engagement'] = "Engagement Date";
  $lang['lead_detail_remarks'] = "Remarks";
  $lang['lead_detail_looks_similar'] = "Looks Similar To";
  $lang['lead_detail_edit'] = "Edit Lead";
  $lang['add_new_lead'] = "Add New Lead";
  $lang['new_lead'] = "New Lead";

  $lang['activity_title'] = "Activities";
  $lang['activity_id'] = "Activity id";
  $lang['activity_action'] = "Action";
  $lang['activity_detail_title'] = "Activity Details";
  $lang['activity_detail_date'] = "Date";
  $lang['activity_detail_group'] = "Team";
  $lang['activity_detail_remark'] = "Description";
  $lang['activity_detail_updated'] = "Added/Updated by";


  $lang['group_title'] = "Teams";
  $lang['group_id'] = "Team id";
  $lang['group_name_ph'] = "name";
  $lang['group_description_ph'] = "description (optional)";
  $lang['group_code_ph'] = "code";
  $lang['group_action'] = "Action";
  $lang['group_detail_title'] = "Team Details";
  $lang['group_detail_name'] = "Name";
  $lang['group_detail_description'] = "Description";
  $lang['group_detail_code'] = "Code";
  $lang['group_detail_status'] = "Status";
  $lang['group_detail_edit'] = "Edit Team Details";
  $lang['group_detail_updated'] = "Added/Updated by";
  $lang['add_new_group'] = "Add New Team";
  $lang['new_group'] = "New Team";
  $lang['add_group'] = "Add Team";

  $lang['service_title'] = "Services";
  $lang['service_status'] = "Service Status";
  $lang['service_id'] = "Service ID";
  $lang['service_name_ph'] = "name";
  $lang['service_id_ph'] = "universal ID";
  $lang['service_price_ph'] = "price";
  $lang['service_start_time_ph'] = "start time";
  $lang['service_end_time_ph'] = "end time";
  $lang['service_start_time'] = "Start Time";
  $lang['service_end_time'] = "End Time";
  $lang['origin'] = "Origin";
  $lang['destination'] = "Destination";
  $lang['service_price'] = "Expected Service Price";
  $lang['available_days'] = "Service Operational Days";
  $lang['service_name'] = "Service Name";
  $lang['service_type'] = "Type";
  $lang['service_payment'] = "Payment Term";
  $lang['service_payment_title'] = "Accepted Payment Type";
  $lang['service_payment_ph'] = "Payment terms";
  $lang['service_description_ph'] = "description (optional)";
  $lang['service_universal_id_ph'] = "Universal ID";
  $lang['service_action'] = "Action";
  $lang['service_detail_title'] = "Service Details";
  $lang['service_members'] = "Members";
  $lang['service_groups'] = "Teams";
  $lang['service_detail_name'] = "Name";
  $lang['service_detail_courier'] = "Courier";
  $lang['service_detail_org_name'] = "Organisation";
  $lang['service_detail_description'] = "Description";
  $lang['service_working_days'] = "Working Days";
  $lang['service_detail_price'] = "Price";
  $lang['service_detail_threshold'] = "Threshold Price";
  $lang['service_detail_public'] = "Public List";
  $lang['service_detail_auto_approve'] = "Auto Approval";
  $lang['service_detail_universal_id'] = "Universal ID";
  $lang['service_detail_service_id'] = "Service ID";
  $lang['service_detail_status'] = "Status";
  $lang['service_detail_edit'] = "Edit Service Details";
  $lang['service_detail_updated'] = "Added/Updated by";
  $lang['add_new_service'] = "Add New Service";
  $lang['edit_service'] = "Edit Service";
  $lang['is_public'] = "Is a public service ?";
  $lang['is_auto_approve'] = "Enable auto approval?";
  $lang['new_service'] = "New Service";
  $lang['order_collection_signature'] = "Collection contact's signature/stamp/date";
  $lang['order_delivery_signature'] = "Delivery contact's signature/stamp/date";
  $lang['order_courier_signature'] = "Courier's signature/stamp/date";
  $lang['terms_title'] = "6connect terms &amp; conditions";
  $lang['terms'] = "Please ensure that you verified the details of this delivery order before you hand the item(s) to the delivery man who will come to collect from you. By handing your item to the courier assigned, you have agreed to 6Connect's service terms and conditions. Details can be found at https://www.6connect.biz/terms";
  $lang['threshold_update_suc'] = "Threshold price updated successfully";
  $lang['use_public_service_info'] = "Allow members to use public services";
  $lang['allow_use_public_confirm'] = "Are you sure you want to allow members to use public service?";
  $lang['not_allow_use_public_confirm'] = "Are you sure you want to disallow members to use public service?";
  $lang['allow_use_public_suc'] = "Allowed members to use public service successfully.";
  $lang['not_allow_use_public_suc'] = "Disallowed members to use public service successfully.";
//errors

  $lang['name_error'] = "Organisation name must be given";
  $lang['shortname_error'] = "Organisation shortname must be given";
  $lang['website_error'] = "Website address is invalid";

  $lang['pre_approved_bidder_email_subject'] = 'Invitation for Pre-Approved Courier';
  $lang['pre_approved_bidder_email'] = "You have been invited as a pre-approved courier by organisation - %s on 6Connect, "
          . "if you haven't registered with us yet, please kindly register here with this email address: <br />%s <br />"
          . "otherwise please login to 6Connect with your courier account. <br />%s";
  /*
    |--------------------------------------------------------------------------
    | Linked Accounts
    |--------------------------------------------------------------------------
   */
  $lang['linked_page_name'] = 'Linked Accounts';
  $lang['linked_page_satement'] = 'Any accounts you link via this section, will only be used for faster sign ins in the future. We are not capturing any private information about your other accounts via this linking. The details you share with us are all visible under your Account Settings.';
  $lang['linked_no_linked_accounts'] = 'You have no linked accounts.';
  $lang['linked_currently_linked_accounts'] = 'Currently linked accounts';
  $lang['linked_link_with_your_account_from'] = 'Link with your account from';
  $lang['linked_remove'] = 'Remove';

  $lang['linked_linked_with_this_account'] = 'The %s account you are connecting to is already linked with this account.';
  $lang['linked_linked_with_another_account'] = 'The %s account you are connecting to has already been linked with another account.';
  $lang['linked_linked_with_your_account'] = 'Your %s account has been linked with this account.';
  $lang['linked_linked_account_deleted'] = 'Your linked account has been deleted.';
  $lang['linked_link_with_your_x_account'] = 'Link with your %s account';
  /*
    |--------------------------------------------------------------------------
    | Password
    |--------------------------------------------------------------------------
   */
  $lang['password_page_name'] = 'Change Password';
  $lang['password_current_password'] = 'Current Password';
  $lang['password_new_password'] = 'New Password';
  $lang['password_retype_new_password'] = 'Re-type New Password';
  $lang['password_change_my_password'] = 'Change my password';
  $lang['password_current_password_incorrect'] = 'Your current password is incorrect.';
  $lang['password_forgot_your_password'] = 'Forgot your password?';
  $lang['password_password_has_been_changed'] = 'Your password has been changed.';

  $lang['password_safe_guard_your_account'] = '<p>To safe guard your account, we <strong>strongly</strong> recommend that you pick a complex password which...</p>
<ol>
	<li>is at least 8 characters long, and longer if possible</li>
	<li>contains a mix of upper and lower case letters</li>
	<li>includes numerals, special characters, and punctuation</li>
</ol>';
  /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
   */
  $lang['profile_page_name'] = 'Manage Profile';
  $lang['profile_instructions'] = 'The information you provide on this page is publicly viewable on your site profile.';
  $lang['profile_username'] = 'Username';
  $lang['profile_picture'] = 'Picture';
  $lang['profile_save'] = 'Save changes';
  $lang['profile_updated'] = 'Your profile has been updated.';
  $lang['profile_username_taken'] = 'This Username is already taken.';
  $lang['profile_username_empty'] = 'Username must be given.';
  $lang['profile_fullname_empty'] = 'Fullname must be given.';
  $lang['profile_custom_upload_picture'] = 'Custom Upload';
  $lang['profile_delete_picture'] = 'Change your image';
  $lang['profile_picture_guidelines'] = 'Maximum file size of 800 kb. JPG, GIF, PNG.';

  $lang['admin_or'] = '&nbsp;or&nbsp;';
  /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
   */
  $lang['settings_page_name'] = 'Account Settings';
  $lang['settings_privacy_statement'] = 'The information we collect on this page is private and will not be shown anywhere on this website or shared with third parties without your explicit permission. For more information, read our %s.';
  $lang['settings_privacy_policy'] = 'privacy policy';
  $lang['settings_email'] = 'Email';
  $lang['settings_fullname'] = 'Full Name';
  $lang['settings_firstname'] = 'First Name';
  $lang['settings_reg_date'] = 'Registration Date';
  $lang['settings_lastname'] = 'Last Name';
  $lang['settings_dateofbirth'] = 'Date of Birth';
  $lang['settings_phone'] = 'Phone Number';
  $lang['settings_fax'] = 'Fax Number';
  $lang['settings_language'] = 'Language';
  $lang['settings_country'] = 'Country';
  $lang['settings_description'] = 'Bio';
  $lang['settings_timezone'] = 'Timezone';
  $lang['settings_save'] = 'Save changes';
  $lang['settings_email_exist'] = 'This Email is already registered.';
  $lang['settings_email_empty_error'] = 'Email must be given';
  $lang['settings_dateofbirth_incomplete'] = 'Date of Birth selection is incomplete.';
  $lang['settings_details_updated'] = 'Your account details have been updated.';

  $lang['dateofbirth_month'] = '- Month -';
  $lang['dateofbirth_day'] = '- Day -';
  $lang['dateofbirth_year'] = '- Year -';
  $lang['month_jan'] = 'Jan';
  $lang['month_feb'] = 'Feb';
  $lang['month_mar'] = 'Mar';
  $lang['month_apr'] = 'Apr';
  $lang['month_may'] = 'May';
  $lang['month_jun'] = 'Jun';
  $lang['month_jul'] = 'Jul';
  $lang['month_aug'] = 'Aug';
  $lang['month_sep'] = 'Sep';
  $lang['month_oct'] = 'Oct';
  $lang['month_nov'] = 'Nov';
  $lang['month_dec'] = 'Dec';
  $lang['settings_select'] = '- Select -';
  $lang['gender_male'] = 'Male';
  $lang['gender_female'] = 'Female';

  /*
    |--------------------------------------------------------------------------
    | Connnect Third Party
    |--------------------------------------------------------------------------
   */
  $lang['connect_google'] = 'Google';
  $lang['connect_yahoo'] = 'Yahoo!';
  $lang['connect_aol'] = 'AOL';
  $lang['connect_openid'] = 'OpenID';
  $lang['connect_facebook'] = 'Facebook';
  $lang['connect_myspace'] = 'MySpace';
  $lang['connect_twitter'] = 'Twitter';

  $lang['connect_with_x'] = 'Connect with %s';
  $lang['connect_click_button'] = 'To proceed, click the following button.';

  $lang['connect_create_account'] = 'Create Account';
  $lang['connect_create_username'] = 'Username';
  $lang['connect_create_email'] = 'Email';
  $lang['connect_create_heading'] = 'Confirm your account information';
  $lang['connect_create_button'] = 'Complete';
  $lang['connect_create_username_taken'] = 'This Username is already taken.';
  $lang['connect_create_email_exist'] = 'This Email is already registered.';

  $lang['connect_enter_your'] = 'Enter your %s';
  $lang['connect_openid_url'] = 'OpenID URL';
  $lang['connect_start_what_is_openid'] = 'What is OpenID?';
  $lang['connect_proceed'] = 'Proceed';


  /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
   */
  $lang['forgot_password_page_name'] = 'Forgot your password?';
  $lang['forgot_password_instructions'] = 'We will send reset password instructions to the email address associated with your account.';
  $lang['forgot_password_username_email'] = "Email";
  $lang['forgot_password_send_instructions'] = 'Send instructions';
  $lang['or_sign_in'] = 'Sign In';

  $lang['forgot_password_username_email_does_not_exist'] = 'This Email does not exist.';
  $lang['forgot_password_does_not_manage_password'] = 'Sorry, we do not manage the password of your account.';
  $lang['forgot_password_recaptcha_required'] = 'The captcha test is required';
  $lang['forgot_password_recaptcha_incorrect'] = 'The captcha test is incorrect.';

  $lang['reset_password_sent_instructions'] = "<h3>Okay, we've sent the instructions to your email.<br />Go check it!</h3>
<p>You can keep this page open while you're checking your email.<br />If you don't receive the instructions within a minute or two try %s!</p>";
  $lang['reset_password_resend_the_instructions'] = 'Re-sending the instructions';
  $lang['reset_password_email_sender'] = '6Connect';
  $lang['reset_password_email_subject'] = '6Connect Password Reset';
  $lang['reset_password_email'] = "We have received a request to reset your password.<BR><BR>
+To reset your password, please click on the link below or copy and paste the URL into your browser:<BR>%s";
  /*
    |--------------------------------------------------------------------------
    | Manage Permissions
    |--------------------------------------------------------------------------
   */

  $lang['permissions_page_name'] = 'Manage Permissions';
  $lang['permissions_page_description'] = 'Place to manage your user permissions.';
  $lang['permissions_column_permission'] = 'Permission';
  $lang['permissions_column_inroles'] = 'In Roles';
  $lang['permissions_update_page_name'] = 'Update Permission';
  $lang['permissions_update_description'] = 'Update the details for this permission';
  $lang['permissions_create_page_name'] = 'Create New Permission';
  $lang['permissions_create_description'] = 'Create a new user permission';
  $lang['permissions_key'] = 'Key';
  $lang['permissions_description'] = 'Description';
  $lang['permissions_role'] = 'Roles';
  $lang['permissions_name_taken'] = 'This Name is already taken.';
  $lang['permissions_ban'] = 'Disable Permission';
  $lang['permissions_unban'] = 'Enable Permission';
  $lang['permissions_banned'] = 'Disabled';
  $lang['permissions_system_name'] = 'Unable to modify names of system permissions.';
  /*
    |--------------------------------------------------------------------------
    | Manage Roles
    |--------------------------------------------------------------------------
   */

  $lang['roles_page_name'] = 'Manage Roles';
  $lang['roles_page_description'] = 'Place to manage your user roles.';
  $lang['roles_column_role'] = 'Role';
  $lang['roles_column_users'] = 'Users';
  $lang['roles_update_page_name'] = 'Update Role';
  $lang['roles_update_description'] = 'Update the details for this role';
  $lang['roles_create_page_name'] = 'Create New Role';
  $lang['roles_create_description'] = 'Create a new role';
  $lang['roles_name'] = 'Name';
  $lang['roles_description'] = 'Description';
  $lang['roles_permission'] = 'Permissions';
  $lang['roles_name_taken'] = 'This Name is already taken.';
  $lang['roles_ban'] = 'Disable Role';
  $lang['roles_unban'] = 'Enable Role';
  $lang['roles_banned'] = 'Disabled';
  $lang['roles_system_name'] = 'Unable to modify names of system roles.';
  /*
    |--------------------------------------------------------------------------
    | Manage Users
    |--------------------------------------------------------------------------
   */

  $lang['users_page_name'] = 'Manage Users';
  $lang['users_description'] = 'Update the account details for your users or create new accounts.';
  $lang['users_update_page_name'] = 'Update User';
  $lang['users_update_description'] = 'Update the account details for this user';
  $lang['users_create_page_name'] = 'Create New User';
  $lang['users_create_description'] = 'Create a new account';
  $lang['users_username'] = 'Username';
  $lang['users_roles'] = 'Roles';
  $lang['users_ban'] = 'Ban User';
  $lang['users_unban'] = 'Unban User';
  $lang['users_admin'] = 'Admin';
  $lang['users_banned'] = 'Banned';

  /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
   */
  $lang['reset_password_page_name'] = 'Reset your password';
  $lang['reset_password_captcha'] = "For security reasons, please type out the two words below.";
  $lang['reset_password_captcha_submit'] = 'Submit';
  $lang['reset_password_recaptcha_required'] = 'The captcha test is required';
  $lang['reset_password_recaptcha_incorrect'] = 'The captcha test is incorrect.';
  $lang['reset_password_unsuccessful'] = 'Sorry, your reset password link has expired.';
  $lang['reset_password_resend'] = 'Resend password reset link?';

  /*
    |--------------------------------------------------------------------------
    | Sign In
    |--------------------------------------------------------------------------
   */
  $lang['sign_in_page_name'] = 'Sign In';
  $lang['sign_in_heading'] = 'Login to 6Connect';
  $lang['courier_sign_in_heading'] = 'Login for Service Providers';
  $lang['sign_in_third_party_heading'] = 'Sign in with your account from';
  $lang['sign_in_with'] = 'Sign In with %s';

  $lang['sign_in_username_email'] = 'Email';
  $lang['sign_in_password'] = 'Password';
  $lang['sign_in_forgot_your_password'] = 'Forgot your password?';
  $lang['sign_in_sign_in'] = 'Sign In';
  $lang['sign_in_remember_me'] = 'Remember me';
  $lang['sign_in_dont_have_account'] = "Don't have an account? %s";
  $lang['sign_in_sign_up_now'] = 'Sign up now';
  $lang['privacy_policy'] = "I agree to the 6Connect %s and %s";
  $lang['terms_link'] = "Terms of Service";
  $lang['privacy_link'] = "Privacy Policy";

  $lang['sign_in_username_email_does_not_exist'] = 'This Email does not exist.';
  $lang['sign_in_recaptcha_required'] = 'The captcha test is required';
  $lang['sign_in_recaptcha_incorrect'] = 'The captcha test is incorrect.';
  $lang['sign_in_combination_incorrect'] = 'Email and Password combination mismatch.';

  $lang['password_password_has_been_changed'] = 'Your password has been changed.';
  /*
    |--------------------------------------------------------------------------
    | Signout
    |--------------------------------------------------------------------------
   */
  $lang['sign_out_successful'] = 'Okay, you have been signed out.';
  $lang['sign_out_go_to_home'] = 'Go to home';
  /*
    |--------------------------------------------------------------------------
    | Sign Up
    |--------------------------------------------------------------------------
   */
  $lang['sign_up_page_name'] = 'Sign Up';
  $lang['sign_up_heading'] = 'Create your 6Connect Account';
  $lang['sign_up_third_party_heading'] = 'Sign up with your account from';
  $lang['sign_up_with'] = 'Sign Up with %s';

  $lang['sign_up_username'] = 'Username';
  $lang['sign_up_password'] = 'Password';
  $lang['sign_up_email'] = 'Email';
  $lang['sign_up_create_my_account'] = 'Create my account';
  $lang['sign_up_sign_in_now'] = 'Sign in now';

  $lang['sign_up_already_have_account'] = 'Already have an account?';
  $lang['sign_up_recaptcha_required'] = 'The captcha test is required';
  $lang['sign_up_recaptcha_incorrect'] = 'The captcha test is incorrect.';
  $lang['sign_up_username_taken'] = 'This Username is already taken.';
  $lang['sign_up_email_exist'] = 'This Email is already registered.';
  $lang['sign_up_forgot_your_password'] = 'Forgot your password?';
  $lang['sign_up_confirm_password'] = "Confirm password";
  $lang['sign_up_companyname'] = "Company Name";
  $lang['sign_up_notice'] = 'NOTICE:';
  $lang['sign_up_registration_disabled'] = 'New account registrations are currently disabled.';


  $lang['courier_sign_up_heading'] = 'Create your 6Connect Courier Account';
  $lang['courier_sign_up_email'] = 'Email';
  $lang['sign_up_confirm_password_label'] = "confirm password";
  $lang['not_email_confirmed'] = "Your email is not yet verified. Please confirm your email.";
  $lang['not_account_approved'] = "Your account is not yet approved.";
  $lang['courier_email_confirm'] = "Email Confirmed";
  $lang['courier_email_confirm_msg'] = "Well done, your 6Connect account is now activated! You are ready to start engaging the couriers on our platform, and meet your business delivery needs now.";
  $lang['courier_email_already_confirm'] = "Email Already Confirmed";
  $lang['courier_email_already_confirm_msg'] = "The email address %s is already verified to your 6connect account.";
  $lang['courier_email_confirm_error'] = "Could Not Confirm Email";
  $lang['courier_email_confirm_error_msg'] = "We�re sorry, but here was a problem adding your email address to your 6connect account. Please try selecting the entire link and copy & paste it into your browser's address bar.";

  $lang['courier_overview_tab'] = "Overview";
  $lang['bidding_badge'] = 'Tender';
  $lang['profile_company_name'] = "Company Name";

  $lang['settings_reg_no'] = "Register Number";
  $lang['settings_support_email'] = "Support Email";
  $lang['settings_address'] = "Registered Address";
  $lang['settings_billing_address'] = "Billing Address";
  $lang['settings_url'] = "URL";
  $lang['insured_amount'] = "Insured Amount";
  $lang['insured_policy'] = "Insurance Policy";
  $lang['compliance_rating'] = "Compliance Rating";

  $lang['profile_logo_guidelines'] = 'Maximum file size of 800 kb. JPG, PNG.';
  $lang['profile_logo'] = 'Company logo';
  $lang['couriers_logo_info'] = "Providing us with a logo of your company.";

  /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
   */
  $lang['user_usage_report'] = "User Usage Report";
  $lang['group_usage_report'] = "Team Usage Report";
  $lang['overall_usage_report'] = "Overall Usage Report";
  $lang['user_performance'] = "User Performance";
  $lang['group_performance'] = "Team Performance";
  $lang['overall_performance'] = "Overall Performance";
  $lang['day_trend'] = "Order Placement Day Trend";
  $lang['week_trend'] = "Order Placement Week Trend";
  $lang['services_breakdown'] = "Services Breakdown";
  $lang['groups_breakdown'] = "Teams Breakdown";
  $lang['users_breakdown'] = "Users Breakdown";
  $lang['export_pdf'] = "Export as PDF";
  $lang['export_excel'] = "Export as Excel";
  $lang['export_transaction'] = "Export Transactions";
  $lang['export_transaction_title'] = "Export Transactions";

  /* -----------------------------------------------------------
   * Partner
   * -----------------------------------------------------------
   */
  $lang['partner'] = "Partner";
  $lang['partner_id'] = "ID";
  $lang['partner_name'] = "Partner Name";
  $lang['partner_url'] = "Partner URL";
  $lang['partner_user'] = "Partner User";
  $lang['assign_puser'] = "Assign User";
  $lang['change_puser'] = "Change User";
  $lang['partner_url_info'] = "This is the authorized URL that will be embedding the 6Connect portal.";
  $lang['partner_shortname'] = "Shortname";
  $lang['partner_color'] = "Color scheme";
  $lang['partner_color_info'] = "Upload your own css file for your theme";
  $lang['partner_color_info_edit'] = "Upload your own css file for your theme <br> (old file will be overwritten by new one only if you save changes)";
  $lang['partner_domain'] = "Domain";
  $lang['new_partner'] = 'New Partner';
  $lang['create_new_partner'] = "Create New Partner";
  $lang['assign_partner_user_suc'] = "Partner user assigned successfully";
  $lang['assign_partner_user_err'] = "This user has been assigned to another partner";

  /* -------------------------------------------------------------
   * Couriers
   * ------------------------------------------------------------
   */

  $lang['couriers'] = "Couriers";
  $lang['courier_id'] = "Courier ID";
  $lang['courier_email'] = "Email";
  $lang['courier_company_name'] = "Company Name";
  $lang['courier_access_key'] = "Access Key";
  $lang['courier_url'] = "URL";
  $lang['description'] = "Description";
  $lang['courier_email_verified'] = "Email Verified";
  $lang['courier_account_approved'] = "Approved";
  $lang['courier_approved'] = "Courier account is approved.";
  $lang['approve_confirm'] = "Approve this courier?";
  $lang['organisation_pre_services'] = "Pre-approved services";

  $lang['courier_details_tab'] = "Details";
  $lang['courier_orders_tab'] = "Deliveries";
  $lang['courier_services_tab'] = "Services";

  $lang['tenders'] = "Delivery Requests";
  $lang['bid_popup_title'] = "Bid this job?";
  $lang['bid_remark_ph'] = "any delivery details to let user know";
  $lang['withdraw_bid'] = "Are you sure you want to withdraw this bid?";

  /*
   * ----------------------------------------------------------
   * Email notification
   * ----------------------------------------------------------
   */
  $lang['email_notifications'] = "Notifications";
  $lang['notification_settings_title'] = "Update the type of email notifications that you wish to receive.";
  $lang['n_assign_order'] = "A new delivery request has been assigned to you";
  $lang['n_add_response'] = "A comment has been submitted for a bid that you are involved.";
  $lang['n_bid_won'] = "A bid is awarded to you.";
  $lang['n_cancel_order'] = "A delivery order that was assigned / awarded to you has been cancelled by member";

  $lang['n_bid_received'] = "When you receive new bids for their order.";
  $lang['n_service_bid'] = "When you received new bids for your service request.";
  $lang['n_status_update'] = "When your delivery is collected, delivered or failed delivery happened.";
  $lang['n_comment_from_courier'] = "When bidder/courier wrote a comment for a delivery job.";

  $lang['direct_assign_email_title'] = "A new delivery request has been assigned to you by 6connect user (%s).";
  // $lang['direct_assign_email_content'] = "You have been assigned a delivery by the customer %s";
  $lang['direct_assign_email_content'] = "You have received a new delivery order from %s (%s).";
  $lang['direct_assign_email_link_title'] = "Click here to view the delivery detail.";
  $lang['direct_assign_email_multipleorder_titile'] = "You have received %d deliveries from the customer %s";

  $lang['order_cancel_request_email_title'] = "Request for cancel order";
  $lang['order_cancel_request_email_content'] = "Customer has been requested to cancel the order.";
  $lang['order_cancel_request_email_link_title'] = "View order details";

  $lang['order_cancel_accept_email_title'] = "Request for cancel order accepted";
  $lang['order_cancel_accept_email_content'] = "Your request to cancel the order is accepted by courier.";
  $lang['order_cancel_accept_email_link_title'] = "View order details";

  $lang['new_comment_email_title'] = "New Comment";
  $lang['new_comment_email_content'] = "New message from customer";
  $lang['new_comment_email_link_title'] = "View order details";

  $lang['comment_response_email_title'] = "Comment Response";
  $lang['comment_response_email_content'] = "Comment responded by user";
  $lang['comment_response_email_link_title'] = "View order details";

  $lang['comment_email_title'] = "Comment";
  $lang['comment_email_content'] = "Commented responded by customer";
  $lang['comment_link_title'] = "View details";

  $lang['bid_won_email_title'] = "You won bid for 6connect order";
  $lang['bid_won_email_content'] = "You won bid for job";
  $lang['bid_won_email_link_title'] = "View order details";

  $lang['bid_won_service_email_title'] = "You won bid for 6connect service";
  $lang['bid_won_service_email_content'] = "You won bid for the service '%s'";
  $lang['bid_won_service_email_link_title'] = "View Service";

  $lang['cancel_order_email_title'] = "Order cancelled by ";
  $lang['cancel_order_email_content'] = "A delivery order that was assigned/awarded to you has been cancelled by ";
  $lang['cancel_order_email_link_title'] = "View order details";

  $lang['change_order_email_title'] = "Order changed by user";
  $lang['change_order_email_content'] = "The delivery request has been changed, your delivery request bidding has been withdrawn, if you are still interested in this request.<BR><BR>Please click on the link below or copy and paste the URL into your browser to see the service request details";
  $lang['change_order_email_link_title'] = "View order details";

  $lang['courier_comment_email_title'] = "New comment added by courier to your job";
  $lang['courier_comment_email_content'] = "New comment added by courier to your job";
  $lang['courier_comment_email_link_title'] = "View order details";

  $lang['status_update_email_title'] = "Status Update";
  $lang['status_update_email_content'] = "Your order status is updated to '%s'";
  $lang['status_update_email_link_title'] = "View order details";

  $lang['new_bid_email_title'] = "New Bid";
  $lang['new_bid_email_content'] = "New bid for your job";
  $lang['new_bid_email_link_title'] = "View order details";

  $lang['service_bid_email_title'] = "New Bid for Service";
  $lang['service_bid_email_content'] = "New bid is added for your service request";
  $lang['service_bid_email_link_title'] = "View bidding";

  $lang['threshold_email_title'] = "New Bid for Service";
  $lang['threshold_email_content'] = " Price from the assigned courier exceed the threshold price set for the service";
  $lang['threshold_email_link_title'] = "View order details";

  $lang['accept_email_title'] = "Order accepted";
  $lang['accept_email_content'] = "Your order has been accepted by the courier. ";
  $lang['accept_email_link_title'] = "View order details";

  $lang['closed_bid_email_title'] = "New job";
  $lang['closed_bid_email_content'] = " New job from the organisation in which you are a pre-defined courier. Its a closed bid.";
  $lang['closed_bid_email_link_title'] = "View order details";

  $lang['open_bid_email_title'] = "New job";
  $lang['open_bid_email_content'] = " New job from the 6Connect user. Its an open bid.";
  $lang['open_bid_email_link_title'] = "View order details";

  /*
   * ----------------------------------------------------------
   * My Contacts
   * ----------------------------------------------------------
   */

  $lang['contact_name'] = "Contact name";
  $lang['contact_email'] = "Email";
  $lang['contact_phone'] = "Phone number";
  $lang['contact_address'] = "Address";
  $lang['contact_address_line1'] = "Block No., Unit No., Floor No.";
  $lang['contact_address_line2'] = "Road Name, Building Name, City/Town etc.";
  $lang['contact_company_name'] = "Company name";
  $lang['contact_dept_name'] = "Department name";
  $lang['contact_postal_code'] = "Postal code";
  $lang['contact_country'] = "Country";
  $lang['create_new_contact'] = "Save new contact";
  $lang['new_contact'] = "New Contact";
  $lang['new_contact_saved'] = "New contact saved successfully";
  $lang['contact_updated'] = "Contact details updated successfully";
  $lang['contact_deleted'] = "Contact deleted successfully";
  $lang['contact_share_info'] = "Share contact with others";

  $lang['select_from_mycontacts'] = "Select from Address Book";
  $lang['replace_from_mycontacts'] = "Replace from Address Book";
  $lang['save_contact_to_my_contact'] = "Save this contact to Address Book";
  $lang['conatclist_subhead'] = "Contacts";
  $lang['recent_conatclist_subhead'] = "Recent Contacts";

  /*
   * --------------------------------------------------------------------
   * Service Requests
   * --------------------------------------------------------------------
   */

  $lang['create_new_srequest'] = "Service Tender Request";
  $lang['new_srequest'] = "New Request";
  $lang['srequest_title'] = "Name";
  $lang['srequest_org'] = "Requesting Organisation";
  $lang['srequest_duration'] = "Expected Service Duration";
  $lang['srequest_type'] = "Service Type";
  $lang['srequest_price'] = "Price Range";
  $lang['srequest_status'] = "Status";
  $lang['srequest_payment'] = "Payment Term(s)";
  $lang['srequest_delpermonth'] = "Expected Deliveries per Month";
  $lang['srequest_compensation'] = "Other Conditions";
  $lang['srequest_expiry'] = "Expiry Date";
  $lang['srequest_description'] = "Delivery Details";
  $lang['new_service_tender'] = "New Service Tender";

  $lang['srequest_title_ph'] = "Give a name to this request";
  $lang['srequest_duration_ph'] = "Expected service duration";
  $lang['srequest_type_ph'] = "Service Type";
  $lang['srequest_price_ph'] = "Price Range";
  $lang['srequest_payment_ph'] = "Payment term(s)";
  $lang['srequest_payment_info'] = "Indicate your preferred payment terms for the service e.g. 14-days credit term";
  $lang['srequest_delpermonth_ph'] = "Expected deliveries per month";
  $lang['srequest_delpermonth_sub'] = "deliveries per month";
  $lang['months'] = "month(s)";
  $lang['srequest_description_ph_sub'] = "failure to give a good understanding of the things you are expecting to deliver may attract the wrong types of couriers.";
  $lang['srequest_delpermonth_info'] = "Indicate the estimated number of deliveries to be made every month during the service period";
  $lang['srequest_duration_info'] = "Indicate how log will you be expecting to use this service for. Usually its 12 months.";
  $lang['srequest_compensation_ph'] = "Other conditions e.g. insurance coverage for delivery etc.";
  $lang['srequest_description_title'] = "Tell us more about goods/items to be delivering";
  $lang['srequest_description_ph'] = "Provide the prospecting couriers more about the type of deliveries that will be engaging e.g. small parcels delivery, deliveries that requires cold storage, groceries items etc.";

  $lang['srequest_errors'] = "Please clear the errors.";
  $lang['srequest_save_success'] = "Request submitted.";
  $lang['srequest_added_on'] = "Added Date";
  $lang['service_bidders_courier'] = "Courier";
  $lang['service_bidders_service'] = "Service";
  $lang['service_bidders_price'] = "Price";
  $lang['service_bidders_time'] = "Service Time";
  $lang['service_bidders_type'] = "Service Type";
  $lang['service_bidders_remarks'] = "Description";
  $lang['service_accept'] = "Accept";
  $lang['service_accepted'] = "Accepted";
  $lang['srequest_uploads'] = "Uploaded files";

  /*
   * -----------------------------------------------------
   * Available services
   * -----------------------------------------------------
   */
  $lang['a_s_name'] = "Service";
  $lang['a_s_courier'] = "Courier";
  $lang['a_s_description'] = "Description";
  $lang['a_s_type'] = "Type";
  $lang['a_s_price'] = "Price";
  $lang['a_s_org'] = "Pre-approved";
  $lang['a_s_cutoff'] = "Cut-off Time";
  $lang['a_s_days'] = "Available Days";
  $lang['a_s_request_success'] = "Request has been sent";
  $lang['a_s_request_failed'] = "No longer available for request";
  $lang['a_s_request_mail_title'] = "New Service Request";
  $lang['a_s_request_mail_content'] = "New request from 6connect user for pre-approved service";
  $lang['request_service_confirm'] = "Are you sure you want to request this service?<br> If courier accept your request this service will be added to your organisation as a pre-approved service.";
  $lang['use_service_confirm'] = "Are you sure you want to use this service with this organisation?";
  $lang['a_s_no_org_info'] = "We noticed that you have not created or join an organisation yet. To use this service, you had to be a member of at least one organisation.";
  $lang['proceed_info'] = "As your company / organisation has not pre-approve this service, do you still want to continue using it?";


  $lang['service_approve_confirm'] = "Are you sure you want to approve this organisation?";
  $lang['service_reject_confirm'] = "Are you sure you want to reject this organisation?";
  $lang['a_s_request_accepted_suc'] = "Request accepted successfully";
  $lang['a_s_request_rejected_suc'] = "Request rejected successfully";
  $lang['req_org'] = "Organisation";
  $lang['no_of_req'] = "No. of Requests";
  $lang['req_stat'] = "Status";

  /*
   * -----------------------------------------------------
   * Pre approved bidders
   * -----------------------------------------------------
   */
  $lang['p_a_b_courier_name'] = "Courier";
  $lang['p_a_b_email'] = "Email";
  $lang['p_a_b_description'] = "Description";
  $lang['p_a_b_url'] = "URL";
  $lang['p_a_b_status'] = "Status";
  $lang['allow_colsed_bidding'] = "Allow members to do open bid. (Note: You will need at least 2 pre-approved couriers to enable closed bidding)";
  $lang['open_bid_confirm'] = "Are u sure you want to allow members to do open bid?";
  $lang['closed_bid_confirm'] = "Are u sure you want to make it closed bidding?";
  $lang['open_bid_updated_suc'] = "Open bid settings updated successfully";
  $lang['courier_removed_suc'] = "Pre-approved courier removed successfully";
  $lang['remove_bidder_warning'] = "Are you sure you want to remove this pre-approved courier?";
  $lang['new_pre_bidder'] = "Invite";
  $lang['invite_pre_bidder'] = "Invite Courier";
  $lang['add_new_pre_bidder'] = "Invite New Courier";
  $lang['new_pre_courier_added'] = "New courier is invited to pre-approved couriers";
  $lang['unknown_courier'] = 'Unknown courier';

  /*
   * -----------------------------------------------------
   * Organisation API
   * -----------------------------------------------------
   */
  $lang['o_api_info'] = "Enable API to push your new delivery request into the system via URL post method.";
  $lang['o_api_info_sub'] = "Organisation can use the 6connect Delivery APIs to add delivery orders into 6connect system programmatically.";
  $lang['reset_btn'] = "Reset";
  $lang['access_key'] = "Access Key";
  $lang['base_url'] = "Base URL";
  $lang['disable_api_confirm'] = "Disable API to push your new delivery request?";
  $lang['enable_api_confirm'] = "Enable API to push your new delivery request?";

  $lang['disable_suc'] = "Disabled API to push your new delivery request";
  $lang['enable_suc'] = "Enabled API to push your new delivery request";
  $lang['reset_access_key'] = "Are you sure you want to reset access key of this organisation?";
  $lang['reset_hash_suc'] = "Access Key has been reset successfully";

  /*
   * ----------------------------------------------------
   * Tips
   * ----------------------------------------------------
   */
  $lang['tip_id'] = "ID";
  $lang['tip_content'] = "Content";
  $lang['new_tip'] = "New Tip of the Day";
  $lang['create_new_tip'] = "Add New Tip of the Day";
  $lang['update_new_tip'] = "Update Tip of the Day";
  $lang['new_tip_added_suc'] = "New tip of the day added successfully";
  $lang['update_tip_added_suc'] = "Tip of the day updated successfully";
  $lang['tip_delete_suc'] = "Tip of the day deleted successfully";
  $lang['tip_content_error'] = "Content must be given";

  /*
   * ----------------------------------------------------
   * Bid Service Requests
   * ----------------------------------------------------
   */
  $lang['service_requests'] = "Service Requests";
  $lang['service_tenders'] = "Service Tenders";
  $lang['s_r_date'] = "Created Date";
  $lang['s_r_name'] = "Name";
  $lang['s_r_delpermonth'] = "Deliveries per Month";
  $lang['s_r_payment'] = "Payment Term(s)";
  $lang['s_r_conditions'] = "Other conditions";
  $lang['s_r_duration'] = "Duration";
  $lang['s_r_type'] = "Service type";
  $lang['s_r_price'] = "Price Range";
  $lang['bid_request_popup_title'] = "Bid this request?";
  $lang['service_request_edit'] = 'WARNING: Are you sure you want to edit this request, it will withdraw all the existing biddings. <br />Do you want to continue ?';
  $lang['service_request_cancel'] = 'WARNING: Are you sure you want to cancel this request, it will withdraw all the existing biddings. <br />Do you want to continue ?';
  $lang['cancel_request_success'] = 'Serive request cancelled successfully.';
  $lang['service_request_withdrawn_email_subject'] = '6Connect service request bidding has been withdrawn';
  $lang['service_request_withdrawn_email'] = "The service request (%s) has been changed, your service request bidding has been withdrawn, if you are still interested in this request.<BR><BR>Please click on the link below or copy and paste the URL into your browser to see the service request details";
  $lang['service_request_withdrawn_email_link_title'] = 'View request';
  $lang['service_request_email'] = "New service request (%s) has been added if you are interested in this request, please click on the link below or copy and paste the URL into your browser to see the service request details";
  $lang['service_request_cancel_email'] = "The service request has been cancelled, your service request bidding has been withdrawn<BR><BR>Please click on the link below or copy and paste the URL into your browser to see the service request details";
  /*
   * ----------------------------------------------------
   * public tracking
   * ----------------------------------------------------
   */
  $lang['public_tracking'] = "Public Tracking";
  $lang['enable_tracking_info'] = "Enable public tracking for allowing non-member to track delivery status";
  $lang['enable_tracking_info_sub'] = "Allow anyone with public access to be able to access a public page to check the status of the delivery using the public tracking number for this organisation.";

  $lang['disable_tracking_confirm'] = "Disable public tracking to track delivery status?";
  $lang['enable_tracking_confirm'] = "Enable public tracking to track delivery status?";
  $lang['disable_tracking_suc'] = "Disabled public tracking to track delivery status";
  $lang['enable_tracking_suc'] = "Enabled public tracking to track delivery status";
  $lang['tracking_title'] = "Tracking #";
  $lang['tracking_info'] = "You may provide up to 25 tracking numbers on one go, sepereated by comma.";
  $lang['tracking_url'] = "Tracking URL";
  $lang['tracking_intro'] = "Tracking Page Description";
  $lang['tracking_logo'] = "Tracking Page Logo";


  $lang['init_new_org'] = "Setup your first organisation";
  $lang['init_new_org_info'] = "Setup your corporate account and manage the deliveries for your staffs, using a single dashboard and consolidate the billing.";

  $lang['init_new_delivery'] = "Create your first delivery";
  $lang['init_new_delivery_info'] = "Have something to deliver now? Create your first delivery order now.";

  $lang['init_contacts'] = "Add your contacts";
  $lang['init_contacts_info'] = "You can avoid keying in the contacts for every new order by adding your favorite delivery and collection contacts into the address book.";

  $lang['init_delivery_tender'] = "Start your first <strong>Delivery Tender</strong>";
  $lang['init_delivery_tender_info'] = "Have a delivery job but not sure which service to use? Use the delivery tender to get quotation from multiple couriers.";

  $lang['init_service_tender'] = "Start your first <strong>Service Tender</strong>";
  $lang['init_service_tender_info'] = "Do you have regular delivery jobs or have to do bulk deliveries? Use the service tender to get service quotations from multiple couriers. Once you awarded, you and your organisation members can use it to fulfill the deliveries.";

  $lang['init_team'] = "Create your first <strong>Team</strong>";
  $lang['init_team_info'] = "Team help to organize the members with similar functions into the same group i.e. departments or divisions. Admin can assign services to selected teams.";

  $lang['init_service'] = "Add New Pre-approved Service";
  $lang['init_service_info'] = "Pre-approved services are authorised for use by your members/teams. To add a pre-approved service, you can click &quot;Request&quot; from Service Directory <strong>OR</strong> you can open a Service Request Tender for couriers to bid for it.";

  $lang['init_couriers'] = "Invite Couriers";
  $lang['init_couriers_info'] = "Add a list of qualified couriers, or delivery operators to your organisation. Qualified couriers will be able to bid for Delivery Tenders and Service Tenders.";

  $lang['init_track'] = "No items found.";
  $lang['init_track_info'] = "Key in the tracking number(s) in the input box above to get the status.";
  /*
   * ----------------------------------------------------
   * partner users
   * ----------------------------------------------------
   */
  $lang['p_partner'] = "Partner";
  $lang['p_users'] = "Users";
  $lang['p_orders'] = "Orders";
  $lang['p_org_orders'] = "Organisations";
  $lang['p_export'] = "Export";
  $lang['p_user_email'] = "Email";
  $lang['p_user_fullname'] = "Fullname";
  $lang['p_user_bio'] = "Bio";
  $lang['p_user_phone'] = "Phone";
  $lang['p_user_fax'] = "Fax";
  $lang['p_user_country'] = "Country";
  $lang['export_csv_title'] = "Export To CSV";
  $lang['export_csv'] = "Generate CSV";
  /*
   * ----------------------------------------------------
   * Get started
   * ----------------------------------------------------
   */
  $lang['welcome'] = "Welcome to 6Connect";
  $lang['delivery_simple'] = "At 6connect, we believe in making delivery simple and easy to manage.";
  $lang['make_a_delivery'] = "Make a Delivery";
  $lang['tips_get_started'] = "Tips to get you started";
  $lang['tips_get_started_info'] = "Below are some useful tips to help you deliver your goods and documents efficiently";
  $lang['setup_org'] = "Setup Organisation";
  $lang['setup_org_desc'] = "If you are doing deliveries for your organisation, setup an organisation to provide accountability and control over the deliveries.";
  $lang['pre-service_tip'] = "Pre-approved services from your trusted courier";
  $lang['pre-service_tip_desc'] = "If you are using a regular courier service provider, you can add their services as pre-approved services for your company to use.";
  $lang['get_quote_tip'] = "Get a Quotation";
  $lang['get_quote_tip_desc'] = "Besides using existing wide list of services, you can also get quotations from our courier services providers.";
  $lang['p_tips_get_started_info'] = "Here are some tips to help you get started.";
  $lang['p_setup_org'] = "Setup Your Organisation";
  $lang['p_setup_org_desc'] = "To begin, put in your company details to ease the quotation process. You can also break down by departments to help track expenditures.";
  $lang['p_pre-service_tip'] = "Choose pre-approved services ";
  $lang['p_pre-service_tip_desc'] = "If you have a service provider you use regularly, you can add them as a pre-approved service so it’s easier to select.";
  $lang['p_get_quote_tip'] = "Get a quotation for any service";
  $lang['p_get_quote_tip_desc'] = "Besides the pre-defined list of services, you can also get quotations for other kinds of services from our courier service providers";
  $lang['benefits'] = "<b>Benefits</b> for using organisation";
  $lang['benefits1'] = "Track delivery usages by individuals and by departments";
  $lang['benefits2'] = "Limit staffs / departments to use pre approved services";
  $lang['benefits3'] = "Pay your logistics / dispatch venders with detailed usage report";
  $lang['benefits4'] = "Source quotes from pre-selected couriers";
  $lang['need_assist'] = "Need Assistance?";
  $lang['support_email_title'] = "Support Email";
  $lang['support_email'] = "support@6connect.biz";
  $lang['hotline'] = "Hotline";

  /*
   * ----------------------------------------------------
   * Surcharge Items
   * ----------------------------------------------------
   */
  $lang['surcharge_items'] = "Surcharge Items";
  $lang['s_item_name'] = "Item Name";
  $lang['s_item_price'] = "Unit Price";
  $lang['s_item_remarks'] = "Remarks";
  $lang['s_item_location'] = "Location";
  $lang['add_item'] = "Add Item";

  /*
   * ----------------------------------------------------
   * Parcel type for service
   * ----------------------------------------------------
   */
  $lang['parcel_type_title'] = 'Price for Parcel Type';
  $lang['parcel_type'] = 'Parcel Type';
  $lang['parcel_type_price'] = 'Price';
  $lang['parcel_type_selection'] = "Select parcel type";
  $lang['parcel_type_price'] = 'Price';
  $lang['parcel_type_add_price'] = 'Set Price';
  $lang['parcel_type_max_cubic_volume'] = 'Max cubic volume (cm)';
  $lang['parcel_type_cubic_volume_cost'] = 'cost per cubic (cm)';
  $lang['parcel_type_max_weight'] = 'Max weight (KG)';
  $lang['parcel_type_weight_cost'] = 'cost per KG';
  $lang['parcel_type_invalid_type'] = 'invalid type';
  $lang['parcel_type_invalid_price'] = 'invalid price';
  $lang['parcel_type_invalid_volume'] = 'invalid volume';
  $lang['parcel_type_invalid_weight'] = 'invalid weight';
  $lang['parcel_type_invalid_weight_cost'] = 'invalid weight cost';
  $lang['parcel_type_invalid_volume_cost'] = 'invalid volume cost';

  /*
   * ----------------------------------------------------
   * Payment Terms
   * ----------------------------------------------------
   */

  $lang['service_payment_terms'] = "Payment Terms";
  $lang['cash_sender'] = "Cash on Collection";
  $lang['cash_recipient'] = "Cash on Delivery";
  $lang['credit_terms'] = "Credit with 6Connect";
  $lang['credit_terms_direct'] = "Credit with Courier Directly";
  $lang['service_payment_updated'] = "Service payment terms updated successfully.";

  $lang['account_name'] = "Contact Name e.g. Sean Seah";
  $lang['account_phone'] = "Contact Number e.g. 6397 5818";
  $lang['account_address_line1'] = "Address Line 1";
  $lang['account_address_line2'] = "Address Line 2";
  $lang['account_city'] = "City";
  $lang['account_country'] = "Country";
  $lang['account_postal_code'] = "Postal code";
  $lang['account_attention'] = "Invoice Attention To";
  $lang['account_reg_no'] = "Company Registration Number (Not required for individuals)";
  $lang['account_deli_per_mnth'] = "Estimated Number of Deliveries/Month (optional)";
  $lang['account_comments'] = "Additional Comments (optional)";
  $lang['new_account_saved'] = "New account saved successfully.";
  $lang['account_updated'] = "Account details updated successfully.";
  $lang['account_deleted'] = "Account deleted successfully.";
  $lang['payment_name'] = "Name";
  $lang['payment_balance'] = "Balance";
  $lang['payment_status'] = "Status";
  $lang['payment_info'] = "These payment methods will only be charged when you request for a service.";
  $lang['payment_add_suc_info'] = "Thanks for your application. Our hardworking team will review your application and get back to you as soon as possible.";

  $lang['payment_add_credit_info'] = "Thank you for your application. It is now under review by our service team. A 6Connect service officer will review it and may contact you shortly for more information.";
  $lang['create_new_account'] = "New Credit Account";
  $lang['credit_info'] = "Credit terms can add only if you have approved accounts.";
  $lang['review_credit_info'] = "Credit (Do not give cash to driver.) ";
  $lang['payment_mode'] = "Payment Mode";
  $lang['attachments'] = "Attachments";
  $lang['6con_mak_del'] = "6Connect make deliveries";
  $lang['fast_and_easy'] = '<span style="color: #00323A">fast</span> and <span style="color: #00323A">easy to manage</span>';
  $lang['make_first_delivery'] = "Make your first delivery";
  $lang['do_it_today'] = 'What would you like to do today?';
  $lang['set_up_org'] = "Set up Organisation";
  $lang['support_tools'] = "Support Tool";
  $lang['ac_payment_met'] = "Payment Method";
  $lang['organisation_user'] = 'Organisation / User';
  $lang['holder_type'] = "Holder Type";
  $lang['accept_confirm'] = "User can use this account as 6Connect credit";
  $lang['accept_pay'] = "Accept Payment?";
  $lang['status'] = "Status";
  $lang['account_type'] = "Account Type";
  $lang['customer_feedback'] = "Customer Feedback";
  $lang['collect_back'] = "<b>Collect back</b> : Require courier to collect back the document such as cheque, invoice, reciept etc.";
  $lang['collect_back_head'] = "<b>2 Ways Delivery:</b>Pick up / collect back document such as cheque, receipts etc";
  $lang['pre_delivery_chk'] = "<b>Pre-delivery Pick Up:</b> Courier to pick up document before delivery";
  $lang['post_delivery_chk'] = "<b>Post-delivery Collect back:</b> Courier to collect back document after delivery";
  $lang['cust_parcel_type'] = "Customised parcel type";
  $lang['settings_organization'] = "Organisation";
  $lang['no_matching_service_head']="No Matching Service Found";
  $lang['no_matching_service_desc']="<b>Some tips -</b> Services have cut-off time for service booking.<br> You might want to try changing the collection timing to tommorrow.";


  /* Drivers */
  $lang['driver_name'] = "Driver Name";
  $lang['driver_mobile_number'] = "Mobile No.";
  $lang['driver_email'] = "Email";
  $lang['driver_status'] = "Status";
  $lang['driver_last_active'] = "Last Active";
  $lang['driver_action'] = "Action";
  $lang['mobile_number'] = "Mobile Number";
  $lang['passcode'] = "Passcode";
  $lang['driver_identification_id'] = "Identification ID<br><small>(eg. NRIC, Work permit ID or Passport Number)</small>";
  $lang['driver_name_ph'] = "Full Name";
  $lang['passcode_ph'] = "provide a minimum 6digits passcode";
  $lang['driver_country_code_ph'] = "Country Code";
  $lang['driver_mobile_number_ph'] = "Phone Number";
  $lang['driver_email_ph'] = "sean@6connect.biz";
  $lang['driver_d_o_b_sample'] = "Driver's current age is: ";
  $lang['passcode_details'] = "Provide this passcode to your driver. It will be <br> needed for him tologin to the app.";
  /* End of file english_lang.php */
/* Location: ./application/language/english/english_lang.php */

  