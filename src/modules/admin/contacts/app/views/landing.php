<div class="content" ng-if="dashboard">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">

                    <?php if ($count > 0) : ?>
                           <div class="tip_div_wrap" ng-if="tip_of_the_day" ng-show="show_tip">
                                <img src="<?= base_url('resource/images/tip.png') ?>" class="tipoftheday">
                                <p ng-bind-html="tip_of_the_day"></p>
                                <span class="tip_close" ng-click="close_tip()"></span>
                           </div>
                           <div class="clearfix"></div>
                           <div class="hpanel">
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
                           </div>
                      <?php else: ?>

                           <div class="hpanel">
                                <div class="panel-body">
                                     <div class="init_div init_div_home" style="margin: 0;">
                                          <h2 style="letter-spacing: 3px;font-weight: bold;margin: 3px auto;"><?= lang('6con_mak_del') ?> <br>
                                               <?= lang('fast_and_easy') ?>
                                          </h2>
                                          <div style="margin:0 -20px;margin-bottom: -20px">
                                               <div class="init-home-courier" style="text-align: left">
                                                    <p><?php // echo lang('init_new_delivery_info')     ?></p>
                                                    <a ui-sref="delivery_orders.new_order" class="make_delivery make_delivery1">
                                                         <b><?= lang('make_first_delivery') ?></b> &nbsp;&nbsp; <img height="45" src="<?= base_url('resource/images/right-arrow.png') ?>">
                                                    </a>
                                               </div>
                                          </div>
                                     </div>

                                     <!--                                     <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 p-lg" ng-hide="orders.length > 0">
                                                                                      <p class="c-caption"><span>At 6connect, we belive in making delivery simple and easy to manage.</span></p>
                                            
                                                                                      <a ui-sref="delivery_orders.new_order" class="make_delivery make_delivery1 make_delivery2">Make a Delivery</a>
                                                                                 </div>                                    -->
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
                         <div class="panel-body" style="padding: 0;margin-left: 20px;margin-right: 20px;">
                              <div class="get_started_img_back row" style="padding-bottom: 30px;margin-bottom: 0;">
                                   <div class="col-xs-12">
                                        <div class="col-xs-12" style="margin-top: 7px">
                                             <span class="close_started" ng-click="close_get_start()"></span>
                                        </div>
                                   </div>
                                   <div class="get_started">
                                        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 p-lg" style="padding-top: 10px !important;padding-bottom:  10px !important;" ng-hide="orders.length > 0">
                                             <p class="c-caption"><span>At 6connect, we believe in making delivery simple and easy to manage.</span></p>
                                        </div> 
                                   </div>

                                   <div class="col-xs-12 col-sm-5">
                                        <div class="text-bold">
                                             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                  <h4 class="c-caption"><?= lang('do_it_today') ?></h4>
                                             </div>     
                                             <div class="col-xs-11 col-xs-offset-1">
                                                  <div class="col-xs-12" >
                                                       <a ui-sref="delivery_orders.new_order" class="make_delivery make_delivery1  col-xs-11 text-center"><?= lang('make_a_delivery') ?></a>
                                                  </div>
                                                  <div class="col-xs-12" style="margin-top: 10px;margin-bottom: 10px;">
                                                       <a ui-sref="organisations" class="make_delivery make_delivery1 col-xs-11 text-center"><?= lang('set_up_org') ?></a>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-xs-12 col-sm-7 tips">
                                        <div class="col-xs-12 yellow_bg" style="padding-top: 0;padding-bottom: 4px;">
                                             <p class="tip_head text-uppercase text-bold"><?= lang('tips_get_started') ?></p>   
                                             <p class="tip_info small"><?= lang('tips_get_started_info') ?></p>
                                        </div>
                                        <div class="tips_outer">
                                             <div class="tips_wrap">
                                                  <div>
                                                       <img src="<?= base_url('resource/images/icons/1.png') ?>">
                                                  </div>
                                                  <div>
                                                       <p class="tip_name"><?= lang('setup_org') ?></p>
                                                       <p class="tip_content"><?= lang('setup_org_desc') ?></p>
                                                  </div>
                                             </div>
                                             <div class="tips_wrap">
                                                  <div>
                                                       <img src="<?= base_url('resource/images/icons/2.png') ?>">
                                                  </div>
                                                  <div>
                                                       <p class="tip_name"><?= lang('pre-service_tip') ?></p>
                                                       <p class="tip_content"><?= lang('pre-service_tip_desc') ?></p>
                                                  </div>
                                             </div>
                                             <div class="tips_wrap">
                                                  <div>
                                                       <img src="<?= base_url('resource/images/icons/3.png') ?>">
                                                  </div>
                                                  <div>
                                                       <p class="tip_name"><?= lang('get_quote_tip') ?></p>
                                                       <p class="tip_content"><?= lang('get_quote_tip_desc') ?></p>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="row yellow_bg">
                                   <div class="col-xs-12 col-sm-6">
                                        <p class="tip_head"><?= lang('benefits') ?></p>
                                        <div>
                                             <ul>
                                                  <li class="benf"><?= lang('benefits1') ?></li>
                                                  <li class="benf"><?= lang('benefits2') ?></li>
                                                  <li class="benf"><?= lang('benefits3') ?></li>
                                                  <li class="benf"><?= lang('benefits4') ?></li>
                                             </ul>

                                        </div>
                                   </div>
                                   <div class="col-xs-12 col-sm-6">
                                        <div class="contact">
                                             <!--<p class="tip_head"><?php // echo lang('need_assist')      ?></p>-->
                                             <div class="pull-left contactImage">
                                                  <img src="<?= base_url('resource/images/icons/4.png') ?>">
                                             </div>
                                             <div class="contact_sub">                                  
                                                  <div class="contact_head"><?= lang('support_email_title') ?></div>
                                                  <div class="contact_content email" ><a href="mailto:<?= lang('support_email') ?>"><?= lang('support_email') ?></a></div>
                                                  <div class="contact_head"><?= lang('hotline') ?></div>
                                                  <div class="contact_content">(65) 6397 5818</div>
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
<script>

     $(window).resize(function () {
          var height = $('.init-home-courier').width() / 1.87;
          $('.init-home-courier').css('min-height', height);
     });
     
     $(document).ready(function () {
          var height = $('.init-home-courier').width() / 1.87;
          $('.init-home-courier').css('min-height', height);
     });
</script>
