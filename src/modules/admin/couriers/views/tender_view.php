<div class="content" ng-class="{'padding_0':$state.current.name === 'organisation.orders.view_order'}">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
          <?php
            if (isset($order) && !empty($order)) {
                 ?>

                 <div class="row">
                      <div class="col-sm-12" ng-class="{'padding_0':$state.current.name === 'organisation.orders.view_order'}">
                           <div class="hpanel">
                                <div class="panel-body">                              
                                     <div class="col-sm-12 no-padding margin_bottom_10" >
                                          <div class="col-sm-12 no-padding">

                                               <div class="col-sm-6"> 
                                                    <h3 class="order_status">
                                                         <?= $order->consignment_status ?></h3>  

                                               </div>
                                               <div class="col-sm-6"> 

                                               </div>

                                          </div>
                                          <div class="clear"></div>
                                          <div class="col-sm-12 barcode_wrap text-left">
                                               <div class="col-sm-8">
                                                    <p><?= lang('order_tracking_id') ?></p>
                                                    <p class="orderid"><?php echo $order->public_id; ?></p>
                                                    <div>
                                                         <img src="<?= base_url('filebox/barcode/consignment_document_' . $order->public_id . '.png'); ?>" alt="">
                                                    </div>
                                               </div>
                                               <div class="col-sm-4 text-right">
                                               		<p class="text-danger text-right"><?php echo lang('deadline_title');?>: <?php echo $order->bidding_deadline;?></p>
                                               </div>
                                          </div>
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
                                                                        <div class="form-group">  
                                                                             <label><?= lang('order_remark') ?></label>
                                                                             <div class="form-control text-area"><?= $order->remarks ?></div>
                                                                        </div>
                                                                   </div>
                                                                  
                                                                   <div class="col-sm-12 ">
                                                                        <div class="form-group">  
                                                                             <label><?= lang('ref_label') ?></label>
                                                                             <div class="form-control"> <?php
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
                                                                             <div class="form-control">
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
                                                                             </div>
                                                                        </div>
                                                                   </div>
                                                              </div>  
                                                         </div>
                                                    </div>
                                               </div>
                                     </div>

                                          <div class="col-lg-12 clearfix" id="msg_sec">
                                               <div class="col-lg-12 no-padding">
                                                    <h3>Questions for Customer</h3>
                                                    <div class="ask_querier">
                                                         <textarea rows="3" class="form-control" placeholder="please write your question here..."  ng-model="comment.content"></textarea>
                                                         <button type="button" class="btn btn-default ask_btn" ng-click="addcomment(<?php echo $order->consignment_id; ?>)">Ask</button>
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