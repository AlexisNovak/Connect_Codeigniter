<div class="content padding_0">
     <div class="wrap" animate-panel  ng-hide="intro">
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
          <?php
            if (isset($request) && !empty($request)) {
                 ?>
                 <div class="row">
                      <div class="col-lg-12">
                           <div class="hpanel">
                                <div class="panel-body">                              
                                     <div class="col-lg-12" id="order_tabs">
                                          <div class="hpanel">
                                               <tabset class="narrow_tabs">
                                                    <tab>
                                                         <tab-heading>
                                                              <i class="fa fa-exclamation-circle"></i> 
                                                              <?= lang('order_detail_tab') ?>
                                                         </tab-heading>
                                                         <div class="panel-body no-padding"> 
                                                              <?php $this->load->view('request/details'); ?>   
                                                         </div>
                                                    </tab>
                                                    <tab>
                                                         <tab-heading>
                                                              <i class="fa fa-share-alt"></i> 
                                                              <?= lang('order_bidders_tab') ?>
                                                              <span class="label label-danger">{{bidcount.total|| ""}}</span>
                                                         </tab-heading>
                                                         <div class="panel-body no-padding">
                                                              <?php $this->load->view('request/bids'); ?>   
                                                         </div>
                                                    </tab>
                                                    <tab>
                                                         <tab-heading>
                                                              <i class="fa fa-envelope-o"></i> 
                                                              <?= lang('order_msg_tab') ?>
                                                              <span class="label label-danger">{{(msgcount.total - msgcount.reply) || ""}}</span>
                                                         </tab-heading>
                                                         <div class="panel-body no-padding">
                                                              <?php $this->load->view('request/messages'); ?>   
                                                         </div>
                                                    </tab>
                                                    <tab>
                                                         <tab-heading>
                                                              <i class="fa fa-tasks"></i> 
                                                              <?= lang('order_log_tab') ?>
                                                         </tab-heading>
                                                         <div class="panel-body no-padding">
                                                              <?php $this->load->view('request/log'); ?>   
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

     <div  animate-panel ng-show="intro">
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="col-xs-12 no-padding">
                                   <span class="close_started" ng-click="close_intro()"></span>                                 
                              </div>
                              <div class="srv_intro">
                                   <h2><?= lang('congrats_service_tender') ?></h2>
                                   <p class="h_sub"><?= lang('congrats_service_tender_info') ?><span> {{courier_name}}</span></p>   

                                   <div class="col-xs-12 col-sm-6">
                                        <div class="srv_info">
                                             <div class="use circle-bg">Use</div>
                                             <p><?= lang('use_info') ?></p>
                                             <a ui-sref="available_services"><?= lang('use_link') ?></a>
                                        </div>
                                   </div>
                                   <div class="col-xs-12 col-sm-6">
                                        <div class="srv_info srv_info_left">
                                             <div class="manage circle-bg">Manage</div>
                                             <p><?= lang('manage_info') ?></p>
                                             <a ui-sref="organisation.services({id:org_id})"><?= lang('manage_link') ?></a>
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
     $(document).ready(function () {
          $('.i-checks').iCheck({
               checkboxClass: 'icheckbox_square-green',
               radioClass: 'iradio_square-green',
          });
     });
</script>