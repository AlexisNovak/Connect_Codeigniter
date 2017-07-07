<div class="content">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
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
                           <tr><td>Working Days</td><td>{{sdetail.working_days}}</td></tr>
                           <tr><td>Type</td><td>{{sdetail.type}}</td></tr>
                           <tr><td>Status</td><td>{{sdetail.status}}</td></tr>
                      </table>
                 </div>
                 <div class="row">
                      <div class="col-sm-12">
                           <div class="hpanel">
                                <div class="panel-body">                              
                                     <div class="col-sm-12 no-padding margin_bottom_10" >                                       
                                          <div class="col-sm-12 no-padding clearfix">

                                               <div class="col-sm-6"> 
                                                    <h3 class="order_status">
                                                         <?= $order->consignment_status ?></h3> 
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
                                                         <?php if ($order->service != NULL) { ?>
                                                              <div class="col-sm-12 no-padding margin_bottom_10">
                                                                   <div class="well a-box">
                                                                        <div class="form-group">
                                                                             <h3>Assigned To <span ng-click="view_courier_info(<?= $order->courier_id ?>)" class="courier_name"><?= $order->courier_name ?></span></h3>
                                                                             <p class="srv_name"><span ng-click="view_service_info(<?= $order->service_id ?>)" class="link_color"><?= $order->service ?></span></p>
                                                                        </div>

                                                                   </div>
                                                              </div> 
                                                         <?php } ?>
                                                    </div>
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