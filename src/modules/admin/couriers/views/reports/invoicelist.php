<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="col-lg-12 no-padding">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="col-md-12 no-padding margin_bottom_10 search_toolbar">

                                        <div class="pull-left no-padding">    
                                             <div  class="dataTables_length">
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="invoiceslistdata.perpage"  
                                                               ng-options="invoicesperpages as invoicesperpages.label for invoicesperpages in invoicesperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="pull-right no-padding">  
                                             <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;">
                                                  <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findinvoices()" aria-controls="invoices_list"  class="form-control input-sm mem_input"  type="search" ng-model="invoiceslistdata.filter">
                                                  </span>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="clr"></div>
                                   <div class="table-responsive">
                                        <table id="invoices_list"   class="table table-striped table-bordered table-hover ">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 20%">Date
                                                            <i ng-class="{'glyphicon glyphicon-sort':invoiceheaders.invoice_date.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': invoiceheaders.invoice_date.reverse == true, 'glyphicon glyphicon-sort-by-attributes': invoiceheaders.invoice_date.reverse == false}" class="pull-right" ng-click="sort('invoice_date')"></i>  
                                                       </th>
                                                       <th style="width: 20%">Customer
                                                            <i ng-class="{'glyphicon glyphicon-sort':invoiceheaders.customer.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': invoiceheaders.customer.reverse == true, 'glyphicon glyphicon-sort-by-attributes': invoiceheaders.customer.reverse == false}" class="pull-right" ng-click="sort('customer')"></i>  
                                                       </th>
                                                       <th style="width:20%">Invoicing Period
                                                            <i ng-class="{'glyphicon glyphicon-sort':invoiceheaders.period.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': invoiceheaders.period.reverse == true, 'glyphicon glyphicon-sort-by-attributes': invoiceheaders.period.reverse == false}" class="pull-right" ng-click="sort('period')"></i>   
                                                       </th>
                                                       <th style="width:20%">Download Link
                                                       </th>
                                                       <th><?= lang('action') ?></th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr ng-repeat="invoice in invoiceslist| orderBy:orderByField:reverseSort">
                                                       <td>
                                                            {{invoice.invoice_date}}
                                                       </td>
                                                       <td>{{invoice.customer}}</td>
                                                       <td>{{invoice.period}}</td>
                                                       <td>
                                                            <span ng-if="invoice.is_deleted == 0 && invoice.pdf == 1">                                                                 
                                                                 <a ng-href="{{invoice.pdflink}}"  class="label label-success" target="_blank">PDF</a>
                                                            </span>
                                                            <span ng-if="invoice.is_deleted == 0 && invoice.excel == 1">                                                                 
                                                                 <a ng-href="{{invoice.excellink}}" class="label label-success" target="_blank">Excel</a>
                                                            </span>
                                                            <span ng-if="invoice.is_deleted == 1" class="label label-danger">
                                                                 Deleted
                                                            </span>
                                                       </td>                                                      
                                                       <td>
                                                            <span class="btn btn-sm btn-default m-b-xs" ng-click="show_warning_popup(invoice.id)" ng-show="invoice.is_deleted == 0">Delete</span>
                                                       </td>
                                                  </tr>



                                             <div class="angular_popup popup_mid pull-right warning_box" ng-show="warning_popup"> 
                                                  <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_warning()"></i></h3>
                                                  <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                                  <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="invoice_delete()" style=""><?= lang('yes') ?></span>
                                                       <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_warning()"><?= lang('no') ?></span>
                                                  </div>
                                             </div>                                          
                                             <tr class="no-data">
                                                  <td colspan="8"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                             </tbody>
                                        </table>
                                   </div>
                                   <div class="col-lg-12 no-padding">
                                        <div class="col-md-4 no-padding">
                                             <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                        </div> 
                                        <div class="col-md-8 text-right no-padding">

                                             <paging
                                                  class="small"
                                                  page="invoiceslistdata.currentPage" 
                                                  page-size="invoiceslistdata.perpage_value" 
                                                  total="invoiceslistdata.total"
                                                  adjacent="{{adjacent}}"
                                                  dots="{{dots}}"
                                                  scroll-top="{{scrollTop}}" 
                                                  hide-if-empty="false"
                                                  ul-class="{{ulClass}}"
                                                  active-class="{{activeClass}}"
                                                  disabled-class="{{disabledClass}}"
                                                  show-prev-next="true"
                                                  paging-action="getinvoices(page)">
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