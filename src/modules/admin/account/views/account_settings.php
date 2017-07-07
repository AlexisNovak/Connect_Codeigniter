<div class="content">
     <div class="wrap" animate-panel>
          <div class="hpanel">
               <tabset>
                    <tab>
                         <tab-heading>
                              <?= lang('manage_settings') ?>
                         </tab-heading>
                         <div class="panel-body no-padding"> 
                              <?php $this->load->view('account_details'); ?>   
                         </div>
                    </tab> 
                    <tab>
                         <tab-heading>
                              <?= lang('email_notifications') ?>
                         </tab-heading>
                         <div class="panel-body no-padding"> 
                              <?php $this->load->view('notification_settings'); ?>   
                         </div>
                    </tab> 
                    <tab>
                         <tab-heading>
                              <?= lang('manage_payment_account') ?>
                         </tab-heading>
                         <div class="panel-body no-padding">
                              <?php $this->load->view('account_payment'); ?>   
                         </div>
                    </tab>
               </tabset>

          </div>
     </div>
</div>