<div id="userperform">
     <div class="panel panel-default">
          <div class="panel-heading">
               <h3><?= lang('export_transaction_title') ?></h3>
          </div>
          <div class="panel-body">

               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12 no-padding">                        
                         <form action="<?= site_url('reports/export_transactions/get_export_transactions_excel') ?>" method="post" class="inline_form">
                              <div class="col-xs-12 col-sm-5 col-md-4 m-b-md">
                                   <input type="text" name="org_id" class="hidden" value="<?= $org_id ?>">
                                   <input type="text" id="upreportdate_input" name="date" class="hidden">
                                   <div id="upreportdate" class="form-control">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <span></span> 
                                   </div>
                              </div>
                              <div class="col-xs-12 col-sm-4 m-b-md">
                                   <select name="status[]" class="form-control" id="statuses" data-placeholder="Statuses" multiple>
                                        <?php
                                          foreach ($statuses as $stat) {
                                               ?>
                                               <option value="<?= $stat->status_id ?>" <?= ($stat->status_id == C_DELIVERED) ? "selected" : "" ?>><?= $stat->display_name ?></option>
                                               <?php
                                          }
                                        ?>
                                   </select>
                              </div>
                              <div class="col-xs-12 col-sm-3 m-b-md">
                                   <button type="submit" class="btn btn-primary"><?= lang('export_excel') ?></button>
                              </div>
                         </form>            
                    </div>
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
