<div class="col-lg-12 no-padding margin_bottom_10">

     <div class="clearfix"></div>
     <div class="pull-left">
          <div  class="dataTables_length">
               <form>
                    <label>
                         Show
                         <select class="form-control"  name="perpage" ng-model="loglistdata.perpage"  
                                 ng-options="logperpages as logperpages.label for logperpages in logperpage" ng-change="logperpagechange()">
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
     <table id="log_list" class="table table-striped table-bordered table-responsive">
          <thead>             
               <tr>
                    <th><?=  lang('order_log_timestamp')?></th>
                    <th><?=  lang('order_log_details')?></th>
               </tr>
          </thead>
          <tbody>
               <tr ng-repeat="log in loglist| orderBy:orderByField_log:reverseSort_log">                   
                    <td>{{log.time}}</td>
                    <td>{{log.activity}}</td>
               </tr>
               <tr class="no-data">
                    <td colspan="2"><?= lang('nothing_to_display') ?></td>
               </tr>
          </tbody>
     </table>
</div>
<div class="col-md-12 no-padding">
     <div class="col-md-4 no-padding">
          <div ng-show="logcount.total" style="line-height: 35px;">Showing {{logcount.start}} to {{logcount.end}} of {{logcount.total}} entries</div>
     </div> 
     <div class="col-md-8 text-right no-padding">

          <paging
               class="small"
               page="loglistdata.currentPage" 
               page-size="loglistdata.perpage_value" 
               total="loglistdata.total"
               adjacent="{{adjacent}}"
               dots="{{dots}}"
               scroll-top="{{scrollTop}}" 
               hide-if-empty="false"
               ul-class="{{ulClass}}"
               active-class="{{activeClass}}"
               disabled-class="{{disabledClass}}"
               show-prev-next="true"
               paging-action="getLog(page)">
          </paging> 
     </div>
</div>
