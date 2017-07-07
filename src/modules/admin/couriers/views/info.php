<div class="content">
     <div  animate-panel>
          <div class="hpanel">
               <div class="panel-body">
                    <?php
                      if (isset($courier)) {
                           if (isset($courier->is_verified) && $courier->is_verified == 0) {
                                ?>
                                <div class="row">
                                     <div class="col-lg-12">
                                          <?php echo lang("not_email_confirmed"); ?>

                                     </div>
                                </div>
                                <?php
                           } else {
                                if (!$courier->is_approved) {
                                     ?>
                                     <div class="row">
                                          <div class="col-lg-12">
                                               <?php echo lang("not_account_approved"); ?>
                                          </div>
                                     </div>
                                     <?php
                                } else {
                                     ?>
                                     <div class="row">
                                          <div class="col-lg-12">
                                               <div class="col-lg-3 text-right col-md-3 col-sm-3 col-xs-3">
                                                    <div class="avatar">                                                        
                                                         <img id="org_profile_dp"  ng-src="{{courier.logo}}" alt="logo" class="img-circle m-b">
                                                    </div> 
                                                    <div class="clr"></div>
                                               </div>

                                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                    <div class="org_current_details" ng-hide="isEditing" >
                                                         <h2 class="text-capitalize">{{courier.company_name}}</h2>
                                                         <p class="text-justify">{{courier.description}}</p>
                                                    </div>
                                               </div>
                                          </div>
                                     </div>
                                     <div class="hr-line-dashed"></div>                                  
                                </div>   
                                <?php
                           }
                      }
                 }
               ?>
          </div>       
     </div>
</div>

