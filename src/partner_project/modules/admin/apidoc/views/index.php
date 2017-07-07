<!DOCTYPE html>
<html>

     <head>
          <meta charset="utf-8">
          <title>6connect - Courier API Documentation</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta name="description" content="">
          <meta name="author" content="">
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo outer_base_url(); ?>resource/images/favicons.png" />

          <!-- Le styles -->
          <link href="<?php echo outer_base_url('REST'); ?>/css/bootstrap.css" rel="stylesheet">
          <link href="<?php echo outer_base_url('REST'); ?>/css/bootstrap-responsive.css" rel="stylesheet">
          <style>
               .desp_title{text-transform: capitalize;}
          </style>
          <script type="text/javascript" src="<?php echo outer_base_url('REST'); ?>/js/jquery-1.9.1.js"></script>

          <script type="text/javascript">
<?php
  $url = "http://" . $_SERVER['HTTP_HOST'] . str_replace("rest/", "", str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME'])) . "index.php/api/";
  $new_url = str_replace("REST/", '', $url);
?>
               var baseUrl = '<?php echo $new_url; ?>';
               var apis = {
                    'login': [
                         'company/login',
                         '{"username":"", "userpass":""}',
                         'Login API is used for login user to 6connect system. This API requires two parameters and on success it will return the access key.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>username</strong> : 6connect username (mandatory)<br>' +
                                 '<strong>userpass</strong> : 6connect password (mandatory)<br>'
                    ],
                    'adduser': [
                         'company/adduser',
                         '{"username":"", "usermail":"",  "userpass":""}',
                         'Adduser API is used for create a new user in 6connect system. This API requires three parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>username</strong> : 6connect username (mandatory)<br>' +
                                 '<strong>username</strong> : a valid  email address (mandatory)<br>' +
                                 '<strong>userpass</strong> : 6connect password (mandatory)<br>'
                    ],
                    'updateuser': [
                         'company/updateuser',
                         '{"username":"","usermail":"","userpass":""}',
                         'Update user API is used for update existing user details in 6connect system. This API requires three parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>username</strong> : 6connect username (mandatory)<br>' +
                                 '<strong>username</strong> : a valid  email address (mandatory)<br>' +
                                 '<strong>userpass</strong> : 6connect password (mandatory)<br>'
                    ],
                    'addgroup': [
                         'company/addgroup',
                         '{"groupname":"","org_id":""}',
                         'Add group API is used for create a new group in an organisation. This API requires two parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>groupname</strong> : group name (mandatory)<br>' +
                                 '<strong>org_id</strong> : Organsisation ID to which the new group will be added. (mandatory)<br>'
                    ],
                    'suspendgroup': [
                         'company/suspendgroup',
                         '{"groupname":"","groupid":""}',
                         'Suspend group API is used for suspend a group in 6connect system. This API requires two parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>groupname</strong> : group name (mandatory)<br>' +
                                 '<strong>groupid</strong> : group ID (mandatory)<br>'
                    ],
                    'assignusertogroup': [
                         'company/assignusertogroup',
                         '{"groupid":"","user_id":""}',
                         'Assign user to  group API is used for assign a user to a group in 6connect system. This API requires two parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>groupid</strong> : group ID (mandatory)<br>' +
                                 '<strong>user_id</strong> : user ID (mandatory)<br>'
                    ],
                    'assignservice': [
                         'company/assignservice',
                         '{"org_id":"","group_id":"","user_id":"","service_id":"","atype":""}',
                         'Assign service API is used for assign a current service to specific group / user in 6connect system. This API requires five parameters.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>org_id</strong> : Organsisation ID (mandatory)<br>' +
                                 '<strong>group_id</strong> : group ID if service assign to a group (mandatory)<br>' +
                                 '<strong>user_id</strong> : user ID (mandatory if atype is user)<br>' +
                                 '<strong>service_id</strong> : service ID (mandatory if atype is group)<br>' +
                                 '<strong>atype</strong> : "user" if assign to user or "group" if assign to group (mandatory)<br>'
                    ],
                    'getorders': [
                         'company/getorders',
                         '{}',
                         'Get orders API is used for list jobs that are available for bidding. It only needs the access key'
                    ],
                    'neworder': [
                         'company/neworder',
                         '{"item":"","serviceid":"","quantity":"","remarks":"","length":"","breadth":"","height":"","weight":"","caddress1":"","caddress2":"","czipcode":"","ctimezone":"","cdate":"","cdate_to":"","ccountry":"","daddress1":"","daddress2":"","dzipcode":"","dtimezone":"","ddate":"","ddate_to":"","dcountry":"","dname":"","dmail":"","dphone":"","cname":"","cmail":"","cphone":""}',
                         'New order API is used for create a new job in 6connect system.' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>item</strong> : item description (mandatory) <br>' +
                                 '<strong>serviceId</strong> : authcode (mandatory) <br>' +
                                 '<strong>quantity</strong> : service ID (mandatory) <br>' +
                                 '<strong>remarks</strong> : Quantity <br>' +
                                 '<strong>length</strong> : Remarks (mandatory) <br>' +
                                 '<strong>breadth</strong> : Length (mandatory) <br>' +
                                 '<strong>height</strong> : Breadth (mandatory) <br>' +
                                 '<strong>wieght</strong> : Height (mandatory) <br>' +
                                 '<strong>caddress1</strong> : Volumetric Weight (mandatory) <br>' +
                                 '<strong>caddress2</strong> : Collection address line 1 (mandatory) <br>' +
                                 '<strong>czipcode</strong> : Collection Address Line 2 (mandatory) <br>' +
                                 '<strong>ccountry</strong> : Collection Zip Code (mandatory) <br>' +
                                 '<strong>daddress1</strong> : Collection Country (mandatory) <br>' +
                                 '<strong>daddress2</strong> : Delivery contact address line 1 (mandatory) <br>' +
                                 '<strong>dzipcode</strong> : delivery contact address line 2 (mandatory) <br>' +
                                 '<strong>dcountry</strong> : delivery contact zip code (mandatory) <br>' +
                                 '<strong>dname</strong> : delivery contact country Id (mandatory) <br>' +
                                 '<strong>dmail</strong> : delivery contact name (mandatory) <br>' +
                                 '<strong>dphone</strong> : delivery contact mail <br>' +
                                 '<strong>cname</strong> : delivery contact phone (mandatory) <br>' +
                                 '<strong>cmail</strong> : collection contact name (mandatory) <br>' +
                                 '<strong>cphone</strong> : collection contact mail <br>'
                    ],
                    'listservices': [
                         'company/listservices',
                         '{}',
                         'List services API is used for list all services under user’s organisation.'
                    ],
                    'updateprofile': [
                         'company/updateprofile',
                         '{"fullname":"","phone_no":"","fax_no":"","country":"","description":"","language":""}',
                         'Update profile API is used for update user profile in 6connect system. This API consists of six parameters .' +
                                 '<h5>Parameters</h5>' +
                                 '<strong>fullname</strong> : Full name<br>' +
                                 '<strong>phone_no</strong> : Phone number<br>' +
                                 '<strong>fax_no</strong> : Fax number<br>' +
                                 '<strong>country</strong> : Country<br>' +
                                 '<strong>description</strong> : Description <br>' +
                                 '<strong>language</strong> : Language<br>'
                    ],
                    'changepassword': [
                         'company/changepassword',
                         '{"password":""}',
                         'Change password API is used for change the password. This API requires only the new password.' +
                                 '<h5>Parameter</h5>' +
                                 '<strong>password</strong> : New password<br>'
                    ]
               };

               $(function () {
                    $('#request-token').val('');
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
                              url: '<?php echo outer_base_url('REST'); ?>/curl.php',
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
                                   <select id="select_api" class="span2">
                                        <option value="">select API</option>
                                   </select>
                                   <input type="text" id="request-token" placeholder="Token" class="span2" value="" />
                                   <input type="text" name="request-url" id="request-url" placeholder="url" class="span4" value="" />
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

     </body>

</html>