<div class="col-lg-12 no-padding margin_bottom_10">

     <div class="clearfix"></div>
     <!-- <div class="pull-left">
          <div  class="dataTables_length">
               <form>
                    <label>
                         Show
                         <select class="form-control"  name="perpage" ng-model="bidlistdata.perpage"  
                                 ng-options="bidperpages as bidperpages.label for bidperpages in bidperpage" ng-change="bidperpagechange()">
                              <option style="display:none" value class>15</option>
                         </select>
                         entries
                    </label>
               </form>
          </div>
     </div>
     <div class="pull-right no-padding">
          <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
               <form>
                    <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                    <span class="no-padding table_filter" style="display: inline-flex;">
                         <input ng-change="findBidders()" aria-controls="bid_list"  class="form-control input-sm" type="search" ng-model="bidlistdata.filter">
                    </span>
               </form>
          </div>
     </div> -->


     <div class="angular_popup popup_sm pull-right warning_box s_detail" ng-show="show_service_info"> 
          <h3><?= lang('service_detail_title') ?><i class="fa fa-close pull-right" ng-click="cancel_service_info()"></i></h3>
          <table class="table table-bordered table-striped">                    
               <tr><td>Name</td><td>{{sdetail.name}}</td></tr> 
               <tr><td>Description</td><td>{{sdetail.description}}</td></tr>
               <tr><td>Price</td><td>{{sdetail.price}}</td></tr>
               <tr><td>Start Time</td><td>{{sdetail.start_time}}</td></tr>
               <tr><td>End Time</td><td>{{sdetail.end_time}}</td></tr> 
               <tr><td>Origin</td><td>{{sdetail.origin}}</td></tr>
               <tr><td>Destination</td><td>{{sdetail.destination}}</td></tr>
               <tr><td>Working Days</td><td>{{sdetail.working_days}}</td></tr>
               <tr><td>Type</td><td>{{sdetail.type}}</td></tr>
               <tr><td>Status</td><td>{{sdetail.status}}</td></tr>
          </table>
     </div>
</div>
<div class="clearfix"></div>
<div class="clr"></div>
<div class="table-responsive">
     <table id="bid_list" class="table table-striped table-bordered table-responsive">
          <thead>             
          <th><?= lang('service_bidders_courier') ?>
               <i ng-class="{'glyphicon glyphicon-sort':bidheaders.courier.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.courier.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.courier.reverse == false}" class="pull-right" ng-click="bidsort('courier')"></i>  
          </th>
          <th><?= lang('service_bidders_service') ?>
               <i ng-class="{'glyphicon glyphicon-sort':bidheaders.service.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.service.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.service.reverse == false}" class="pull-right" ng-click="bidsort('service')"></i>  
          </th>
          <th><?= lang('service_bidders_time') ?>
               <i ng-class="{'glyphicon glyphicon-sort':bidheaders.start_time.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.start_time.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.start_time.reverse == false}" class="pull-right" ng-click="bidsort('start_time')"></i>  
          </th>
          <th><?= lang('service_bidders_price') ?>
               <i ng-class="{'glyphicon glyphicon-sort':bidheaders.price.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.price.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.price.reverse == false}" class="pull-right" ng-click="bidsort('price')"></i>  
          </th>
          <th style="width:30%"><?= lang('service_bidders_remarks') ?>
               <i ng-class="{'glyphicon glyphicon-sort':bidheaders.remarks.reverse == undefined,'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.remarks.reverse == true,'glyphicon glyphicon-sort-by-attributes': bidheaders.remarks.reverse == false}" class="pull-right" ng-click="bidsort('remarks')"></i>  
          </th>
          <th><?= lang('action') ?>                        
          </th>
          </tr>
          </thead>
          <tbody>
               <tr ng-repeat="bid in bidlist| orderBy:orderByField_bid:reverseSort_bid">                   
                    <td>
                         <span ng-click="view_courier_info(bid.courier_id)" class="courier_name">{{bid.courier}}</span>                         
                    </td>
                    <td><span ng-click="view_service_info(bid.service_id)" class="link_color">{{bid.service}}</span></td>
                    <td>{{bid.start_time}} - {{bid.end_time}}</td>
                    <td>{{bid.price|currency}}</td>
                    <td>{{bid.description}}</td>
                    <td>
                         <button type="button" class="btn btn-default btn-sm" ng-click="accept(bid.bid_id, bid.courier)" ng-if="bid.status == 1">
                              <?= lang("service_accept") ?>
                         </button>
                         <span class="label label-success" ng-if="bid.status == 2">
                              <?= lang("service_accepted") ?>
                         </span>
                    </td>
               </tr>

               <tr class="no-data">
                    <td colspan="7"><?= lang('nothing_to_display') ?></td>
               </tr>
          </tbody>
     </table>
</div>
<div class="col-md-12 no-padding">
     <div class="col-md-4 no-padding">
          <div ng-show="bidcount.total" style="line-height: 35px;">Showing {{bidcount.start}} to {{bidcount.end}} of {{bidcount.total}} entries</div>
     </div> 
     <div class="col-md-8 text-right no-padding">

          <paging
               class="small"
               page="bidlistdata.currentPage" 
               page-size="bidlistdata.perpage_value" 
               total="bidlistdata.total"
               adjacent="{{adjacent}}"
               dots="{{dots}}"
               scroll-top="{{scrollTop}}" 
               hide-if-empty="false"
               ul-class="{{ulClass}}"
               active-class="{{activeClass}}"
               disabled-class="{{disabledClass}}"
               show-prev-next="true"
               paging-action="getBidders(page)">
          </paging> 
     </div>
</div>
