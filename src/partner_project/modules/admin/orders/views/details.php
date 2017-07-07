<div class="col-sm-12 no-padding margin_bottom_10" >  
     <div class="form-holder">
          <form name="view_order" class="form-horizontal view_order">
               <fieldset>
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
               </fieldset>
          </form>
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
