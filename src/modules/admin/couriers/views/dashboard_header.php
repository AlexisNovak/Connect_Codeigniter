<div id="header">
     <div class="color-line">
     </div>
     <div id="logo" class="light-version">
          <span>
               <?= lang('website_title') ?>
          </span>
     </div>
     <div class="logo"><img src="<?php echo base_url(); ?>resource/images/favicons.png"></div>
     <div class="welcome_title"><h3><?= lang('website_welcome') ?></h3></div>
     <nav role="navigation">
          <minimaliza-menu></minimaliza-menu>
          <div class="small-logo">
               <span class="text-primary">6Connect</span>
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
                         <a href="<?= site_url('couriers/log_out') ?>">
                              <i class="pe-7s-upload pe-rotate-90"></i>
                         </a>
                    </li>
               </ul>
          </div>
     </nav>
</div>



