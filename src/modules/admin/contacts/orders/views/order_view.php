<div class="content" ng-class="{'padding_0':$state.current.name === 'organisation.orders.view_order' || $state.current.name === 'tender_requests.delivery.view_order'  }">
     <div class="wrap" animate-panel>
          <?php
            if (isset($order) && !empty($order)) {
                 ?>
                 <div class="angular_popup popup_sm pull-right warning_box s_detail" ng-show="show_service_info"> 
                      <h3><?= lang('service_detail_title') ?><i class="fa fa-close pull-right" ng-click="cancel_service_info()"></i></h3>
                      <table class="table table-bordered table-striped">                    
                           <tr><td>Name</td><td>{{sdetail.name}}</td></tr> 
                           <tr><td>Description</td><td>{{sdetail.description}}</td></tr>
                           <tr><td>Price</td><td>{{sdetail.price}}</td></tr>
                           <tr><td>Start Time</td><td>{{sdetail.start_time}}</td></tr>
                           <tr><td>End Time</td><td>{{sdetail.end_time}}</td></tr> 
                           <tr><td>Origin</td><td>{{sdetail.origin}}</td></tr>
                           <tr><td>Destination</td><td>{{sdetail.destination}}</td></tr>
                           <tr><td>Payment Terms</td><td>{{sdetail.payment_term}}</td></tr>
                           <tr><td>Working Days</td><td>{{sdetail.working_days}}</td></tr>
                           <tr><td>Type</td><td>{{sdetail.type}}</td></tr>
                           <tr><td>Status</td><td>{{sdetail.status}}</td></tr>
                      </table>
                 </div>
                 <div class="row">
                      <div class="col-sm-12">
                           <div class="hpanel">
                                <div class="panel-body">   
                                     <div class="col-sm-12">
                                          <h3 class="order_title"><?= lang("delivery_request") ?>
                                               <span class="pull-right">                                             
                                                    <span ng-click="goback()" class="btn-cancel"><?= lang('cancel_btn') ?></span>

                                                    <?php if ($order->consignment_status_id == C_DRAFT) { ?>
                                                         <button type="button" class="btn btn-success" ng-click="confirmOrder()"  ng-hide="hide_pending"><?= lang('confirm_order') ?></button>
                                                    <?php } else { ?>
                                                         <!--<a class="btn btn-success <?= $order->public_id ?>" ui-sref="delivery_orders.change_request({corder_id:'<?= $order->public_id ?>'})"><?= lang('request_for_change') ?></a>-->
                                                         <?php
                                                    }
                                                    if ($order->is_for_bidding == 0 || $order->is_confirmed == 1 || $order->service != NULL) {
                                                         ?>
                                                         <a href="<?php echo site_url('orders/printOrder/' . $order->consignment_id); ?>" class="btn btn-primary">
                                                              <i class="fa fa-print"></i> <?= lang('print_btn') ?>
                                                         </a>
                                                    <?php }if ($order->is_third_party) { ?>
                                                         <div class="copy_link">
                                                              <button class="btn btn-primary" clip-copy="getTextToCopy()"  clip-click="notify_selection()" title="copy"><i class="fa fa-link"></i></button>
                                                              <span class="arrow_box" id="permalink" ng-show="show_permalink">
                                                                   <input type="text" value="<?= site_url('thirdparty/orders/view/' . $order->consignment_id . '/' . $order->permalink) ?>"  select-on-click >
                                                              </span>

                                                         </div>
                                                    <?php } ?>

                                               </span>
                                          </h3>                                     
                                          <div class="col-sm-12 no-padding margin_bottom_10 well" >
                                               <div class="col-sm-12 no-padding" ng-if="print_info">
                                                    <?php if ($order->courier_name) { ?>
                                                         <div class="important_note">
                                                              <span><?= lang('important_note') ?></span>
                                                              <br>
                                                              <?= lang('print_important_note') ?>
                                                              <br>
                                                              <?= lang('service_payment') ?> : <?php
                                                              if ($order->payment_type == 4 || $order->payment_type == 8) {
                                                                   echo lang('review_credit_info');
                                                              } else {
                                                                   if ($order->payment_type == 1)
                                                                        echo "Cash on Collection";
                                                                   if ($order->payment_type == 2)
                                                                        echo "Cash on Delivery";
                                                              }
                                                              ?>
                                                         </div>   
                                                    <?php } ?>
                                               </div>
                                               <div class="col-sm-12 clearfix m-t-sm">

                                                    <div class="col-sm-6"> 
                                                         <div class="order_status">
                                                              <h3><?= $order->consignment_status ?></h3>  
                                                              <p id="popover_order_remark" class="order_remark" data-toggle="popover" title="Order Remark" data-placement="bottom" data-content="<?php echo $order->remarks ?>"><?php echo $order->remarks ?></p>
                                                         </div>
                                                    </div>
                                                    <div class="col-sm-6 text-right">  
                                                         <span>Delivery Request No.</span>
                                                         <p class="pno"><?php echo $order->public_id; ?></p>

                                                    </div>

                                               </div>
                                               <?php if ($order->change_price !== NULL) { ?>
                                                    <div class="col-sm-12 clearfix text-right" ng-hide="hide_price_pending">
                                                         <div class="col-sm-12"> <span class="p-btn">Price Request : <?php echo "$" . number_format($order->change_price, 2) ?></span>
                                                              <button type="button" class="btn btn-success" ng-click="approvePrice()"><?= lang('approve_order') ?></button>
                                                              <button type="button" class="btn btn-danger" ng-click="rejectPrice()"><?= lang('reject_price') ?></button>
                                                         </div>
                                                    </div>                                              
                                                    <?php
                                               }
                                               ?>
                                               <div class="clear"></div>                                          
                                               <div class="col-sm-12 no-padding m-t-lg" id="order_tabs">
                                                    <div class="hpanel <?php echo ($order->is_for_bidding == 1) ? "" : "th_tab" ?>">
                                                         <tabset>
                                                              <tab>
                                                                   <tab-heading>
                                                                        <i class="fa fa-exclamation-circle"></i> 
                                                                        <?= lang('order_detail_tab') ?>
                                                                   </tab-heading>
                                                                   <?php $this->load->view('details'); ?>   

                                                              </tab>
                                                              <?php if ($order->is_for_bidding == 1): ?>                                                   
                                                                   <tab>
                                                                        <tab-heading>
                                                                             <i class="fa fa-share-alt"></i> 
                                                                             <?= lang('order_bidders_tab') ?>
                                                                             <span class="label label-danger">{{bidcount.notify|| ""}}</span>
                                                                        </tab-heading>
                                                                        <div class="panel-body">
                                                                             <?php $this->load->view('bidding'); ?>   
                                                                        </div>
                                                                   </tab>
                                                              <?php endif; ?>
                                                              <tab active="activetab.msg">
                                                                   <tab-heading>
                                                                        <i class="fa fa-envelope-o"></i> 
                                                                        <?= lang('order_msg_tab') ?>
                                                                        <span class="label label-danger" ng-if="(msgcount.total - msgcount.reply)"> {{(msgcount.total - msgcount.reply) || ""}}</span>
                                                                   </tab-heading>
                                                                   <div class="panel-body">
                                                                        <?php $this->load->view('messages'); ?>   
                                                                   </div>
                                                              </tab>
                                                              <tab>
                                                                   <tab-heading>
                                                                        <i class="fa fa-tasks"></i> 
                                                                        <?= lang('order_log_tab') ?>
                                                                   </tab-heading>
                                                                   <div class="panel-body">
                                                                        <?php $this->load->view('order_log'); ?>   
                                                                   </div>
                                                              </tab>
                                                              <tab>
                                                                   <tab-heading>
                                                                        <i class="fa fa-tasks"></i> 
                                                                        <?= lang('attachments') ?>
                                                                   </tab-heading>
                                                                   <div class="panel-body">
                                                                        <?php $this->load->view('attachments'); ?>   
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
                 <?php } else {
                      ?>
                      <p class="well text-danger"><?= lang('nothing_to_display') ?></p>
                      <?php
                 }
               ?>
          </div>
     </div>
</div>
<script>
     $(document).ready(function () {
          $('#popover_order_remark').popover();
          $('.i-checks').iCheck({
               checkboxClass: 'icheckbox_square-green',
               radioClass: 'iradio_square-green',
          });
     });
</script>