<div class="content">
     <div animate-panel>
          <div class="hpanel">
               <div class="panel-heading">

               </div>
               <div class="panel-body">

                    <div class="col-xs-12">
                         <div class="col-xs-12">    
                              <h3>Download Transaction Details</h3>
                         </div>
                    </div>
                    <div class="col-xs-12">                     
                         <form action="<?= site_url('payment/download') ?>" method="POST" class="inline_form">
                              <div class="col-xs-12 col-sm-4 col-md-4 m-b-md">
                                   <input type="text" name="id" class="hidden" value="<?= $account->id ?>">
                                   <div class="input-group">
                                        <input type="text" id="upreportdate_input" name="daterange" class="hidden" >
                                        <div id="upreportdate" class="form-control" ng-class="{error:errors.deliver_date}" >
                                             <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                             <span></span> 
                                        </div>                              
                                        <!--<span class="input-group-addon btn-primary" style="cursor: pointer;background-color: #34495e;border-color: #34495e;color: #FFFFFF;" ng-click="downloadForm()">Download</span>-->
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-4 m-b-md">
                                   <button type="submit" class="btn btn-primary"><?= lang('export_excel') ?></button>
                              </div>
                         </form>  
                         <script>
                              $(function () {
                                   setTimeout(function () {
                                        var init = moment().subtract(1, 'month').format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
                                        $('#upreportdate span').html(init);
                                        $("#upreportdate_input").val(init);
                                   }, 1000);

                                   $('#upreportdate').daterangepicker({
                                        timePicker: false,
                                        format: 'MM/DD/YYYY',
                                        startDate: moment().subtract(1, 'month'),
                                        endDate: moment(),
                                        minDate: moment().subtract(3, 'month'),
                                        maxDate: moment().format('MM/DD/YYYY'),
                                        timePickerIncrement: 30,
                                        timePicker12Hour: true,
                                        timePickerSeconds: false

                                   }, function (start, end, label) {
                                        var result = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
                                        $('#upreportdate span').html(result);
                                        $("#upreportdate_input").val(result);
                                   });
                                   $("#statuses").chosen({
                                        disable_search_threshold: 10,
                                        no_results_text: "Oops, nothing found!",
                                        width: "95%"
                                   });
                              });</script>
                    </div>
               </div>
          </div>
     </div>
</div>