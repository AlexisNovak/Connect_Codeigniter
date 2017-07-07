<!DOCTYPE html>
<html>

     <head>
          <meta charset="utf-8">
          <title>6connect - REST API</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta name="description" content="">
          <meta name="author" content="">
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="../resource/images/favicons.png" />

          <!-- Le styles -->
          <link href="css/bootstrap.css" rel="stylesheet">
          <link href="css/bootstrap-responsive.css" rel="stylesheet">

          <script type="text/javascript" src="js/jquery-1.9.1.js"></script>

          <script type="text/javascript">
<?php
  $url = "http://" . $_SERVER['HTTP_HOST'] . str_replace("rest/", "", str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME'])) . "index.php/api/";
  $new_url = str_replace("REST/", '', $url);
?>
               var baseUrl = '<?php echo $new_url; ?>';
               var apis = {
                    'adduser': ['company/adduser', '{"username":"", "usermail":"",  "userpass":""}'],
                    'updateuser': ['company/updateuser', '{"username":"","usermail":"","userpass":""}'],
                    'addgroup': ['company/addgroup', '{"groupname":"","org_id":""}'],
                    'suspendgroup': ['company/suspendgroup', '{"groupname":"","groupid":""}'],
                    'assignusertogroup': ['company/assignusertogroup', '{"groupid":"","user_id":""}'],
                    'assignservice': ['company/assignservice', '{"org_id":"","group_id":"","user_id":"","service_id":"","atype":""}'],
                    'getorders': ['company/getorders', '{}'],
                    'neworder': ['company/neworder', '{"item":"","serviceid":"","quantity":"","remarks":"","length":"","breadth":"","height":"","weight":"","caddress1":"","caddress2":"","czipcode":"","ctimezone":"","cdate":"","cdate_to":"","ccountry":"","daddress1":"","daddress2":"","dzipcode":"","dtimezone":"","ddate":"","ddate_to":"","dcountry":"","dname":"","dmail":"","dphone":"","cname":"","cmail":"","cphone":""}'],
                    'listservices': ['company/listservices', '{}'],
                    'updateprofile': ['company/updateprofile', '{"fullname":"","phone_no":"","fax_no":"","country":"","description":"","language":""}'],
                    'changepassword': ['company/changepassword', '{"password":""}'],
                    "register courier": ['courier/register', '{"email":"","name":"","password":"","url":"","description":""}'],
                    "update courier": ['courier/update', '{"company_name":"","url":"","description":"","reg_address":"","billing_address":"","fullname":"","reg_no":"","phone":"","fax":"","support_email":""}'],
                    "get profile": ['courier/getprofile', '{}'],
                    "reset access key": ['courier/resetaccesskey', '{}'],
                    "push services": ['courier/pushservices', '{"services":[{"service_id":"","company":"","display_name":"","price":"","start_time":"","end_time":"","week":"","description":""}]}'],
                    "list courier services": ['courier/listservices', '{}'],
                    "remove service": ['courier/removeservice', '{"service_id":"","company":""}'],
                    "assign service": ['courier/assignservice', '{"service_id":"","company":""}'],
                    "list jobs": ['courier/listjobs', '{"company":"","username":"","search":"","collection_address":"","delivery_address" :"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to" :"","delivery_date_from":"","delivery_date_to":"","delivery_time_from":"","delivery_time_to":""}'],
                    "get job detail": ['courier/getjobdetail', '{"job_id":""}'],
                    "bid for job": ['courier/bidforjob', '{"job_id":"","price":"","service_id":""}'],
                    "withdraw bid": ['courier/withdrawbid', '{"job_id":""}'],
                    "List history bidding jobs": ['courier/listbidhistory', '{"company":"","username":"","collection_address":"","delivery_address":"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to":"","offset":"","limit":""}'],
                    "List won jobs": ['courier/listwonjob', '{"company":"","username":"","search":"","collection_address":"","delivery_address":"","collection_date_from":"","collection_date_to":"","collection_time_from":"","collection_time_to":"","delivery_date_from":"","delivery_date_to":"","delivery_time_from":"","delivery_time_to":""}'],
                    "List job messages": ['courier/listjobmessages', '{"job_id":""}'],
                    "Leave a message": ['courier/leavejobmessages', '{"job_id":"","message":""}'],
                    "Acknowledge Job": ['courier/acknowledgejob', '{"job_id":"","consignment_id":""}'],
                    "Adjust Price": ['courier/adjustprice', '{"job_id":"","price":"","remark":""}'],
                    "List price history": ['courier/listpricehistory', '{"job_id":""}'],
                    "Submit Job track": ['courier/submitjobstate', '{"job_id":"","status_code":"","status_name":"","description":""}'],
                    "Check User exists": ['courier/checkuserexists', '{"username":""}'],
                    "Check company exists": ['courier/checkcompanyexists', '{"company_id":""}']



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
                              url: 'curl.php',
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
                         $('#select_api').append('<option value="' + api + '">' + api + '</option>')
                    });
                    $('#select_api').change(function () {
                         var api = $(this).val();
                         if (api == "") {
                              $('#request-url').val('');
                              $('#request-data').val('');
                         } else {
                              $('#request-url').val(baseUrl + apis[api][0]);
                              $('#request-data').val(apis[api][1]);
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

          </script>

     </head>

     <body>

          <!--          <div class="container-fluid">
          
                         <div class="row-fluid">
          
                              <div class="span12 well">
          
                                   <h2>REQUEST</h2>
          
                                   <div id="request-error"></div>
          
                                   <div id="request-form">
                                        <form class="form-inline" id="request_form">
                                             <select name="request-method" id="request-method" class="span2">
                                                  <option value="POST">POST</option>
                                                  <option value="GET">GET</option>
                                                  <option value="PUT">PUT</option>
                                                  <option value="DELETE">DELETE</option>
                                             </select>
                                             <select id="select_api" class="span2">
                                                  <option value="">select API</option>
                                             </select>
                                             <input type="text" id="request-token" placeholder="Token" class="span2" value="nCcaPqozQKwLZ" />
                                             <input type="text" name="request-url" id="request-url" placeholder="url" class="span4" value="" />
                                             <a href="javascript:;" class="btn span2 pull-right" id="request-send">Send</a>
                                             <textarea name="request-data" id="request-data" class="span12" style="margin-top: 10px; height: 50px;"></textarea>
                                        </form>
                                   </div>
          
                              </div>
          
                         </div>
          
                         <div class="row-fluid">
          
                              <div class="span12 well">
          
                                   <h2>RESPONSE</h2>
          
                                   <div id="response-data"></div>
          
                              </div>
          
                         </div>
          
                         <div class="row-fluid">
          
                              <div class="span12 well">
          
                                   <h2>CURL-RESPONSE</h2>
          
                                   <div id="curl-response-data"></div>
          
                              </div>
          
                         </div>
          
                    </div>-->

     </body>

</html>