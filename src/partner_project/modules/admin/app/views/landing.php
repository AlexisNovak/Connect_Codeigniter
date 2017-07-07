<div class="content" ng-if="dashboard">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">

                    <div class="hpanel">

                         <?php if ($count > 0) : ?>
                                <div class="panel-body">
                                     <div class="col-lg-12 no-padding" ng-show="orders.length > 0">
                                          <div class="col-lg-12 margin_bottom_10">
                                               <div class="col-lg-4 col-md-4 no-padding reponsive">
                                                    <a ui-sref="organisations" class="border add_new_org">
                                                         <h4>Add New Organisation</h4>
                                                         <p>Start using 6Connect by creating your first organisation. An organisation could be your company, clubs, associations or your family.</p> 
                                                    </a>

                                               </div>
                                               <div class="col-lg-4 col-md-4 no-padding reponsive">
                                                    <a ui-sref="delivery_orders.new_order" class="border single_order">
                                                         <h4>Single Order</h4>
                                                         <p>Need to make a quick delivery<br> now? Click here to create a new order immediately</p><br> 
                                                    </a>

                                               </div>
                                               <div class="col-lg-4 col-md-4 no-padding reponsive">
                                                    <a ui-sref="delivery_orders.multiple_order" class="border multiple_order">
                                                         <h4>Multiple Orders</h4>
                                                         <p>Have more than one deliveries? Click here to request delivery service from one collection<br> point to multiple delivery points</p>
                                                    </a>

                                               </div>
                                          </div>
                                          <div class="clearfix"></div>    

                                          <div class="col-lg-12">
                                               <div class="col-lg-4">
                                                    <highchart config="piechartConfig"></highchart>
                                               </div>
                                               <div class="col-lg-8">
                                                    <div class="table-responsive">
                                                         <table id="services_list" class="table table-striped table-bordered table-hover ">
                                                              <thead>
                                                                   <tr>
                                                                        <th><?= lang('order_tracking_id') ?></th>
                                                                        <th>Delivery Location</th>
                                                                        <th>Service/Courier</th>
                                                                        <th>Current status</th>
                                                                   </tr>
                                                              </thead>
                                                              <tbody>
                                                                   <tr ng-repeat="order in orders">
                                                                        <td>
                                                                             <a ui-sref="delivery_orders.view_order({order_id:order.public_id})" class="link_color"> {{order.public_id}}</a>
                                                                        </td>
                                                                        <td>
                                                                             {{order.delivery_contact_name}}<br>
                                                                             {{order.delivery_address}}<br>
                                                                             {{order.to_country}}<br>
                                                                             {{order.delivery_contact_phone}}
                                                                        </td>
                                                                        <td>{{order.service}}</td>

                                                                        <td>{{order.status}}</td>                                                 
                                                                   </tr>
                                                              </tbody>
                                                         </table>
                                                    </div>
                                               </div>
                                          </div>
                                     </div>
                                </div>
                           <?php else: ?>
                                <div class="panel-body first_order">
                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 p-lg" ng-hide="orders.length > 0">
                                          <p class="c-caption">At 6connect, we belive in making delivery simple and easy to manage.</p>

                                          <a ui-sref="delivery_orders.new_order" class="make_delivery">Make a Delivery</a>
                                     </div>                                    
                                </div>
                         <?php endif; ?>
                    </div>
               </div>               

          </div>
     </div>
</div>
<div class="content" id="getting_started" ng-if="get_start">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="col-xs-12">
                                   <span class="close_started" ng-click="close_get_start()"></span>
                                   <h1 class="welcome"><?= lang('welcome_partner') ?></h1><hr>
                              </div>
                              <div class="col-xs-12 col-sm-6">
                                   <div class=" first_order">
                                        <a ui-sref="delivery_orders.new_order" class="make_delivery"><?= lang('make_a_delivery') ?></a>

                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 tips">
                                   <p class="tip_head"><?= lang('tips_get_started') ?></p>   
                                   <p class="tip_info"><?= lang('p_tips_get_started_info') ?></p>
                                   <div class="tips_outer">
                                        <div class="tips_wrap">
                                             <div>
                                                  <img src="<?= outer_base_url('resource/images/yellow_tick.png') ?>">
                                             </div>
                                             <div>
                                                  <p class="tip_name"><?= lang('p_setup_org') ?></p>
                                                  <p class="tip_content"><?= lang('p_setup_org_desc') ?></p>
                                             </div>
                                        </div>
                                        <div class="tips_wrap">
                                             <div>
                                                  <img src="<?= outer_base_url('resource/images/yellow_tick.png') ?>">
                                             </div>
                                             <div>
                                                  <p class="tip_name"><?= lang('p_pre-service_tip') ?></p>
                                                  <p class="tip_content"><?= lang('p_pre-service_tip_desc') ?></p>
                                             </div>
                                        </div>
                                        <div class="tips_wrap">
                                             <div>
                                                  <img src="<?= outer_base_url('resource/images/yellow_tick.png') ?>">
                                             </div>
                                             <div>
                                                  <p class="tip_name"><?= lang('p_get_quote_tip') ?></p>
                                                  <p class="tip_content"><?= lang('p_get_quote_tip_desc') ?></p>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-xs-12">
                                   <hr>
                              </div>

                              <div class="col-xs-12">
                                   <p class="tip_head" style="color: #E91D75"><?= lang('need_assist') ?></p>
                                   <p>
                                        Contact us at 

                                   </p>
                                   <div>                                  
                                        <div class="contact_content"><i class="fa fa-envelope"></i> <?= lang('support_email') ?></div>
                                        <div class="contact_content"><i class="fa fa-phone"></i> (65) 6397 5818</div>
                                   </div>

                              </div>
                         </div>
                    </div>
               </div> 
          </div>
     </div>
</div>
