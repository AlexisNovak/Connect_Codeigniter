<div class="content">
     <div animate-panel>
          <div class="hpanel forum-box">

               <div class="panel-heading">
                    <span class="pull-right">
                         <i class="fa fa-refresh text-success pointer" ng-click="init_msgs()" title="refresh"></i> 
                         <i class="fa fa-clock-o"> </i> Last modification: {{last_notified}}
                    </span>
                    Notifications
               </div>
               <div class="panel-body" ng-if="msgs.length == 0">
                    <div class="row">
                         <div class="col-md-10 forum-heading animated-panel" style="animation-delay: 0.9s;">
                              <div class="desc"><?= lang('nothing_to_display') ?></div>
                         </div>
                    </div>
               </div>
               <div class="panel-body" ng-repeat="msg in msgs">
                    <div class="row">
                         <div class="col-md-10 forum-heading animated-panel" style="animation-delay: 0.9s;">
                              <a ng-href="{{msg.link}}">
                                   <h4>{{msg.title}}</h4>
                                   <div class="desc" ng-bind-html="msg.content"></div>
                              </a>
                         </div>
                    </div>
               </div> 
               <div class="panel-heading text-center" ng-if="loadmore">                  
                    <span ng-if="!loading" ng-click="load_more_messages()" class="pointer">Loadmore</span>      
                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="20" height="20" alt="<?= lang('loading') ?>" ng-if="loading">

               </div>
          </div>
     </div>
</div>

