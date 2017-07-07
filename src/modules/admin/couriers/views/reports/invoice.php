<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-md-12">
                    <div class="hpanel">
                         <div class="panel-body"> 
                              <div class="col-lg-12 no-padding">     
                                   <legend>Generate Invoice</legend>
                                   <form action="<?= site_url('couriers/reports/generate_invoice') ?>" method="post" class="form-horizontal">
                                        <div class="col-xs-12">
                                             <div class="form-group">
                                                  <label>Date</label>
                                                  <div class="clearfix"></div>
                                                  <div class="col-xs-12 col-md-6 no-padding">
                                                       <input type="text" id="upreportdate_input" name="date" class="hidden">
                                                       <div id="upreportdate" class="form-control">
                                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                            <span></span> 
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12">
                                             <div class="form-group">
                                                  <label class="control-label">Status</label>
                                                  <div class="clearfix"></div>
                                                  <div class="col-xs-12 col-md-6 no-padding">
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
                                             </div>
                                        </div>
                                        <div class="col-xs-12">
                                             <div class="form-group">
                                                  <div class="col-xs-12 col-md-6 no-padding">
                                                       <label class="control-label">Customer Type</label>
                                                       <div class="clearfix"></div>
                                                       <label class="checkbox-inline" style="padding-left: 0;"><input type="radio" name="category" value="1" class="i-checks"> Users </label>
                                                       <label class="checkbox-inline"><input type="radio" name="category" value="2" class="i-checks"> Organisation</label>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12" id="userslist" style="display: none;">
                                             <div class="form-group">
                                                  <label class="control-label">Customers</label>
                                                  <div class="clearfix"></div>
                                                  <div class="col-xs-12 col-md-6 no-padding">
                                                       <select name="customers[]" class="form-control" id="customers" data-placeholder="Customers" multiple>
                                                            <?php
                                                              foreach ($users as $user) {
                                                                   ?>
                                                                   <option value="<?= $user->id ?>"><?= $user->email ?></option>
                                                                   <?php
                                                              }
                                                            ?>
                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12" id="orglist" style="display: none;">
                                             <div class="form-group">
                                                  <label class="control-label">Organisations</label>
                                                  <div class="clearfix"></div>
                                                  <div class="col-xs-12 col-md-6 no-padding">
                                                       <select name="org[]" class="form-control" id="organisations" data-placeholder="Organisation" multiple>
                                                            <?php
                                                              foreach ($organisations as $org) {
                                                                   ?>
                                                                   <option value="<?= $org->id ?>"><?= $org->name ?></option>
                                                                   <?php
                                                              }
                                                            ?>
                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12">
                                             <div class="form-group">
                                                  <div class="col-xs-12 col-md-6 no-padding">
                                                       <label class="control-label">Format</label>
                                                       <div class="clearfix"></div>
                                                       <label class="checkbox-inline" style="padding-left: 0;"><input type="checkbox" name="file_type[]" value="excel" class="i-checks"> <?= lang('export_excel') ?></label>
                                                       <label class="checkbox-inline"><input type="checkbox" name="file_type[]" value="pdf" class="i-checks"> <?= lang('export_pdf') ?></label>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 text-right no-padding">
                                             <button type="submit" class="btn btn-primary"><?= lang('submit_btn') ?></button>
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
                                             maxDate: moment().format('MM/DD/YYYY'),
                                             timePickerIncrement: 30,
                                             timePicker12Hour: true,
                                             timePickerSeconds: false

                                        }, function (start, end, label) {
                                             var result = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
                                             $('#upreportdate span').html(result);
                                             $("#upreportdate_input").val(result);
                                        });
                                        $("#statuses,#customers,#organisations").chosen({
                                             disable_search_threshold: 10,
                                             no_results_text: "Oops, nothing found!",
                                             width: "100%"
                                        });
                                        $('.i-checks').iCheck({
                                             checkboxClass: 'icheckbox_square-green',
                                             radioClass: 'iradio_square-green',
                                        });

                                        $('input[type=radio][name=category]').on('ifChanged', function () {
                                             if (this.value === '1') {
                                                  $("#userslist").show();
                                                  $("#orglist").hide();
                                             }
                                             else if (this.value === '2') {
                                                  $("#userslist").hide();
                                                  $("#orglist").show();
                                             }
                                        });
                                   });</script>
                         </div>
                    </div>
               </div>
          </div>

     </div>
</div>
</div>
