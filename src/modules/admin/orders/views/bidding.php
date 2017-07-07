<div class="col-lg-12 no-padding margin_bottom_10">
     <div class="clearfix"></div>
     <div class="pull-left">
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
     </div>
</div>
<div class="clearfix"></div>
<div class="clr"></div>
<div class="table-responsive">
     <table id="bid_list" class="table table-striped table-bordered table-responsive">
          <thead>   
               <tr>
                    <th><?= lang('order_bidders_courier') ?>
                         <i ng-class="{'glyphicon glyphicon-sort':bidheaders.courier.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.courier.reverse == true, 'glyphicon glyphicon-sort-by-attributes': bidheaders.courier.reverse == false}" class="pull-right" ng-click="bidsort('courier')"></i>  
                    </th>
                    <th><?= lang('order_bidders_service') ?>
                         <i ng-class="{'glyphicon glyphicon-sort':bidheaders.service.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.service.reverse == true, 'glyphicon glyphicon-sort-by-attributes': bidheaders.service.reverse == false}" class="pull-right" ng-click="bidsort('service')"></i>  
                    </th>
                    <th><?= lang('order_bidders_price') ?>
                         <i ng-class="{'glyphicon glyphicon-sort':bidheaders.price.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.price.reverse == true, 'glyphicon glyphicon-sort-by-attributes': bidheaders.price.reverse == false}" class="pull-right" ng-click="bidsort('price')"></i>  
                    </th>
                    <th><?= lang('order_bidders_remarks') ?>
                         <i ng-class="{'glyphicon glyphicon-sort':bidheaders.remarks.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': bidheaders.remarks.reverse == true, 'glyphicon glyphicon-sort-by-attributes': bidheaders.remarks.reverse == false}" class="pull-right" ng-click="bidsort('remarks')"></i>  
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
                    <td><span ng-click="view_service_info(bid.service_row_id)" class="link_color">{{bid.service}}</span></td>
                    <td>{{bid.price}}</td>
                    <td>{{bid.remarks}}</td>
                    <td>
                         <button type="button" class="btn btn-default btn-sm" ng-click="accept(bid.bid_id)" ng-if="bid.is_approved == 0"><?= lang("order_accept") ?></button>
                         <span class="label label-success" ng-if="bid.is_approved == 1"><?= lang("order_accepted") ?></span>
                    </td>
               </tr>
               <tr class="no-data">
                    <td colspan="5"><?= lang('nothing_to_display') ?></td>
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
