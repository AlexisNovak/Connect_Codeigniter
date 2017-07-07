<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  /*
    |--------------------------------------------------------------------------
    | File and Directory Modes
    |--------------------------------------------------------------------------
    |
    | These prefs are used when checking and setting modes when working
    | with the file system.  The defaults are fine on servers with proper
    | security, but you may wish (or even need) to change the values in
    | certain environments (Apache running a separate process for each
    | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
    | always be used to set the mode correctly.
    |
   */
  define('FILE_READ_MODE', 0644);
  define('FILE_WRITE_MODE', 0666);
  define('DIR_READ_MODE', 0755);
  define('DIR_WRITE_MODE', 0777);

  /*
    |--------------------------------------------------------------------------
    | File Stream Modes
    |--------------------------------------------------------------------------
    |
    | These modes are used when working with fopen()/popen()
    |
   */

  define('FOPEN_READ', 'rb');
  define('FOPEN_READ_WRITE', 'r+b');
  define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
  define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
  define('FOPEN_WRITE_CREATE', 'ab');
  define('FOPEN_READ_WRITE_CREATE', 'a+b');
  define('FOPEN_WRITE_CREATE_STRICT', 'xb');
  define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

  /*
   * Define root resources folder name for js/css/img files
   */
  define('RES_DIR', 'resource');

  /*
   * Detect AJAX Request for MY_Session
   */
  define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

  /*
   * Portable PHP password hashing framework
   * http://www.openwall.com/phpass/
   */
  define('PHPASS_HASH_STRENGTH', 8);
  define('PHPASS_HASH_PORTABLE', FALSE);

  define("CONSIGNMENT_PREFIX", "000000");


  define("SUCCESS_DELIVERY_STATUS", 5);
  define("FAILED_DELIVERY_STATUS", 6);
  define("CONFIRMATION_STATUS", 2);

//constants for consignment status, relatd to consignment_status table
  define('C_PENDING', 1);
  define('C_DRAFT', 7);
  define('C_REJECT', 19);
  define('C_PENDING_ACCEPTANCE', 8);
  define('C_GETTING_BID', 9);
  define('C_PRICE_APPROVAL', 10);
  define('C_COLLECTING', 101);
  define('C_COLLECTED', 102);
  define('C_IN-TRANSIT', 301);
  define('C_IN-TRANSIT_2', 311);
  define('C_IN-TRANSIT_VISA', 311);
  define('C_DELIVERED', 401);
  define('C_DELIVERED_CHEQUE', 402);
  define('C_FAILED_DELIVERY', 501);
  define('C_OTHERS', 601);
  define('C_CANCELLED', 11);

  define('C_PENDING_NAME', "Accepted");
  define('C_DRAFT_NAME', "Draft");
  define('C_PENDING_ACCEPTANCE_NAME', "Courier Informed");
  define('C_GETTING_BID_NAME', "Getting Quotes");
  define('C_PRICE_APPROVAL_NAME', "Price Approval Required");
  define('C_COLLECTING_NAME', "Collecting");
  define('C_COLLECTED_NAME', "Collected");
  define('C_IN-TRANSIT_NAME', "In-Transist");
  define('C_IN-TRANSIT_2_NAME', "In-Transit (2nd Leg)");
  define('C_IN-TRANSIT_VISA_NAME', "In-Transit (Processed)");
  define('C_DELIVERED_NAME', "Delivered");
  define('C_DELIVERED_CHEQUE_NAME', "Delivered (Cheque deposited)");
  define('C_FAILED_DELIVERY_NAME', "Failed");
  define('C_OTHERS_NAME', "Others");
  define('C_CANCELLED_NAME', 'Cancelled');

//constants for email notifications, related to notifications table
  define('N_NEW_BID_RECEIVED', 1);
  define('N_NEW_SERVICE_BID', 2);
  define('N_ORDER_STATUS_UPDATE', 3);
  define('N_COMMENT_FROM_COURIER', 4);
  define('N_DIRECT_ASSIGN', 5);
  define('N_COMMENT_RESPONSE', 6);
  define('N_BID_WON', 7);
  define('N_CANCEL_ORDER', 8);
  define('N_THRESHOLD', 9);
  define('N_CLOSED_BID', 10);
  define('N_THIRD_PARTY', 11);
  define('N_COMMENT_FROM_OWNER', 12);
  define('N_PRICE_APPROVED', 13);
  define('N_ORDER_CHANGED', 14);
  define('N_PRICE_REJECTED', 15);


  //custom order type id
  define('CUSTOM_ITEM', 0);
  define('IFRAME_FOLDER', 'partner_project');

  /* End of file constants.php */
/* Location: ./application/config/constants.php */