<div class="content" ng-class="{
          'padding_0'
          :$state.current.name === 'organisation.orders.view_order'}">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
          <?php
            if (isset($order) && !empty($order)) {
                 ?>

                 <div class="row">
                      <div class="col-sm-12" ng-class="{
                                       'padding_0'
                                       :$state.current.name === 'organisation.orders.view_order'}">
                           <div class="hpanel">
                                <div class="panel-body">                              
                                     <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_accept_popup"> 
                                          <h3>
                                               <?= ($order->is_for_bidding == 1) ? "Assign a Private ID?" : "Accept Job?" ?>
                                               <i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>
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
                                               <span class="btn btn-info btn-sm margin_10" ng-click="accept_order()"  ng-class="{
                                                                     disabled:isDisabled
                                                                }"><?= lang('yes') ?></span>
                                               <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_accept()"><?= lang('no') ?></span>
                                          </div>
                                     </div>
                                     <div class="col-sm-12 no-padding margin_bottom_10" >
                                          <div class="col-sm-12 no-padding">

                                               <div class="col-sm-6"> 
                                                    <div class="order_status">
                                                         <h3><?= $order->consignment_courier_status ?></h3>  
                                                         <p id="popover_order_remark" class="order_remark" data-toggle="popover" title="Order Remark" data-placement="bottom" data-content="<?php echo $order->remarks ?>"><?php echo $order->remarks ?></p>
                                                    </div>
                                               </div>
                                               <div class="col-sm-6">  
                                                    <span class="pull-right btn_group overview_btn">
                                                         <?php if ($order->private_id !== ""): ?>
                                                              <a href="<?php echo site_url('orders/printOrder/' . $order->consignment_id); ?>" class="btn btn-primary">
                                                                   <i class="fa fa-print"></i> <?= lang('print_btn') ?>
                                                              </a>
                                                         <?php elseif ($order->is_for_bidding == 0): ?>
                                                              <span class="btn btn-primary" ng-click="show_accept(<?= $order->consignment_id ?>, <?= $order->is_for_bidding ?>,<?= ($order->service_price) ? $order->service_price : 'false' ?>)">Accept Job</span>
                                                         <?php endif; ?>   
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
                                                    <?php elseif ($order->is_for_bidding == 1): ?>
                                                         <p class="orderid">Not yet assign.</p>
                                                         <p class="orderid"><span class="btn btn-default btn-sm" ng-click="show_accept(<?= $order->consignment_id ?>, <?= $order->is_for_bidding ?>,<?= ($order->service_price) ? $order->service_price : 'false' ?>)">Assign a private ID</span></p>
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
                                                                   <?php $this->load->view('details'); ?>   
                                                              </div>
                                                         </tab>

                                                         <tab active="activetab.msg">
                                                              <tab-heading>
                                                                   <i class="fa fa-envelope-o"></i> 
                                                                   <?= lang('order_msg_tab') ?>
                                                                   <span class="label label-danger">{{(msgcount.total - msgcount.reply) || ""}}</span>
                                                              </tab-heading>
                                                              <div class="panel-body no-padding">
                                                                   <?php $this->load->view('messages'); ?>   
                                                              </div>
                                                         </tab>
                                                         <tab>
                                                              <tab-heading>
                                                                   <i class="fa fa-tasks"></i> 
                                                                   <?= lang('order_pod_tab') ?>
                                                              </tab-heading>
                                                              <div class="panel-body no-padding">
                                                                   <?php $this->load->view('order_pod'); ?>   
                                                              </div>
                                                         </tab>
                                                         <tab>
                                                              <tab-heading>
                                                                   <i class="fa fa-tasks"></i> 
                                                                   <?= lang('order_log_tab') ?>
                                                              </tab-heading>
                                                              <div class="panel-body no-padding">
                                                                   <?php $this->load->view('order_log'); ?>   
                                                              </div>
                                                         </tab>
                                                         <tab>
                                                              <tab-heading>
                                                                   <i class="fa fa-tasks"></i> 
                                                                   <?= lang('attachments') ?>
                                                              </tab-heading>
                                                              <div class="panel-body no-padding">
                                                                   <?php $this->load->view('oattach'); ?>   
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
                 <?php } else {
                      ?>
                      <p class="well text-danger"><?= lang('nothing_to_display') ?></p>
                      <?php
                 }
               ?>
          </div>
     </div>
</div>

<div class="angular_popup popup_sm pull-right warning_box" ng-show="show_accept_popup">
     <h3> 
          <span ng-show="show_price_field">Accept Job?</span>
          <span ng-show="!show_price_field">Assign a Private ID?</span>

          <i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>
     <div class="col-lg-12">
          <div class="form-group" ng-show="show_price_field">
               <label>Price</label> <input type="text"
                                           ng-model="accept.price" class="form-control"> <span
                                           class="help-block m-b-none text-danger"
                                           ng-show="accept.errors.price">{{accept.errors.price}}</span>

          </div>
          <div class="form-group">
               <label>Assigned ID</label> <input type="text"
                                                 ng-model="accept.private_id" class="form-control"> <span
                                                 class="help-block m-b-none text-danger"
                                                 ng-show="accept.errors.private_id">{{accept.errors.private_id}}</span>
          </div>
          <div class="form-group">
               <label>Remarks</label>
               <textarea type="text" ng-model="accept.remarks"
                         class="form-control"
                         placeholder="any delivery details to let user know"></textarea>
               <span class="help-block m-b-none text-danger"
                     ng-show="accept.errors.remarks">{{accept.errors.remarks}}</span>
          </div>
     </div>
     <div class="btn-holder">
          <span class="btn btn-info btn-sm margin_10"
                ng-click="accept_order()" ng-class="{
                                                                            disabled:isDisabled
                                                                       }"><?= lang('yes') ?></span>
          <span class="btn btn-primary btn-sm margin_10"
                ng-click="cancel_accept()"><?= lang('no') ?></span>
     </div>
</div>
<script>
     $(function () {
          $('#popover_order_remark').popover();
     })
</script>