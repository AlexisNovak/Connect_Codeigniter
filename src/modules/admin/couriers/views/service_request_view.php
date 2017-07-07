<div class="content">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
          <div class="row"> 
               <div class="col-sm-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="col-lg-12">
                                   <div class="col-lg-12">
                                        <legend><?= $service->display_name ?></legend>
                                        <table class="table table-striped table-bordered table-hover">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 50%"><?=lang('req_org')?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':requestheaders.organisation.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': requestheaders.organisation.reverse == true, 'glyphicon glyphicon-sort-by-attributes': requestheaders.organisation.reverse == false}" class="pull-right" ng-click="rsort('organisation')"></i>  
                                                       </th>
                                                         <th style="width:25%"><?=lang('req_stat')?>
                                                            <i ng-class="{'glyphicon glyphicon-sort':requestheaders.status.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': requestheaders.status.reverse == true, 'glyphicon glyphicon-sort-by-attributes': requestheaders.status.reverse == false}" class="pull-right" ng-click="rsort('status')"></i>  
                                                       </th>
                                                       <th style="width: 25%"><?= lang('action') ?></th>                                                       
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="request in requestlist| orderBy:orderByField:reverseSort">
                                                       <td>
                                                            {{request.organisation}}
                                                       </td>
                                                       <td>
                                                             <span class="label label-danger" ng-if="request.status == 0">Rejected</span>
                                                           <span class="label label-warning" ng-if="request.status == 1">New</span>
                                                            <span class="label label-success" ng-if="request.status == 2">Approved</span>
                                                        </td>
                                                       <td>
                                                            <button class="btn btn-sm" ng-click="show_accept(request.request_id)" ng-if="request.status == 1">Approve</button>
                                                            <button class="btn btn-sm" ng-click="show_reject(request.request_id)" ng-if="request.status == 1">Reject</button>
                                                       </td>                                                      
                                                  </tr>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_accept_popup"> 
                                                  <h3>Approve Request?<i class="fa fa-close pull-right" ng-click="cancel_accept()"></i></h3>                                                 
                                                  <p><?=lang('service_approve_confirm')?></p>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="accept_request()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_accept()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <div class="angular_popup popup_sm pull-right warning_box" ng-show="show_reject_popup"> 
                                                  <h3>Reject Request?<i class="fa fa-close pull-right" ng-click="cancel_reject()"></i></h3>                                                 
                                                  <p><?=lang('service_reject_confirm')?></p>
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label>Remarks</label>
                                                            <textarea class="form-control" rows="2" ng-model="reject.remarks"></textarea>
                                                            <span class="help-block m-b-none text-danger" ng-show="reject.errors.remarks">{{reject.errors.remarks}}</span>
                                                       </div>
                                                  </div>
                                                  <div class="btn-holder">
                                                       <span class="btn btn-info btn-sm margin_10" ng-click="reject_request()"  ng-class="{disabled:isDisabled}"><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_reject()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>
                                             <tr class="no-data">
                                                  <td colspan="3"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>