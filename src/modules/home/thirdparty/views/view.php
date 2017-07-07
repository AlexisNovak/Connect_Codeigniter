<!DOCTYPE html>
<html ng-app="6connect">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Page title set in pageTitle directive -->
          <title>6Connect Third Party</title>
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo base_url(); ?>resource/images/favicons.png" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/bower_components/fontawesome/css/font-awesome.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/bower_components/bootstrap/dist/css/bootstrap.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/fonts/pe-icon-7-stroke/css/helper.css" />
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/bower_components/angular-notify/dist/angular-notify.min.css" />

          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/dropzone.css">
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/style.css">
          <link rel="stylesheet" href="<?php echo base_url(); ?>resource/styles/responsive.css">
          <script>
               var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";
               var order_id = <?= isset($id) ? $id : 0 ?>;
               var ROOT_PATH = "<?= base_url() ?>";
          </script>
          <style>
               .logo img{
                    left:15px;
               }
               .welcome_title{
                    left:80px;
               }
          </style>
     </head>

     <!-- Body -->
     <!-- appCtrl controller with serveral data used in theme on diferent view -->
     <body>

          <!-- Simple splash screen-->
          <div class="splash"> 
               <div class="color-line"></div>
               <div class="splash-title">
                    <h1>6Connect</h1>
                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="64" height="64" />
               </div> </div>

          <div id="header">
               <div class="color-line">
               </div>
               <?php
               ?>  
               <div class="logo">
                    <img src="<?php echo base_url(); ?>resource/images/favicons.png">
               </div>
               <div class="welcome_title"><h3>6Connect</h3>
                    <span>Deliveries simplified for Enterprises</span>
               </div>

          </div>

          <!-- Main Wrapper -->

          <div id="wrapper" class="wrapper_full">
               <!-- Page view wrapper -->
               <div small-header class="normalheader transition small-header">
                    <div class="hpanel" tour-step order="1"  content="Place your page title and breadcrumb. Select small or large header or give the user choice to change the size." placement="bottom">
                         <div class="panel-body">
                              <a ng-click="small()" href="">
                                   <div class="clip-header">
                                        <i class="fa fa-arrow-down"></i>
                                   </div>
                              </a>                             
                              <h2 class="font-light m-b-xs">
                                   Order Details
                              </h2>
                              <small><?= isset($description) ? $description : "" ?></small>
                         </div>
                    </div>
               </div>

               <div class="content">
                    <div class="row" ng-controller="thirdpartyCtrl">
                         <div class="col-lg-12">
                              <div class="hpanel">
                                   <div class="panel-body"> 
                                        <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_accept_popup"> 
                                             <h3>Accept job?<i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>
                                             <div class="col-lg-12">
                                                  <div class="form-group" ng-show="show_price_field">
                                                       <label>Price</label>
                                                       <input type="text" ng-model="accept.price" class="form-control">
                                                       <span class="help-block m-b-none text-danger" ng-show="accept.errors.price">{{accept.errors.price}}</span>

                                                  </div>
                                                  <div class="form-group">
                                                       <label>Assigned ID</label>
                                                       <input type="text" ng-model="accept.private_id" class="form-control">
                                                       <span class="help-block m-b-none text-danger" ng-show="accept.errors.private_id">{{accept.errors.private_id}}</span>
                                                  </div>
                                             </div>
                                             <div class="btn-holder">
                                                  <span class="btn btn-info btn-sm margin_10" ng-click="accept_order()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_accept()"><?= lang('no') ?></span>
                                             </div>
                                        </div>
                                        <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_update_status_popup"> 
                                             <h3>Update Status<i class="fa fa-close pull-right" ng-click="cancel_update_status()"></i></h3>
                                             <div class="col-lg-12">
                                                  <div class="form-group">
                                                       <label>Status</label>
                                                       <select ng-model="update.status" class="form-control">
                                                            <option value="">Select status</option>
                                                            <option ng-repeat="ustat in update_statuslist" value="{{ustat}}">{{ustat.display_name}}</option>
                                                       </select> 
                                                       <span class="help-block m-b-none text-danger" ng-show="update.errors.status">{{update.errors.status}}</span>

                                                  </div>
                                                  <div class="form-group">
                                                       <label>Remarks</label>
                                                       <textarea type="text" ng-model="update.remarks" class="form-control" placeholder="any delivery details to let user know"></textarea>
                                                       <span class="help-block m-b-none text-danger" ng-show="update.errors.remarks">{{update.errors.remarks}}</span>
                                                  </div>
                                             </div>
                                             <div class="btn-holder">
                                                  <span class="btn btn-info btn-sm margin_10" ng-click="update_order()" ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                  <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_update_status()"><?= lang('no') ?></span>
                                             </div>
                                        </div>
                                        <div class="col-sm-12 no-padding margin_bottom_10" >
                                             <div class="col-sm-12 no-padding">

                                                  <div class="col-sm-6"> 
                                                       <h3 class="order_status">
                                                            <?= $order->consignment_status ?></h3>  

                                                  </div>
                                                  <div class="col-sm-6">  
                                                       <span class="pull-right btn_group overview_btn">
                                                            <?php
                                                              if ($order->is_for_bidding == 0 || $order->is_confirmed == 1) {
                                                                   ?>
                                                                   <a href="<?php echo site_url('orders/printOrder/' . $order->consignment_id); ?>" class="btn btn-primary">
                                                                        <i class="fa fa-print"></i> <?= lang('print_btn') ?>
                                                                   </a>
                                                              <?php } ?>
                                                            <?php
                                                              if ($order->private_id) {
                                                                   ?>
                                                                   <span class="btn btn-primary" ng-click="show_update_status(<?= $order->consignment_id ?>)">Update Status</span>
                                                              <?php } if (!$order->private_id) { ?>
                                                                   <span class="btn btn-primary"  ng-click="show_accept(<?= $order->consignment_id ?>, <?= $order->is_for_bidding ?>)">Accept</span>
                                                              <?php } ?>
                                                       </span>
                                                  </div>

                                             </div>
                                             <div class="clear"></div>
                                             <div class="col-sm-12">
                                                  <div class="col-sm-6 barcode_wrap text-left">
                                                       <p><?= lang('order_tracking_id') ?></p>
                                                       <p class="orderid"><?php echo $order->public_id; ?></p>
                                                       <div>
                                                            <img src="<?= base_url('filebox/barcode/consignment_document_' . $order->public_id . '.png'); ?>" alt="">
                                                       </div>
                                                  </div>
                                                  <div class="col-sm-6 barcode_wrap barcode_wrap_right text-right">
                                                       <p><?= lang('order_assigned_id') ?></p>
                                                       <?php if ($order->private_id !== ""): ?>
                                                              <p class="orderid"><?php echo $order->private_id; ?></p>
                                                              <div>
                                                                   <img src="<?= base_url('filebox/barcode/consignment_document_' . $order->private_id . '.png'); ?>" alt="">
                                                              </div>
                                                         <?php else: ?>
                                                              <p class="orderid">Not yet assign.</p>

                                                       <?php endif; ?>
                                                  </div>
                                             </div>
                                             <div class="col-sm-12" id="order_tabs">
                                                  <div class="hpanel th_tab">
                                                       <tabset>
                                                            <tab>
                                                                 <tab-heading>
                                                                      <i class="fa fa-exclamation-circle"></i> 
                                                                      <?= lang('order_detail_tab') ?>
                                                                 </tab-heading>
                                                                 <div class="panel-body no-padding"> 
                                                                      <div class="col-sm-12 no-padding margin_bottom_10" >  
                                                                           <div class="form-holder">
                                                                                <div class="form-horizontal view_order">
                                                                                     <div class="col-xs-12 no-padding">
                                                                                          <div class="col-sm-3 no-padding">
                                                                                               <?php if ($order->picture) { ?>
                                                                                                      <div class="view_img" style="cursor: pointer; background-image: url('<?= base_url('filebox/orders/' . $order->picture) . '?' . time() ?>')" ng-click="openLightboxModal('<?= base_url('filebox/orders/' . $order->picture) . '?' . time() ?>')">
                                                                                                      </div>
                                                                                                 <?php } else {
                                                                                                      ?> 
                                                                                                      <div class="view_img" style="cursor: pointer; background-image: url('<?= base_url('resource/images/parcel-placeholder.png') ?>')">

                                                                                                      </div>
                                                                                                 <?php }
                                                                                               ?>
                                                                                          </div>

                                                                                          <div class="col-sm-9 no-padding">
                                                                                               <div class="col-sm-12 ">
                                                                                                    <div class="col-sm-9 no-padding">
                                                                                                         <div class="form-group">
                                                                                                              <label><?= lang('order_item') ?></label>
                                                                                                              <div class="form-control"><?= $order->display_name ?> </div>
                                                                                                         </div>
                                                                                                    </div>
                                                                                                    <div class="col-sm-2 col-sm-offset-1 no-padding">
                                                                                                         <div class="form-group">
                                                                                                              <label><?= lang('order_quantity') ?></label>
                                                                                                              <div class="form-control"><?= $order->quantity ?></div> 
                                                                                                         </div>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <div class="col-sm-12 ">
                                                                                                    <div class="col-sm-9 no-padding">
                                                                                                         <div class="form-group">  
                                                                                                              <label><?= lang('order_remark') ?></label>
                                                                                                              <div class="form-control text-area"><?= $order->remarks ?></div>
                                                                                                         </div>
                                                                                                    </div>
                                                                                                    <div class="col-sm-2 col-sm-offset-1 no-padding">
                                                                                                         <div class="form-group">
                                                                                                              <div class="d_price">
                                                                                                                   <div class="p_label"><?= lang('delivery_price') ?></div>
                                                                                                                   <div class="amt"><?= ($order->price) ? "$" . number_format($order->price, 2) : "$ -" ?></div> 
                                                                                                              </div>
                                                                                                         </div>
                                                                                                    </div>
                                                                                               </div>  

                                                                                               <div class="col-sm-12 ">
                                                                                                    <div class="form-group">  
                                                                                                         <label><?= lang('ref_label') ?></label>
                                                                                                         <div class="form-control no-box"> <?php
                                                                                                                if ($order->ref != NULL) {
                                                                                                                     $ref = explode(',', $order->ref);
                                                                                                                     foreach ($ref as $rf) {
                                                                                                                          ?>
                                                                                                                          <span class="otag"><?= $rf ?></span>
                                                                                                                          <?php
                                                                                                                     }
                                                                                                                }
                                                                                                              ?></div>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <div class="col-sm-12 ">
                                                                                                    <div class="form-group">  
                                                                                                         <label><?= lang('tags') ?></label>
                                                                                                         <div class="form-control no-box">
                                                                                                              <?php
                                                                                                                if ($order->tags != NULL) {
                                                                                                                     $tags = explode(',', $order->tags);
                                                                                                                     foreach ($tags as $tag) {
                                                                                                                          ?>
                                                                                                                          <span class="otag"><?= $tag ?></span>
                                                                                                                          <?php
                                                                                                                     }
                                                                                                                }
                                                                                                              ?>
                                                                                                         </div>
                                                                                                    </div>
                                                                                               </div>
                                                                                          </div>
                                                                                          <?php if ($order->consignment_type_id == CUSTOM_ITEM) { ?>
                                                                                                 <div class="well col-sm-12 order_dimension ">
                                                                                                      <div class="col-sm-2 small_input">
                                                                                                           <label class="font_12"><?= lang('order_length') ?></label>
                                                                                                           <div class="form-control text-center"><?= $order->length ?></div>
                                                                                                           <label class="font_12"><?= lang('cm') ?></label> 
                                                                                                           <span class="close_icon"><i class="fa fa-close"></i></span>
                                                                                                      </div>                                                            
                                                                                                      <div class="col-sm-2 small_input">
                                                                                                           <label class="font_12"><?= lang('order_breadth') ?></label>
                                                                                                           <div class="form-control text-center"><?= $order->breadth ?></div>
                                                                                                           <label class="font_12"><?= lang('cm') ?></label>
                                                                                                           <span class="close_icon"><i class="fa fa-close"></i></span>
                                                                                                      </div>
                                                                                                      <div class="col-sm-2 small_input">
                                                                                                           <label class="font_12"><?= lang('order_height') ?></label>
                                                                                                           <div class="form-control text-center"><?= $order->height ?></div>
                                                                                                           <label class="font_12"><?= lang('cm') ?></label>
                                                                                                           <span class="close_icon"><p>=</p></span>
                                                                                                      </div>
                                                                                                      <div class="col-sm-2 small_input padding_10">
                                                                                                           <label class="font_12"><?= lang('order_volume') ?></label>
                                                                                                           <div class="form-control text-center"><?= $order->volume ?></div>
                                                                                                           <label class="font_12"><?= lang('cm3') ?></label>
                                                                                                      </div>
                                                                                                      <div class="col-sm-2 small_input padding_10">
                                                                                                           <label class="font_12"><?= lang('order_v_weight') ?></label>
                                                                                                           <div class="form-control text-center"> <?= number_format((($order->height * $order->length * $order->breadth) / 6000), 2, '.', '') ?></div>
                                                                                                           <label class="font_12"><?= lang('kg') ?></label>
                                                                                                      </div>
                                                                                                      <div class="col-sm-2 small_input padding_10">
                                                                                                           <label class="font_12"><?= lang('order_actual_weight') ?></label>
                                                                                                           <div class="form-control text-center"><?= $order->weight ?></div>
                                                                                                           <label class="font_12"><?= lang('kg') ?></label>
                                                                                                      </div>
                                                                                                 </div>
                                                                                            <?php } ?>
                                                                                          <div class="col-sm-12 no-padding" style="position: relative;" >
                                                                                               <div class="col-sm-6 no-padding">
                                                                                                    <div class="a-box a-box-left">
                                                                                                         <div class="col-sm-12 address_box">
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('collection_address') ?></h3>
                                                                                                                        <div class="form-control text-area"><?= $order->collection_address ?></div>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                              <div class="col-sm-4 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->collection_post_code ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-8 no-padding" style="padding-left: 15px !important;">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->from_country ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <p class="custom-info">
                                                                                                                   <?php
                                                                                                                     if ($order->is_c_restricted_area) {
                                                                                                                          echo lang('restricted_area_info');
                                                                                                                     }
                                                                                                                   ?>
                                                                                                              </p>
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('collect_period') ?></h3>
                                                                                                                        <div class="form-control text-area text-center timeperiod"><?php echo date("m/d/Y h:m A", strtotime($order->collection_date)) . " - " . date("m/d/Y h:m A", strtotime($order->collection_date_to)); ?></div>
                                                                                                                        <p class="date_info pull-right"><?= lang('based_on') ?> <span class="timezone"><?= $order->collection_timezone ?>(<?= $order->from_zone ?>)</span> </p>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('primary_contact') ?></h3>
                                                                                                                        <div class="form-control"><?= $order->collection_contact_name ?></div>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->collection_contact_email ? $order->collection_contact_email : lang('no_email') ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->collection_contact_number ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                         </div>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <div class="col-sm-6 no-padding">
                                                                                                    <div class="a-box a-box-right">
                                                                                                         <div class="col-sm-12 address_box">
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('delivery_address') ?></h3>
                                                                                                                        <div class="form-control text-area"><?= $order->delivery_address ?></div>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                              <div class="col-sm-4 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->delivery_post_code ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-8 no-padding" style="padding-left: 15px !important;">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->to_country ?></div>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                              <p class="custom-info">
                                                                                                                   <?php
                                                                                                                     if ($order->is_d_restricted_area) {
                                                                                                                          echo lang('restricted_area_info');
                                                                                                                     }
                                                                                                                   ?>
                                                                                                              </p>
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('delivery_period') ?></h3>
                                                                                                                        <div class="form-control text-area text-center timeperiod"><?php echo date("m/d/Y h:m A", strtotime($order->delivery_date)) . " - " . date("m/d/Y h:m A", strtotime($order->delivery_date_to)); ?></div>
                                                                                                                        <p class="date_info pull-right"><?= lang('based_on') ?> <span class="timezone"><?= $order->delivery_timezone ?> (<?= $order->to_zone ?>)</span> </p>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <h3><?= lang('primary_contact') ?></h3>
                                                                                                                        <div class="form-control"><?= $order->delivery_contact_name ?></div>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->delivery_contact_email ? $order->delivery_contact_email : lang('no_email') ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                              <div class="col-sm-12 no-padding">
                                                                                                                   <div class="form-group">
                                                                                                                        <div class="form-control"><?= $order->delivery_contact_phone ?></div>
                                                                                                                   </div>
                                                                                                              </div> 
                                                                                                         </div>
                                                                                                    </div>
                                                                                               </div>
                                                                                          </div>               
                                                                                     </div>        
                                                                                </div>
                                                                           </div>
                                                                      </div>


                                                                 </div>
                                                            </tab>

                                                            <tab>
                                                                 <tab-heading>
                                                                      <i class="fa fa-envelope-o"></i> 
                                                                      <?= lang('order_msg_tab') ?>
                                                                      <span class="label label-danger">{{(msgcount.total - msgcount.reply) || ""}}</span>
                                                                 </tab-heading>
                                                                 <div class="panel-body no-padding">

                                                                      <div class="col-lg-12 clearfix" id="msg_sec">
                                                                           <div class="col-lg-12">
                                                                                <h3>Questions for Customer</h3>
                                                                                <div class="ask_querier">
                                                                                     <textarea rows="3" class="form-control" placeholder="please write your question here..."  ng-model="comment.content"></textarea>
                                                                                     <button type="button" class="btn btn-default ask_btn" ng-click="addcomment(<?php echo $order->consignment_id; ?>)" ng-disabled="isDisabled">Ask</button>
                                                                                </div>
                                                                           </div>
                                                                           <div class="col-lg-12" id="messages">

                                                                                <?php
                                                                                  if (isset($messages) && is_array($messages)) {
                                                                                       foreach ($messages as $message) {
                                                                                            ?>
                                                                                            <div class="question">
                                                                                                 <div class="q_head">
                                                                                                      <div class="q_title"><?php
                                                                                                           if ($message->by_you == 1) {
                                                                                                                echo 'Ask by you';
                                                                                                           } else if ($message->by_you == 0) {
                                                                                                                echo 'Ask by other courier';
                                                                                                           } else {
                                                                                                                echo 'Comment by customer';
                                                                                                           }
                                                                                                           ?></div>
                                                                                                      <div class="q_time"><?php echo date('Y-m-d h:i A', strtotime($message->created_date)); ?></div>
                                                                                                 </div>
                                                                                                 <div class="q_text">
                                                                                                      <p><?= $message->question ?></p>
                                                                                                      <?php
                                                                                                      if ($message->reply) {
                                                                                                           ?>
                                                                                                           <div class="q_response">
                                                                                                                <div class="q_head">
                                                                                                                     <div class="q_title">Respond from  customer</div>
                                                                                                                     <div class="q_time"><?php echo date('Y-m-d h:i A', strtotime($message->updated_date)); ?></div>
                                                                                                                </div>
                                                                                                                <div class="q_text">
                                                                                                                     <p><?= $message->reply ?></p>

                                                                                                                </div>
                                                                                                           </div>   <?php
                                                                                                      } else if ($message->by_you != 2) {
                                                                                                           ?>
                                                                                                           <div class="q_response">
                                                                                                                <div class="q_text">
                                                                                                                     <p><strong>Customer not yet responded to your question.</strong></p>

                                                                                                                </div>
                                                                                                           </div>
                                                                                                           <?php
                                                                                                      }
                                                                                                      ?>
                                                                                                 </div>
                                                                                            </div>
                                                                                            <?php
                                                                                       }
                                                                                  }
                                                                                ?>
                                                                           </div>
                                                                      </div>

                                                                 </div>
                                                            </tab>
                                                            <tab>
                                                                 <tab-heading>
                                                                      <i class="fa fa-tasks"></i> 
                                                                      <?= lang('order_pod_tab') ?>
                                                                 </tab-heading>
                                                                 <div class="panel-body no-padding">
                                                                      <style>
                                                                           .order_img .dz-message {
                                                                                margin: 70px auto auto;
                                                                           }
                                                                      </style>
                                                                      <div class="col-xs-12" style="min-height: 400px;">    
                                                                           <div class="col-xs-12 no-padding">
                                                                                <div class="col-xs-12 col-sm-6 well">
                                                                                     <h3><?= lang('new_pod_title') ?></h3>
                                                                                     <div class="form-holder">
                                                                                          <form  class="form-horizontal col-xs-12" ng-submit="save_pod()">
                                                                                               <?php echo form_fieldset(); ?>
                                                                                               <div class="form-group">
                                                                                                    <label class="control-label"><?= lang('pod_image') ?></label>
                                                                                                    <input type="text" ng-model="pod.pod_image" id="uploaded_image" class="hidden">
                                                                                                    <div class="order_img dropzone" id="pod_img_upload" style="margin: 0;position: relative;">

                                                                                                    </div>
                                                                                                    <span class="help-block m-b-none text-danger" ng-show="pod.errors.pod_image">{{pod.errors.pod_image}}</span>

                                                                                               </div>                    
                                                                                               <div class="form-group">
                                                                                                    <label class="control-label"><?= lang('pod_remarks') ?></label>   
                                                                                                    <textarea ng-model="pod.remark" class="form-control" rows="3"></textarea>
                                                                                               </div>
                                                                                               <div class="form-group">
                                                                                                    <span style="float: left;margin:auto 10px;"> 
                                                                                                         <input ng-model="pod.signature" type="checkbox" icheck>
                                                                                                    </span><p><?= lang('pod_sign') ?></p>
                                                                                               </div>
                                                                                               <div class="form-group">
                                                                                                    <div class="text-right">
                                                                                                         <button type="submit" class="btn btn-primary"><?php echo lang('save_btn'); ?></button>
                                                                                                         <button type="button" class="btn btn-default" ng-click="cancel_pod()"><?php echo lang('cancel_btn'); ?></button>
                                                                                                    </div>
                                                                                               </div>
                                                                                               <?php echo form_fieldset_close(); ?>
                                                                                          </form>
                                                                                     </div>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                                <div class="signature_block" ng-show="signature.pod_image_url">
                                                                                     <h3>Signature</h3>
                                                                                     <div class="pod_img_wrap">
                                                                                          <img ng-src="{{signature.pod_image_url}}">

                                                                                     </div>
                                                                                </div>
                                                                           </div>
                                                                           <div class="col-xs-12" ng-show="pods.length > 0">

                                                                                <h3>Other Images</h3>

                                                                                <div class="pod_block" ng-repeat="pod in pods">
                                                                                     <div class="pod_img_wrap">
                                                                                          <img src="{{pod.pod_image_url}}">

                                                                                     </div>
                                                                                </div>                     
                                                                           </div>
                                                                      </div>

                                                                 </div>
                                                            </tab>
                                                            <tab>
                                                                 <tab-heading>
                                                                      <i class="fa fa-tasks"></i> 
                                                                      <?= lang('order_log_tab') ?>
                                                                 </tab-heading>
                                                                 <div class="panel-body no-padding">
                                                                      <div class="col-lg-12 no-padding margin_bottom_10">

                                                                           <div class="clearfix"></div>
                                                                           <div class="pull-left">
                                                                                <div  class="dataTables_length">
                                                                                     <form>
                                                                                          <label>
                                                                                               Show
                                                                                               <select class="form-control"  name="perpage" ng-model="loglistdata.perpage"  
                                                                                                       ng-options="logperpages as logperpages.label for logperpages in logperpage" ng-change="logperpagechange()">
                                                                                                    <option style="display:none" value class>15</option>
                                                                                               </select>
                                                                                               entries
                                                                                          </label>
                                                                                     </form>
                                                                                </div>
                                                                           </div>

                                                                      </div>
                                                                      <div class="clearfix"></div>
                                                                      <div class="clr"></div>
                                                                      <div class="table-responsive">
                                                                           <table id="log_list" class="table table-striped table-bordered table-responsive">
                                                                                <thead>             
                                                                                     <tr>
                                                                                          <th><?= lang('order_log_timestamp') ?></th>
                                                                                          <th><?= lang('order_log_details') ?></th>
                                                                                     </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     <tr ng-repeat="log in loglist| orderBy:orderByField_log:reverseSort_log">                   
                                                                                          <td>{{log.time}}</td>
                                                                                          <td>{{log.activity}}</td>
                                                                                     </tr>
                                                                                     <tr class="no-data">
                                                                                          <td colspan="2"><?= lang('nothing_to_display') ?></td>
                                                                                     </tr>
                                                                                </tbody>
                                                                           </table>
                                                                      </div>
                                                                      <div class="col-md-12 no-padding">
                                                                           <div class="col-md-4 no-padding">
                                                                                <div ng-show="logcount.total" style="line-height: 35px;">Showing {{logcount.start}} to {{logcount.end}} of {{logcount.total}} entries</div>
                                                                           </div> 
                                                                           <div class="col-md-8 text-right no-padding">

                                                                                <paging
                                                                                     class="small"
                                                                                     page="loglistdata.currentPage" 
                                                                                     page-size="loglistdata.perpage_value" 
                                                                                     total="loglistdata.total"
                                                                                     adjacent="{{adjacent}}"
                                                                                     dots="{{dots}}"
                                                                                     scroll-top="{{scrollTop}}" 
                                                                                     hide-if-empty="false"
                                                                                     ul-class="{{ulClass}}"
                                                                                     active-class="{{activeClass}}"
                                                                                     disabled-class="{{disabledClass}}"
                                                                                     show-prev-next="true"
                                                                                     paging-action="getLog(page)">
                                                                                </paging> 
                                                                           </div>
                                                                      </div>

                                                                 </div>
                                                            </tab>
                                                       </tabset>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="clear"></div>

                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="hpanel footer_margin">
                    <div class="panel-body">
                         <strong>
                              <small>Copyright &copy; <?php echo date('Y'); ?> <?php echo lang('website_footer'); ?></small>
                         </strong>
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
          <script src="<?php echo base_url(); ?>resource/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
          <script src="<?php echo base_url(); ?>resource/bower_components/iCheck/icheck.min.js"></script>

          <script src="<?php echo base_url(); ?>resource/bower_components/angular-notify/dist/angular-notify.min.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/homer.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/directives/directives.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/thirdparty.js"></script>
          <script src="<?php echo base_url(); ?>resource/scripts/dropzone.js"></script>
          <script>
                                                                                          var myDropzone;
                                                                                          function create_dropzone() {
                                                                                               myDropzone = new Dropzone("#pod_img_upload", {url: BASE_URL + "thirdparty/orders/upload",
                                                                                                    dictDefaultMessage: "<?= lang('pod_picture_info') ?>",
                                                                                                    addRemoveLinks: true,
                                                                                                    acceptedFiles: ".jpg,.jpeg,.png",
                                                                                                    init: function () {
                                                                                                         this.on("addedfile", function (file) {
                                                                                                              if (myDropzone.files.length > 1)
                                                                                                                   myDropzone.removeFile(myDropzone.files[0]);
                                                                                                         });
                                                                                                         this.on("removedfile", function (file) {
                                                                                                              //                             $("#uploaded_image").val("");
                                                                                                              //                                     $("#uploaded_image").trigger('input');
                                                                                                              //                                    // $.post('<?php echo site_url('thirdparty/orders/remove_photo'); ?>', {"image": file.uploaded_name});
                                                                                                         });
                                                                                                         this.on("success", function (response, result) {
                                                                                                              if (JSON.parse(result).files !== undefined) {
                                                                                                                   myDropzone.files[myDropzone.files.length - 1].uploaded_name = JSON.parse(result).files;
                                                                                                                   $("#uploaded_image").val(JSON.parse(result).files);
                                                                                                                   $("#uploaded_image").trigger('input');
                                                                                                              } else {
                                                                                                                   myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                                                                                                              }
                                                                                                         });
                                                                                                    }
                                                                                               });
                                                                                          }
                                                                                          $(document).ready(function () {
                                                                                               create_dropzone();
                                                                                          });
                                                                                          function clean_dropzone() {
                                                                                               myDropzone.destroy();
                                                                                               create_dropzone();
                                                                                          }
          </script>       
     </body>
</html>