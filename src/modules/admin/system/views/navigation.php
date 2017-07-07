<aside id="menu">
     <div id="navigation">
          <div class="profile-picture">
               <?php
                 $picture = $account_details->picture;
                 if (isset($picture) && strlen(trim($picture)) > 0) {
                      $remote = stristr($picture, 'http'); // do a check here to see if image is from twitter / facebook / remote URL
                      if (!$remote) {
                           $src = base_url('filebox/user/profile/' . $picture);
                      } else {
                           $src = $picture;
                      }
                 } else {
                      $src = base_url("resource/images/default-person.png");
                 }
               ?>
               <a ui-sref="dashboard">
                    <img src="<?= $src ?>" class="img-circle m-b" alt="logo" id="user_profile_navigation_bar">
               </a>

               <div class="stats-label text-color">

                    <div class="dropdown" dropdown >
                         <a dropdown-toggle class="dropdown-toggle" href="#">
                              <span class="font-extra-bold font-uppercase" id="user_name_navigation_bar">
                                   <?php echo $account->username; ?>

                              </span><b class="caret"></b>
                         </a>
                         <ul class="dropdown-menu animated fadeInRight m-t-xs">
                              <li><a ui-sref="account_profile"><?= lang('manage_profile') ?></a></li>
                              <li><a ui-sref="account_settings"><?= lang('manage_settings') ?></a></li>
                              <?php
                                if ($account->login_via == 0) {
                                     ?>
                                     <li><a ui-sref="account_password"><?= lang('manage_password') ?></a></li>
                                     <?php
                                }
                              ?>
                              <li class="divider"></li>
                              <li><a href="<?= site_url('account/sign_out') ?>"><?= lang('logout') ?></a></li>
                         </ul>
                    </div>



               </div>
          </div>

          <ul side-navigation class="nav" id="side-menu">
               <?php if (false) { ?>
                      <?php if ($this->authorization->is_permitted(array('retrieve_users', 'retrieve_roles', 'retrieve_permissions'))) : ?>
                           <?php if ($this->authorization->is_permitted('retrieve_users')) : ?>
                                <li class="ui-sref-active"><a ui-sref="manage_users"> <span class="nav-label"><?= lang('website_manage_users') ?></span></a></li>
                           <?php endif; ?>
                           <?php if ($this->authorization->is_permitted('retrieve_roles')) : ?>
                                <li class="ui-sref-active"><a ui-sref="manage_roles"><span class="nav-label"><?= lang('website_manage_roles') ?></span></a></li>
                           <?php endif; ?>
                           <?php if ($this->authorization->is_permitted('retrieve_permissions')) : ?>
                                <li class="ui-sref-active"><a ui-sref="manage_permissions"><span class="nav-label"><?= lang('website_manage_permissions') ?></span></a></li>
                           <?php endif; ?>
                      <?php endif; ?>

                 <?php } ?>

               <li ui-sref-active="active">
                    <a ui-sref="delivery_orders">
                         <span class="nav-label"><?= lang('orders') ?></span>
                         <span class="label label-success pull-right mcount" ng-if="o_new_msg_count != 0">{{o_new_msg_count}}</span>
                    </a>
               </li>    
               <li class="open active">
                    <a href="#"><span class="nav-label"><?= lang('tender_request') ?></span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level in">
                         <li ui-sref-active="active"><a ui-sref="tender_requests.delivery"><?= lang('t_r_delivery') ?></a></li>
                         <li ui-sref-active="active"><a ui-sref="tender_requests.service">
                                   <span class="label label-success pull-right mcount" ng-if="s_new_msg_count != 0">{{s_new_msg_count}}</span>
                                   <?= lang('t_r_service') ?> </a></li>
                    </ul>
               </li>
               <li ui-sref-active="active">
                    <a ui-sref="available_services">
                         <?= lang('services') ?>
                    </a>
               </li>     
               <li ui-sref-active="active">
                    <a ui-sref="organisations"> <span class="nav-label"><?= lang('organizations') ?></span> </a>
               </li>

               <?php if ($account->root == 1) { ?>

                      <li ng-class="{'active': $state.includes('accounts')||$state.includes('cust_parcel_type')|| $state.includes('new_parcel_type')||$state.includes('edit_parcel_type')|| $state.includes('holidays') || $state.includes('couriers') || $state.includes('partner')|| $state.includes('tip_of_the_day')|| $state.includes('rootservices')}">
                           <a href="#">
                                <span class="nav-label"><?= lang('support_tools') ?></span>
                                <span class="fa arrow"></span> 
                           </a>
                           <ul class="nav nav-second-level" ng-class="{'in': $state.includes('holidays')|| $state.includes('holidays') ||$state.includes('cust_parcel_type')|| $state.includes('new_parcel_type')||$state.includes('edit_parcel_type') || $state.includes('couriers') || $state.includes('partner')|| $state.includes('rootservices')|| $state.includes('tip_of_the_day')}">
                                <li ui-sref-active="active">
                                     <a ui-sref="accounts.members"><?= lang('ac_members') ?></a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="accounts.organisations">
                                          <?= lang('ac_organisations') ?> </a>
                                </li>

                                <li ui-sref-active="active">
                                     <a ui-sref="accounts.paymentmethod">
                                          <?= lang('ac_payment_met') ?> </a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="couriers"> <span class="nav-label"><?= lang('couriers') ?></span> </a>
                                </li>
                                
                                <li ui-sref-active="active">
                                     <a ui-sref="rootservices"> <span class="nav-label"><?= lang('services') ?></span> </a>
                                </li>
                                
                                <li ui-sref-active="active">
                                     <a ui-sref="partner"> <span class="nav-label"><?= lang('partner') ?></span> </a>
                                </li>

                                <li ui-sref-active="active" ng-class="{'active': $state.includes('accounts.feedbackview')}">
                                     <a ui-sref="accounts.feedback">
                                          <?= lang('customer_feedback') ?> 
                                     </a>
                                </li>

                                <li ui-sref-active="active" >
                                     <a ui-sref="tip_of_the_day"> 
                                          <span class="nav-label"><?= lang('tip_of_the_day') ?></span> 
                                     </a>
                                </li>
                                
                                <li ui-sref-active="active" >
                                     <a ui-sref="cust_parcel_type"> 
                                          <span class="nav-label"><?= lang('cust_parcel_type') ?></span> 
                                     </a>
                                </li>
                                <li ui-sref-active="active" >
                                     <a ui-sref="holidays"> 
                                          <span class="nav-label">Holidays</span> 
                                     </a>
                                </li>
                           </ul>
                      </li>
                 <?php } ?>

               <li ui-sref-active="active">
                    <a ui-sref="my_contacts"> <span class="nav-label"><?= lang('my_contacts') ?></span> </a>
               </li>  
               <?php
                 if ($account->is_partner_user == 1) {
                      ?>
                      <li ng-class="{'active': $state.includes('partner_user')}">
                           <a href="#">
                                <span class="nav-label"><?= lang('p_partner') ?></span>
                                <span class="fa arrow"></span> 
                           </a>
                           <ul class="nav nav-second-level" ng-class="{'in': $state.includes('partner_user')}">
                                <li ui-sref-active="active">
                                     <a ui-sref="partner_user.users"><?= lang('p_users') ?></a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="partner_user.orders">
                                          <?= lang('p_orders') ?> </a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="partner_user.export">
                                          <?= lang('p_export') ?> </a>
                                </li>
                           </ul>
                      </li>
                      <?php
                 }
               ?>
          </ul>
     </div>
</aside>