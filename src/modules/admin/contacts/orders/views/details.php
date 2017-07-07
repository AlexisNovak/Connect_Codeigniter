<div class="col-xs-12 m-t-md">
     <div class="col-xs-12 col-sm-8 no-padding">
          <div class="item_details cfm_box">
               <p class="org_info text-right">
                    <?= lang('billing_org') ?> <br>
                    <span ><?= $order->org_id ? $order->org_name : lang('nan') ?></span>
               </p>
               <div class="cfm_sub_h"><?= lang('item_detail') ?></div>
               <div>
                    <div class="col-sm-12 no-padding">
                         <?php if ($order->picture) { ?>
                                <div class="view_img" style="cursor: pointer; background-image: url('<?= base_url('filebox/orders/' . $order->picture) . '?' . time() ?>')" ng-click="openLightboxModal('<?= base_url('filebox/orders/' . $order->picture) . '?' . time() ?>')">
                                </div>
                           <?php } else {
                                ?> 
                                <div class="view_img" style=" background-image: url('<?= base_url('resource/images/parcel-placeholder.png') ?>')">

                                </div>
                           <?php }
                         ?>
                    </div>
                    <div class="clearfix"></div>                  
                    <div class="col-xs-12">
                         <span class="item_name"><?= lang('ref_title') ?></span>
                         <p>
                              <?php
                                if ($order->ref != NULL) {
                                     $ref = explode(',', $order->ref);
                                     foreach ($ref as $rf) {
                                          ?>
                                          <span class="otag"><?= $rf ?></span>
                                          <?php
                                     }
                                } else {
                                     echo lang('no_ref');
                                }
                              ?>
                         </p>
                    </div>
                    <div class="col-xs-12">
                         <span class="item_name"><?= lang('tags') ?></span>
                         <p>  <?php
                                if ($order->tags != NULL) {
                                     $tags = explode(',', $order->tags);
                                     foreach ($tags as $tag) {
                                          ?>
                                          <span class="otag"><?= $tag ?></span>
                                          <?php
                                     }
                                } else {
                                     echo lang('no_tag');
                                }
                              ?>
                         </p>
                    </div>
                    <div class="col-xs-12">
                         <span class="item_name"><?= lang('remarks') ?></span>
                         <p><?= $order->remarks ? $order->remarks : lang('no_remarks') ?></p>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="col-xs-12">
                         <div class="col-xs-12 col-sm-6 no-padding">
                              <div class="cfm_sub_h2"><?= lang('collection_from_h') ?></div>
                              <p>
                                   <?= $order->collection_company_name ? $order->collection_company_name : '<span class="text-muted">' . lang('no_company') . '</span>' ?><br>
                                   <?= $order->collection_contact_name ?>, <?= $order->collection_contact_number ?><br>
                              </p>
                              <p><?= $order->collection_address ?> <?= $order->from_country ?> <?= $order->collection_post_code ?></p>
                              <p>                                                 
                         </div>
                         <div class="col-xs-12 col-sm-6 no-padding">
                              <div class="cfm_sub_h2"><?= lang('delivery_to_h') ?></div>
                              <p>
                                   <?= $order->delivery_company_name ? $order->delivery_company_name : '<span class="text-muted">' . lang('no_company') . '</span>' ?><br>
                                   <?= $order->delivery_contact_name ?>, <?= $order->delivery_contact_phone ?><br>
                              </p>
                              <p><?= $order->delivery_address ?> <?= $order->to_country ?> <?= $order->delivery_post_code ?></p>

                         </div>
                    </div>
                    <div class="col-xs-12">
                         <div class="col-xs-12 col-sm-6 no-padding">
                              <div class="cfm_sub_h2"><?= lang('collection_window') ?></div>
                              <p>
                                   <span class="date"><?php echo date("m/d/Y h:m A", strtotime($order->collection_date)) . " :: " . date("m/d/Y h:m A", strtotime($order->collection_date_to)); ?></span>


                         </div>
                         <div class="col-xs-12 col-sm-6 no-padding">
                              <div class="cfm_sub_h2"><?= lang('delivery_window') ?></div>
                              <p>  <span class="date"><?php echo date("m/d/Y h:m A", strtotime($order->delivery_date)) . " :: " . date("m/d/Y h:m A", strtotime($order->delivery_date_to)); ?></span> 


                         </div>
                    </div>
               </div>
          </div>
          <div class="item_details cfm_box">
               <div class="cfm_sub_h"><?= lang('parcels') ?></div>
               <div class="table-responsive">
                    <table class="table table-striped">
                         <tr>
                              <th>Parcel #</th>
                              <th>Parcel Type</th>
                              <!--<th>List of Items in Parcel</th>-->
                         </tr>
                         <tr>
                              <td>1</td>
                              <td><?= $order->display_name ?></td>
                              <!--<td></td>-->
                         </tr>
                    </table>
               </div>
          </div>
          <div class="item_details cfm_box">
               <div class="cfm_sub_h"><?= lang('order_pod_tab') ?></div>
               <?php $this->load->view('order_pod'); ?> 
          </div>
     </div>
     <div class="col-xs-12 col-sm-4 no-padding" >
          <div class="custom-panel">
               <div class="cpanel-title">
                    Fee
               </div>
               <div class="cpanel-body">
                    <p>Service Fee</p>
                    <p class="price_est">$<?= $order->price ? $order->price : '-' ?></p>
                    <small class="text-muted">Surcharges may applied. Courier may proceed if price is below $<?= $order->threshold_price ?>.</small>
                    <p class="m-t-sm"><strong>Pay via</strong></p>
                    <p><img src="<?= base_url() . 'resource/images/account_icon.png' ?>"> <?php
                           if ($order->payment_type == 4 || $order->payment_type == 8) {
                                echo $order->account_name;
                           }
                           if ($order->payment_type == 1 || $order->payment_type == 0)
                                echo "Cash on Collection";
                           if ($order->payment_type == 2)
                                echo "Cash on Delivery";
                         ?></p>
               </div>
          </div>
     </div>
     <script>
          var SERVICE_ASSIGNED = false;
     </script>

     <?php if ($order->is_for_bidding && $order->consignment_status_id == C_GETTING_BID) { ?>

            <div class="col-xs-12 col-sm-4 no-padding">
                 <div class="custom-panel">
                      <div class="cpanel-title">
                           Invited Couriers
                      </div>
                      <div class="cpanel-body">
                           <?php
                           if (isset($order->couriers)) {
                                foreach ($order->couriers as $courier) {
                                     ?>
                                     <p class="courier_name3"><img src="<?= base_url('resource/images/tick.png') ?>" height="24px"> <?= $courier->name ?></p>
                                     <?php
                                }
                           }
                           ?>
                      </div>
                 </div>
            </div> 
       <?php } if ((!$order->is_for_bidding || $order->is_confirmed || $order->consignment_status_id) && $order->service_id != 0) { ?>

            <script>
                 SERVICE_ASSIGNED = true;
            </script>
            <div class="col-xs-12 col-sm-4 no-padding">
                 <div class="custom-panel">
                      <div class="cpanel-title">
                           Requested Service
                      </div>
                      <div class="cpanel-body">
                           <p><span ng-click="view_service_info(<?= $order->service_id ?>)" class="link_color2"><?= $order->service ?></span></p>
                           <div class="srv_p_img">
                                <img src="<?= $order->logo ? $order->logo : base_url() . 'resource/images/default_logo.png' ?>" alt="">
                           </div>
                           <p ng-click="view_courier_info(<?= $order->courier_id ?>)" class="courier_name2"><?= $order->courier_name ?></p>
                           
                      </div>
                 </div>
            </div>  
       <?php } ?>

     <div class="col-xs-12 col-sm-4 no-padding payment_section">
          <div class="col-xs-12">
               <div class="notes m-t-md">
                    <p class="title"><?= lang('things_to_note') ?></p>
                    <p><?= lang('things_to_note_content') ?></p>
               </div>
          </div>
     </div>
</div>