<?php if (strtolower($this->uri->segment(1, 0)) !== "partner"): ?>
       <div id="header">
            <div class="color-line">
            </div>
            <div id="logo" class="light-version">
                 <span>
                      <?= lang('website_title') ?>
                 </span>
            </div>
            <a ui-sref="dashboard">
                 <div class="logo">
                      <img src="<?php echo base_url(); ?>resource/images/main_logo.png">

                 </div>
            </a>
            <nav role="navigation">
                 <minimaliza-menu></minimaliza-menu>
                 <div class="small-logo">
                      <span class="text-primary">6Connect</span>
                 </div>
                 <div class="header-btn">
                      <div class="btn-group">
                           <a ui-sref="delivery_orders.new_order" class="btn btn-sm btn-logo-green"><?= lang('new_order_title') ?></a>
                           <button type="button" class="btn btn-sm btn-logo-green dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                           </button>
                           <ul class="dropdown-menu">
                                <li><a ui-sref="delivery_orders.new_order"><?= lang('new_order_title') ?></a></li>
                                <li><a ui-sref="delivery_orders.multiple_order"><?= lang('multiple_order') ?></a></li>
                           </ul>
                      </div>
                 </div>
                 <div class="navbar-right">
                      <ul class="nav navbar-nav no-borders">                         
                           <li class="dropdown" dropdown>
                                <a class="dropdown-toggle label-menu-corner" href="#" dropdown-toggle  ng-click="update_time()">
                                     <i class="pe-7s-mail"></i>
                                     <span class="label label-success" ng-if="new_msg_count != 0">{{new_msg_count}}</span>
                                </a>
                                <ul class="dropdown-menu hdropdown animated flipInX">
                                     <div class="title"ng-if="new_msg_count != 0">
                                          You have {{new_msg_count}} new messages
                                     </div>                                     
                                     <li ng-repeat="msg in msgs"><a ng-href="{{msg.link}}">{{msg.content}}</a></li>
                                     <li class="summary"><a ui-sref="notifications">See All Messages</a></li>
                                </ul>
                           </li>
                           <li class="dropdown">
                                <a href="<?= site_url('account/sign_out') ?>">
                                     <i class="pe-7s-upload pe-rotate-90"></i>
                                </a>
                           </li>
                      </ul>
                 </div>


            </nav>
       </div>
  <?php else: ?>

       <div id="header" class="ifrm">
            <div class="color-line">
            </div>

            <nav role="navigation"> <!-- Brand and toggle get grouped for better mobile display -->
                 <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                           <span class="sr-only">Toggle navigation</span>
                           <span class="icon-bar"></span>
                           <span class="icon-bar"></span>
                           <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand">
                           <?= lang('website_welcome') ?></a>

                 </div>

                 <!-- Collect the nav links, forms, and other content for toggling -->
                 <div class="collapse navbar-collapse" id="navbar-collapse-1">

                      <div class="navbar-right">
                           <ul class="nav navbar-nav no-borders">
                                <li ui-sref-active="active">
                                     <a ui-sref="organisations"> <span class="nav-label"><?= lang('my_organizations') ?></span> </a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="delivery_orders"> <span class="nav-label"><?= lang('orders') ?></span> </a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="delivery_orders.new_order"> <span class="nav-label">new order</span> </a>
                                </li>
                                <li ui-sref-active="active">
                                     <a ui-sref="delivery_orders.multiple_order"> <span class="nav-label">Multiple Orders</span> </a>
                                </li>  
                                <li class="dropdown">
                                     <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                          <i class="glyphicon glyphicon-cog"></i>
                                     </a>
                                     <ul class="dropdown-menu">
                                          <li><a ui-sref="account_profile"><?= lang('manage_profile') ?></a></li>
                                          <li><a ui-sref="account_settings"><?= lang('manage_settings') ?></a></li>
                                          <li><a ui-sref="account_password"><?= lang('manage_password') ?></a></li>
                                          <li class="divider"></li>
                                          <li><a href="<?= site_url('account/sign_out') ?>"><?= lang('logout') ?></a></li>

                                     </ul>
                                </li> 
                           </ul>
                      </div>

                 </div>
            </nav>
       </div>
<?php endif; ?>


