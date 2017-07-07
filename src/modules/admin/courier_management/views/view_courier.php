<div class="content">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
     </div>
     <?php
       if (isset($courier)) {
            ?>
            <div class="hpanel">
                 <tabset>
                      <tab>
                           <tab-heading>
                                <?= lang('courier_details_tab') ?>
                           </tab-heading>
                           <div class="panel-body no-padding"> 
                                <?php $this->load->view('courier_details'); ?>   
                           </div>
                      </tab>                                                         
                      <tab>
                           <tab-heading>
                                <?= lang('courier_services_tab') ?>
                           </tab-heading>
                           <div class="panel-body no-padding">
                                <?php $this->load->view('courier_services'); ?>   
                           </div>
                      </tab>
                      <tab>
                           <tab-heading>
                                <?= lang('courier_orders_tab') ?>
                           </tab-heading>
                           <div class="panel-body no-padding">
                                <?php $this->load->view('courier_orders'); ?>   
                           </div>
                      </tab>
                 </tabset>                 

            </div>
            <?php
       } else {
            ?>
            <p class="well text-danger"><?= lang('nothing_to_display') ?></p>
            <?php
       }
     ?>
</div>

