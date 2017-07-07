<aside id="menu">
     <div id="navigation">
          <div class="profile-picture">
               <?php
                 $logo = $courier->logo;
                 if (isset($logo) && strlen(trim($logo) && $logo != 'null') > 0) {
                      $remote = stristr($logo, 'http'); // do a check here to see if image is from twitter / facebook / remote URL
                      if (!$remote) {
                           $src = base_url('filebox/couriers/' . $logo);
                      } else {
                           $src = $logo;
                      }
                 } else {
                      $src = base_url("resource/images/default_logo.png");
                 }
               ?>
               <a  ui-sref="dashboard.overview">
                    <img src="<?= $src ?>" class="img-circle m-b" alt="logo" id="courier_logo_navigation_bar">
               </a>

               <div class="stats-label text-color">

                    <div class="dropdown" dropdown>
                         <a dropdown-toggle class="dropdown-toggle" href="#">
                              <span class="font-extra-bold font-uppercase" id="courier_name_navigation_bar"><?php echo $courier->company_name; ?></span>
                              <b class="caret"></b></small>
                         </a>
                         <ul class="dropdown-menu animated fadeInRight m-t-xs">
                              <li><a ui-sref="account_profile"><?= lang('manage_profile') ?></a></li>
                              <li><a ui-sref="account_settings"><?= lang('manage_settings') ?></a></li>
                              <li><a ui-sref="account_password"><?= lang('manage_password') ?></a></li>
                              <li><a ui-sref="c_notifications"><?= lang('email_notifications') ?></a></li>
                              <li class="divider"></li>
                              <li><a href="<?= site_url('couriers/log_out') ?>"><?= lang('logout') ?></a></li>
                         </ul>
                    </div>
               </div>
          </div>
          <ul side-navigation class="nav" id="side-menu">             
               <li ui-sref-active="active">
                    <a ui-sref="assigned_orders">
                         <span class="nav-label"><?= lang('orders') ?></span>
                         <span class="label label-success pull-right" ng-if="pending_orders">{{pending_orders}}</span>
                    </a>
               </li>                            
               <li ui-sref-active="active">
                    <a ui-sref="available_service_requests"> <span class="nav-label">Service Requests</span> </a>
               </li>                          
               <li ui-sref-active="active">
                    <a ui-sref="drivers"> <span class="nav-label">Drivers</span> </a>
               </li>
               <li class="open active">
                    <a href="#"><span class="nav-label"><?= lang('tender_request') ?></span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level in">
                         <li ui-sref-active="active"><a ui-sref="tenders"><span class="label label-success pull-right mcount" ng-if="o_new_msg_count != 0">{{o_new_msg_count}}</span><?= lang('t_r_delivery') ?></a></li>
                         <li ui-sref-active="active"><a ui-sref="service_requests"><span class="label label-success pull-right mcount" ng-if="s_new_msg_count != 0">{{s_new_msg_count}}</span>
                                   <?= lang('t_r_service') ?> </a></li>
                    </ul>
               </li> 
               <li ui-sref-active="active">
                    <a ui-sref="ownservices"> <span class="nav-label">Services</span> </a>
               </li>
               <li ui-sref-active="active">
                    <a ui-sref="organisations"> <span class="nav-label">Organisations</span> </a>
               </li>
               <li ng-class="{'active': $state.includes('reports')}">
                    <a href="#"><span class="nav-label">Reports</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level" ng-class="{'in':$state.includes('reports') }">
                         <li ui-sref-active="active"><a ui-sref="reports.invoice">Invoice</a></li>
                         <li ui-sref-active="active"><a ui-sref="reports.downloads">Download Invoices</a></li>
                    </ul>
               </li>  
               <li ui-sref-active="active">
                    <a href="<?php echo site_url('courierapi'); ?>" target="_blank"> <span class="nav-label">API Doc</span></a>
               </li>
          </ul>
     </div>
</aside>