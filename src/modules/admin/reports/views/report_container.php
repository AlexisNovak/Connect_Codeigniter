<div class="content">
     <div  animate-panel>
          <div class="row" ng-controller="reportsCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">                            
<!--                              <div class="col-lg-12 no-padding margin_bottom_10">
                                   <div class="btn-group btn-group-justified" ng-class="{in: $state.includes('reports')}">
                                        <span ui-sref-active="active" class="btn btn-default">
                                             <a ui-sref="organisation.reports.userperform">User Performance</a>
                                        </span>
                                        <span ui-sref-active="active" class="btn btn-default">
                                             <a ui-sref="organisation.reports.overall">Overall Usage Report</a>
                                        </span>
                                        <span ui-sref-active="active" class="btn btn-default">
                                             <a ui-sref="organisation.reports.groupperform">Group Performance</a>
                                        </span>
                                   </div>
                              </div>-->
                              <div class="col-lg-12 no-padding margin_bottom_10">                             
                                   <div ui-view></div>                                   
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>