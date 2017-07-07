<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <!-- angular popup starts -->
                              <!-- ends angular popup -->
                              <div class="clearfix"></div>
                              <div ng-show="show_table">
                                   <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">                                  
                                        <div class="pull-left">
                                             <div  class="dataTables_length">
                                                  <form>
                                                       <label>
                                                            Show
                                                            <select class="form-control"  name="perpage" ng-model="feedbacklistdata.perpage"  
                                                                    ng-options="orgperpages as orgperpages.label for orgperpages in orgperpage" ng-change="perpagechange()">
                                                                 <option style="display:none" value class>15</option>
                                                            </select>
                                                            entries
                                                       </label>
                                                  </form>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clearfix"></div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="organisation_list" class="table table-striped table-bordered table-responsive">
                                             <thead>
                                                  <tr>
                                                       <th>FeedbackID
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.id.reverse == false}" class="pull-right" ng-click="sort('id')"></i>  
                                                       </th>
                                                       <th>Service Name
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.service_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.service_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.service_name.reverse == false}" class="pull-right" ng-click="sort('service_name')"></i>  
                                                       </th>                                                   
                                                       <th>Feedback Rating
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.score.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.score.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.score.reverse == false}" class="pull-right" ng-click="sort('score')"></i>  
                                                       </th>
                                                       <th>Customer Name
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.customer_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.customer_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.customer_name.reverse == false}" class="pull-right" ng-click="sort('customer_name')"></i>  
                                                       </th>    
                                                       <th style="width:10%">Timestamp
                                                            <i ng-class="{'glyphicon glyphicon-sort':orgheaders.timestamp.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': orgheaders.timestamp.reverse == true, 'glyphicon glyphicon-sort-by-attributes': orgheaders.timestamp.reverse == false}" class="pull-right" ng-click="sort('timestamp')"></i>  
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="feedback in feedbackslist|orderBy:orderByField:reverseSort">
                                                       <td><a class="link_color"  ui-sref="accounts.feedbackview({id:feedback.id})">{{ padinteger(feedback.id, 10)}}</a></td>
                                                       <td>{{feedback.service_name}}</td>
                                                       <td>{{feedback.score}}</td>
                                                       <td>{{feedback.customer_name}}</td>
                                                       <td>{{feedback.timedata * 1000| date}}</td>

                                                  </tr>
                                                  <tr class="no-data">
                                                       <td colspan="5"><?= lang('nothing_to_display') ?></td>
                                                  </tr>  
                                             </tbody>
                                        </table>


                                        <!--angular View Rating-->
                                        <div class="angular_popup pull-right warning_box popup_mid feedback_popup" ng-show="show_feedback"> 
                                             <h3>Details<i class="fa fa-close pull-right" ng-click="hide_feedback()"></i></h3>
                                             <p>
                                                  <label>Feedback ID:</label>
                                                  <span>{{padinteger(singlefeedback.id, 10)}}</span>
                                             </p>
                                             <p>
                                                  <label>Service Name:</label>
                                                  <span>{{singlefeedback.service_name}}</span>
                                             </p>
                                             <p>
                                                  <label>Feedback Rating:</label>
                                                  <span class="starRating " style="margin-bottom: 8px;">
                                                       <span class="stars">
                                                            <span ng-style="{ 'width': getStars(singlefeedback.rating) }"></span>
                                                       </span>
                                                  </span>
                                             </p>
                                             <p>
                                                  <label>Customer Name:</label>
                                                  <span>{{singlefeedback.customer_name}}</span>
                                             </p>
                                             <p>
                                                  <label>Courier Name:</label>
                                                  <span>{{singlefeedback.courier_name}}</span>
                                             </p>
                                             <p>
                                                  <label>Review:</label><br>
                                                  <span>{{singlefeedback.review}}</span>
                                             </p>
                                        </div>
                                        <!--end of angular View Rating-->


                                   </div>
                                   <div class="col-md-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                        </div> 
                                        <div class="col-md-8 text-right no-padding">
                                             <paging
                                                  class="small"
                                                  page="feedbacklistdata.currentPage" 
                                                  page-size="feedbacklistdata.perpage_value" 
                                                  total="feedbacklistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getOrganisations(page)">
                                             </paging> 
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
