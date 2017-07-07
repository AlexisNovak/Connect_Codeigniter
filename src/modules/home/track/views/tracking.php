<!DOCTYPE html>
<html ng-app="6connect">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Page title set in pageTitle directive -->
          <title>6Connect Tracking Page</title>
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
               var org = "<?php // echo isset($id) ? $id : 0          ?>";

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
          </style>
     </head>

     <!-- Body -->
     <!-- appCtrl controller with serveral data used in theme on diferent view -->
     <body>
          <nav class="navbar navbar-fixed-top header">
               <div class="col-xs-12" style="padding: 8px 20px;">
                    <div class="col-md-4 col-sm-3 col-xs-1">
                         <a href="<?php echo site_url() ?>">
                              <img src="<?= base_url('resource/home/images/bg-logo.png') ?>" style="max-height: 48px;">
                         </a>
                    </div>
                    <div class="col-sm-offset-4 col-md-4 col-sm-5 col-xs-offset-1 col-xs-10">
                         <ul class="list-inline pull-right">
                              <li><h3><small><a class="active" href="<?php echo site_url(); ?>">Home</a></small></h3></li>
                              <li><h3><small><a href="<?php echo site_url('account/sign_in'); ?>">Login</a></small></h3></li>
                              <li><h3><small><a href="<?php echo site_url('contact_us'); ?>">Contact Us</a></small></h3></li>
                         </ul>
                    </div>
               </div>
          </nav>
          <!-- Simple splash screen-->
          <div class="splash"> 
               <div class="color-line"></div>
               <div class="splash-title">
                    <h1>6Connect</h1>
                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="64" height="64" />
               </div> 
          </div>

          <div id="header">
               <div class="color-line">
               </div>


          </div>

          <!-- Main Wrapper -->

          <div id="wrapper" class="wrapper_full">
               <div class="content">
                    <div class="row" ng-controller="trackCtrl">
                         <div class="col-lg-12">
                              <div class="hpanel">
                                   <div class="panel-body">                            
                                        <div class="col-xs-12 no-padding">
                                             <label style="color:#164C56;font-weight: 600;font-size: 22px;margin: 0;">Tracking ID</label>
                                             <span class="help-block" style="color: #f9c400">(<?= lang('tracking_info') ?>)</span>
                                             <button type="button" class="btn btn-logo-green" style="float: right; height: 30px;margin-left:5px;width: 100px;line-height: 18px;" ng-click="track()">Track</button>
                                             <div style="width:calc(100% - 105px)"><input id="tracking_ids" class="form-control" ng-model="tracking" ></div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="col-xs-12 no-padding" style="min-height:100px;">
                                             <div class="table-responsive" ng-show="show_list">
                                                  <p style="font-size: 14px;font-weight: 600;color:#666;margin: 0 0 -5px;">Showing {{total}} results</p>
                                                  <table id="order_list" class="table table-striped table-bordered table-responsive">
                                                       <thead>
                                                            <tr>
                                                                 <th style="width:20%;"><?= lang('order_tracking_id') ?>
                                                                 </th>
                                                                 <th style="width:30%;"><?= lang('orders_table_collection') ?>
                                                                 </th>
                                                                 <th style="width:30%;"><?= lang('orders_table_delivery') ?>
                                                                 </th>
                                                                 <th style="width:20%;"><?= lang('orders_table_status') ?>
                                                                 </th>
                                                            </tr>
                                                       </thead>
                                                       <tbody id="orderslist_body">
                                                            <tr ng-repeat="order in orderslist|orderBy:orderByField:reverseSort">
                                                                 <td>{{order.public_id}}                                                    
                                                                 </td>
                                                                 <td>
                                                                      {{order.collection_contact_name}}<br>
                                                                      {{order.collection_address}}<br>
                                                                      {{order.from_country}}<br>
                                                                      {{order.collection_contact_number}}
                                                                      <span ng-if="order.crestrict == 1" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                                 </td>
                                                                 <td>
                                                                      {{order.delivery_contact_name}}<br>
                                                                      {{order.delivery_address}}<br>
                                                                      {{order.to_country}}<br>
                                                                      {{order.delivery_contact_phone}}
                                                                      <span ng-if="order.drestrict == 1" class="row_icon" title="<?= lang('restricted_area_tooltip') ?>"><i class="fa fa-ban"></i></span>
                                                                 </td>
                                                                 <td>{{order.status}}</td>                                                                                                
                                                            </tr>                                                     
                                                            <tr class="no-data">
                                                                 <td colspan="4"><?= lang('nothing_to_display') ?></td>
                                                            </tr>
                                                       </tbody>
                                                       <tbody id="orders_loading" class="loading">
                                                            <tr>                                                  
                                                                 <td colspan="4" class="text-center">
                                                                      <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
                                                                 </td>
                                                            </tr>
                                                       </tbody>
                                                  </table>
                                             </div>
                                             <div ng-hide="show_list" class="init-wrap-sm">
                                                  <div class="init_div">
                                                       <h2><?= lang('init_track') ?></h2>
                                                       <p><?= lang('init_track_info') ?></p>
                                                  </div>
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
          <script>
                                                  $(function () {

                                                       var sampleTags = [];
                                                       $('#tracking_ids').tagit({
                                                       });

                                                       //-------------------------------
                                                       // Tag events
                                                       //-------------------------------
                                                       var eventTags = $('#eventTags');
                                                       var addEvent = function (text) {
                                                            $('#events_container').append(text + '<br>');
                                                       };
                                                       eventTags.tagit({
                                                            availableTags: sampleTags,
                                                            beforeTagAdded: function (evt, ui) {
                                                                 if (!ui.duringInitialization) {
                                                                      addEvent('beforeTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                                                                 }
                                                            },
                                                            afterTagAdded: function (evt, ui) {
                                                                 if (!ui.duringInitialization) {
                                                                      addEvent('afterTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                                                                 }
                                                            },
                                                            beforeTagRemoved: function (evt, ui) {
                                                                 addEvent('beforeTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                                                            },
                                                            afterTagRemoved: function (evt, ui) {
                                                                 addEvent('afterTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                                                            },
                                                            onTagClicked: function (evt, ui) {
                                                                 addEvent('onTagClicked: ' + eventTags.tagit('tagLabel', ui.tag));
                                                            },
                                                            onTagExists: function (evt, ui) {
                                                                 addEvent('onTagExists: ' + eventTags.tagit('tagLabel', ui.existingTag));
                                                            }
                                                       });
                                                  });
          </script>
     </body>
</html>