<div class="ifrm">
     <nav class="navbar navbar-default">
          <div class="container-fluid">
               <!--Brand and toggle get grouped for better mobile display--> 
               <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                         <span class="sr-only">Toggle navigation</span>
                         <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                         <span class="icon-bar"></span>
                    </button>
                    <a ui-sref="dashboard">
                         <div class="logo">
                              <img src="<?php echo outer_base_url(); ?>resource/images/partner_logo.png">

                         </div>
<!--                         <div class="welcome_title">
                              <h3>6Connect</h3>
                              <span>Deliveries simplified for Enterprises</span>
                         </div>-->
                    </a>
               </div>

               <!--Collect the nav links, forms, and other content for toggling--> 
               <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                         <li class="dropdown">
                              <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                                   <span class="nav-label"><?= lang('orders') ?></span> 
                              </a>
                              <ul class="dropdown-menu">
                                   <li>
                                        <a ui-sref="delivery_orders">
                                             <img src="<?= outer_base_url() ?>resource/images/order.png"> <span class="nav-label"><?= lang('view_order_title') ?></span> 
                                        </a>
                                   </li>
                                   <li ui-sref-active="active">
                                        <a ui-sref="delivery_orders.new_order"> 
                                             <img src="<?= outer_base_url() ?>resource/images/order.png"> <span class="nav-label"><?= lang('new_order_title') ?></span> 
                                        </a>
                                   </li>
                                   <li ui-sref-active="active">
                                        <a ui-sref="delivery_orders.multiple_order">
                                             <img src="<?= outer_base_url() ?>resource/images/multi_order.png"> <span class="nav-label"><?= lang('multiple_order') ?></span> </a>
                                   </li>   
                              </ul>
                         </li>
                         <li class="dropdown">
                              <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
                                   <span class="nav-label"><?= lang('tender_request') ?></span> 
                              </a>
                              <ul class="dropdown-menu">
                                   <li><a ui-sref="tender_requests.delivery"><?= lang('t_r_delivery') ?></a></li>
                                   <li><a ui-sref="tender_requests.service"><?= lang('t_r_service') ?></a></li>
                              </ul>
                         </li>

                         <li ui-sref-active="active">
                              <a ui-sref="my_contacts"> <span class="nav-label"><?= lang('my_contacts') ?></span> </a>
                         </li>
                         <li ui-sref-active="active">
                              <a ui-sref="available_services">
                                   <span class="nav-label">    <?= lang('service_title') ?></span>
                              </a>
                         </li>



                         <li class="dropdown">
                              <?php
                                $picture = $account_details->picture;
                                if (isset($picture) && strlen(trim($picture)) > 0) {
                                     $remote = stristr($picture, 'http'); // do a check here to see if image is from twitter / facebook / remote URL
                                     if (!$remote) {
                                          $src = outer_base_url('filebox/user/profile/' . $picture);
                                     } else {
                                          $src = $picture;
                                     }
                                } else {
                                     $src = outer_base_url("resource/images/default-person.png");
                                }
                              ?>
                              <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                   <!--<img src="<?= $src ?>" class="img-circle" alt="logo" id="user_profile_navigation_bar" style="height: 40px;margin-bottom: 5px;">-->
                                   <i class="fa fa-caret-down"></i>
                              </a>
                              <ul class="dropdown-menu">   
                                   <li>
                                        <a ui-sref="organisations"><?= lang('organizations') ?></a>
                                   </li>
                                   <!--<li><a ui-sref="account_profile"><?= lang('manage_profile') ?></a></li>-->
                                   <li><a ui-sref="account_settings"><?= lang('manage_settings') ?></a></li>
                                   <li><a ui-sref="account_notifications"><?= lang('email_notifications') ?></a></li>
                              </ul>
                         </li> 
                    </ul>
               </div> 
          </div>
     </nav>
</div>