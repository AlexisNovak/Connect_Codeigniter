<!DOCTYPE html>
<html ng-app="6connect">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Page title set in pageTitle directive -->
          <title>6Connect Rating Page</title>
          <style>
               .splash{
                    display: block!important
               }
          </style>
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo base_url(); ?>resource/images/favicons.png" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/bower_components/fontawesome/css/font-awesome.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/bower_components/bootstrap/dist/css/bootstrap.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/fonts/pe-icon-7-stroke/css/helper.css" />

          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/jquery.tagit.css">
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/tagit.ui-zendesk.css">
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/style.css">
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/responsive.css">
          <script>
               var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";
               var email = "<?= $email ?>";
               var public_id = "<?= $public_id ?>";
               var rate = "<?= $rate ?>";
          </script>
          <style>
               .logo img{
                    left:15px;
               }
               .welcome_title{
                    left:80px;
               }
               .p_footer{
                    display: block;
                    overflow: auto;
                    padding-top: 10px;
                    text-align: right;
               }
               .pwd_by{
                    font-size:14px;
                    color:#222;
                    font-weight: 600;
               }
               .pwd_connect{
                    font-size:23px;
                    font-weight: 600;
                    display: inline-block;
                    vertical-align: middle;
                    line-height: 24px;
                    cursor: pointer;
                    color:#35495E;
               }
               .pwd_connect img{
                    height:30px;
               }
               .ratings div{
                    cursor: pointer;
                    padding: 20% 30%;
                    background-color: #F0C028;
               }
               .ratings div.active{
                    background-color: #164C56;
                    color: #fff;
               }
               .content{
                    padding: 0px 40px 20px 40px;
               }
          </style>
     </head>

     <!-- Body -->
     <!-- appCtrl controller with serveral data used in theme on diferent view -->
     <body ng-controller="ratingCtrl">
          <!-- <nav class="navbar navbar-fixed-top header" style="background: #fff;">
               <div class="col-xs-12" style="padding: 8px 20px;">
                    
               </div>
          </nav> -->
          <!-- Simple splash screen-->
          <div class="splash" style="" ng-show="showsplash"> 
               <div class="color-line"></div>
               <div class="splash-title">
                    <h1>6Connect</h1>
                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="64" height="64" />
               </div> 
          </div>

          <!-- <div id="header">
               <div class="color-line">
               </div>


          </div> -->

          <!-- Main Wrapper -->

          <div id="wrapper" class="wrapper_full">
               <div class="content">
                    <div class="row" >
                         <div class="col-lg-12">
                              <div class="hpanel">
                                   <div class="panel-body">                         
                                        <div class="col-xs-8 col-xs-offset-2" ng-hide="rated">
                                             <div class="col-xs-12" style="padding:0;padding-bottom: 10px;border-bottom: 1px solid #ddd;">
                                                  <div class="col-md-4 col-sm-3 col-xs-1" style="padding:0">
                                                       <a href="<?php echo site_url() ?>">
                                                            <img src="<?= base_url('resource/home/images/bg-logo.png') ?>" style="max-height: 48px;">
                                                       </a>
                                                  </div>
                                                  <div class="col-sm-offset-4 col-md-4 col-sm-5 col-xs-offset-1 col-xs-10" style="padding:0">
                                                       <ul class="list-inline pull-right">
                                                            <li><h3><small><a href="<?php echo site_url('account/sign_in'); ?>">Go To Login</a></small></h3></li>  
                                                       </ul>
                                                  </div> 
                                             </div>  
                                             <br><br><br>
                                             <div style="color:rgb(52, 73, 94);padding-top:50px;">
                                                  <font style="font-size: 16px;">Based on the delivery service, how will you rate your experience?</div>
                                             <br><br>
                                             <div class="col-sm-12" style="background:#fafafa;padding:20px 0px 20px 0">
                                                  <div class="col-xs-10 col-xs-offset-1" style="padding:0px">
                                                       <div style="float:left"><strong>Very bad</strong></div><div  style="float:right"><strong>Excellent</strong></div>
                                                  </div>
                                                  <div style="clear:both;">
                                                       <table style="margin-top:0 !important" class="col-xs-10 col-xs-offset-1 ratings">
                                                            <tr>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===0)?'active':''" ng-click="setRate(0)"><strong>0</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===1)?'active':''" ng-click="setRate(1)"><strong>1</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===2)?'active':''" ng-click="setRate(2)"><strong>2</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===3)?'active':''" ng-click="setRate(3)"><strong>3</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===4)?'active':''" ng-click="setRate(4)"><strong>4</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===5)?'active':''" ng-click="setRate(5)"><strong>5</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===6)?'active':''" ng-click="setRate(6)"><strong>6</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===7)?'active':''" ng-click="setRate(7)"><strong>7</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===8)?'active':''" ng-click="setRate(8)"><strong>8</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 9%;"><div ng-class="(rating.value===9)?'active':''" ng-click="setRate(9)"><strong>9</strong></div></td>
                                                                 <td style="text-align: center;padding: 5px;width: 10%;"><div ng-class="(rating.value===10)?'active':''" ng-click="setRate(10)"><strong>10</strong></div></td>
                                                            </tr>
                                                       </table>
                                                  </div>
                                             </div>
                                             <br><br>
                                             <div style="clear:both;padding-top:25px;">
                                                  <div class="form-group">
                                                       <label>Why did you give this rating?</label>
                                                       <textarea class="form-control" ng-model="rating.reason" rows="5" placeholder="Why did you give this rating?"></textarea>
                                                  </div>
                                             </div>
                                             <br>
                                             <br>
                                             <hr>
                                             <p>
                                                  Submit your feedback on the link below. Rest assured that the feedback given is solely used for the purpose of improving our services and to serve you better in future.
                                             </p>
                                             <br>
                                             <center><button style="background-color:#F0C028;border: 0;color:rgb(52, 73, 94);display:inline-block;padding:0px 40px;border-radius: 5px;min-height:50px;line-height:50px;font-weight:bold;text-align:center;text-decoration:none;margin-top:20px" ng-click="submitRating()">Submit</button></center>
                                        </div>

                                        <div class="col-xs-8 col-xs-offset-2" ng-show="rated">
                                             <div class="col-xs-12" style="padding:0;padding-bottom: 10px;border-bottom: 1px solid #ddd;">
                                                  <div class="col-md-4 col-sm-3 col-xs-1" style="padding:0">
                                                       <a href="<?php echo site_url() ?>">
                                                            <img src="<?= base_url('resource/home/images/bg-logo.png') ?>" style="max-height: 48px;">
                                                       </a>
                                                  </div>
                                                  <div class="col-sm-offset-4 col-md-4 col-sm-5 col-xs-offset-1 col-xs-10" style="padding:0">
                                                       <ul class="list-inline pull-right">
                                                            <li><h3><small><a href="<?php echo site_url('account/sign_in'); ?>">Go To Login</a></small></h3></li>  
                                                       </ul>
                                                  </div> 
                                             </div>  
                                             <br><br><br><br><br>
                                             <div>
                                                  <br><br>
                                                  <center  style="color:#F0C028"><h1>Thank you!</h1></center>
                                                  <div class="col-xs-10 col-xs-offset-1 text-center">
                                                       <p>
                                                            Your feedback is very important to us.<br>Please continue to support us, and let us know if you have <br>any other suggestions to serve you better
                                                       </p>
                                                  </div>
                                                  <br>
                                                  <br>
                                                  <div style="clear:both;">
                                                       <br>
                                                       <br>
                                                       <div style="" class="col-xs-10 col-xs-offset-1 text-center">
                                                            <p style="margin:0">
                                                                 Feedback email
                                                            </p>
                                                            <p style="color:#F0C028">
                                                                 feedbacks@6connect.biz
                                                            </p>
                                                       </div>
                                                  </div>

                                                  <br>
                                                  <br>
                                                  <br>
                                                  <br>
                                                  <center><a href="<?= site_url() ?>"><button style="background-color:#F0C028;border: 0;color:rgb(52, 73, 94);display:inline-block;padding:0px 40px;border-radius: 5px;min-height:50px;line-height:50px;font-weight:bold;text-align:center;text-decoration:none;margin-top:20px">Back to Home</button></a></center>
                                             </div>

                                        </div>
                                        <div class="clearfix"></div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="hpanel footer_margin">
                    <div class="panel-body">
                         <div class="p_footer" style="text-align: left;vertical-align: baseline">
                              <span class="pwd_by" style="font-weight: 100;">Powered By</span>
                              <span class="pwd_connect">
                                   <a href="<?= site_url() ?>"><img src="<?php echo base_url(); ?>resource/images/6connect_new.png"></a>
                              </span>
                         </div>
                    </div>
                    <div class="clr"></div>
               </div>
               <div class="clr" style="height:40px;"></div>
          </div>
          <!-- build:js(.) scripts/vendor.js --> 
          <script src="<?php echo base_url(); ?>resource/bower_components/jquery/dist/jquery.min.js"></script>
          <script src="<?php echo base_url(); ?>resource/bower_components/jquery-ui/jquery-ui.min.js"></script>
          <script src="<?php echo base_url(); ?>resource/bower_components/angular/angular.min.js"></script>
          <script src="<?php echo base_url(); ?>resource/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

          <script src="<?php echo base_url(); ?>resource/scripts/tag-it.min.js"></script> 
          <script src="<?php echo base_url(); ?>resource/scripts/homer.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/public.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/directives/directives.js"></script>

     </body>
</html>