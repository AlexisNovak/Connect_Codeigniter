<!DOCTYPE html>
<html style="min-height: 100%;background: #f3f3f3;">
     <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <meta name="viewport" content="width=device-width">
          <style type="text/css">
               @media (max-width: 767px){
                    .header .td1{
                         padding-left:15px !important; 
                    }
                    .header .td2{
                         padding-right:15px !important; 
                    }
                    .td3, .td3 td{
                         width: 100% !important;
                         clear: both;
                    }
                    .td4, .td4 td{
                         width: 100% !important;
                    }
                    .table{
                         width: 90% !important;
                    }
                    .table1{
                         width: 90% !important;
                    }
               }
          </style>
     </head>
     <body style="font-family: Helvetica, Arial, sans-serif;margin:0;">
          <div style="background-color: #f3f3f3">
               <table style="background-color:#F9C400;width: 100%">
                    <tr>
                         <td>
                              <table class="header" style="padding:0px;width:80%;margin: auto">
                                   <tr>
                                        <td class="td1" style="">
                                             Powered by 6Connect Pte Ltd
                                        </td>
                                        <td class="td2" style="text-align:right;">
                                             <img style="display: inline-block;vertical-align: middle;width:35px;" src="<?= base_url('resource/images/icons/4.png') ?>" width="35">
                                             &nbsp;<a style="margin: 0;text-align: right;color: #ffffff;font-weight: bold">Tel (65) 6397 5818</a>
                                        </td>
                                   </tr>
                              </table>
                         </td>
                    </tr>

               </table>
               <table class="table" style="width:80%;margin:0 auto;background:#fff;">
                    <tr>
                         <td style="padding-left:30px;padding-right:30px">
                              <table style="width:100%">
                                   <tr>
                                        <td style="text-align:left;">
                                             <a href="https://www.6connect.biz" >
                                                  <img src="<?= base_url('resource/images/favicons_email.png') ?>" style="height:50px;min-height:40px;padding-top:15px;padding-bottom:0px;">
                                             </a>
                                        </td>
                                        <td style="text-align:right;vertical-align: bottom;padding-bottom: 10px;">
                                             <a href="https://www.6connect.biz" style="max-width: 100px;color: #666!important;text-decoration-line: none;">Learn More</a>
                                        </td>
                                   </tr>
                              </table>
                              <hr style="color: rgb(210, 206, 206); background-color: rgb(188, 186, 186); border-color: rgb(192, 190, 190);height: 1px; border: 0px none;max-width: 590px;margin:20px auto">
                         </td>
                    </tr>
                    <tr>
                         <td style="padding-left:30px;">
                              <h3 style="font-family: Helvetica, Arial, sans-serif;font-size: 28px;font-weight: normal;">Dear <?= isset($name) ? closetags($name) : "customer" ?>,</h3>
                         </td>
                    </tr>
                    <tr>
                         <td style="padding-left:30px;padding-right:30px" >
                              <?php
                                if (isset($content)) {
                                     if (is_array($content)) {
                                          foreach ($content as $value) {
                                               ?>
                                               <p style="margin: 0 0 0 10px;"><?= closetags($value); ?></p><br>
                                               <?php
                                          }
                                     } else {
                                          ?>
                                          <p style="margin: 0 0 0 10px;">
                                               <?php
                                               echo closetags($content);
                                               if (!empty($link_title)) {
                                                    ?><br>
                                                    <a href="<?= $link ?>"><?php echo closetags($link_title); ?></a>
                                                    <?php
                                               }if (isset($afterlink)) {
                                                    echo closetags($afterlink);
                                               }
                                               ?>
                                          </p>
                                          <?php
                                     }
                                }if (isset($link2)) {
                                     echo closetags($link2);
                                }
                              ?>
                         </td> 
                    </tr> 
               </table>

          </div>
          <div style="background-color: #fec600">
               <table class="table1" style="width:80%;margin:0 auto;background:#fafafa;">
                    <tr>
                         <td style="padding-left:30px;width:50%" class="td3">
                              <table style="width:100%">
                                   <tr>
                                        <td style="padding-right:65px;">
                                             <h3 style="font-family: Helvetica, Arial, sans-serif;font-size: 28px;font-weight: normal;">Why use 6Connect?</h3>
                                             <p>6Connect believes that delivery services nowadays are full of resource wastage, lack of information transparency and unjust to the real folks doing the real delivery works.</p>
                                             <p>We want to bring fairness and clarify to this industry and make deliveries as simple as sending an email.</p>
                                             <p>Jason Cheng<br>CEO, 6connect</p>	
                                        </td>
                                   </tr>
                              </table>
                         </td>
                         <td style="width:50%" class="td4">
                              <table style="width:100%;text-align: center;">
                                   <tr>
                                        <td>
                                             <img src="<?= base_url('resource/images/icons/facebook.png') ?>" style="vertical-align:middle">
                                   <center  style="padding: 10px;"><span style="padding: 10px;margin: 0px;">Follow us on Facebook Fanpage</span></center>
                         </td>
                    </tr>
               </table>
          </td>
     </tr>
</table>
</div>
</body>
</html>