<form name="new_order_p2" class="form-horizontal" ng-submit="new_order_p2.$valid && show_part_three()">
     <h3 class="order_title"><?= lang("multi_order_title") ?>
          <span class="pull-right">
               <span class="btn-cancel" ng-click="show_part_one()">
                    <?= lang('back_btn') ?>                                                  
               </span>  
               <button type="submit" class="btn btn-primary btn-sm">
                    <?= lang('next') ?>                                                  
               </button>         
          </span>
     </h3>
     <legend>B. <?= lang('order_caption_d_confirm') ?></legend>
     <div  class="col-sm-12 no-padding">
          <div class="cfm_box cfm_box_d">
               <div class="cfm_sub_h text-center"><?= lang('delivery_to_h') ?></div>
               <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                         <thead>
                              <tr>
                                   <th>Parcel Type</th>
                                   <th>Alt. track numbers</th>
                                   <th>Remarks</th>
                                   <th>Tags</th>
                                   <th>Length</th>
                                   <th>Width</th>
                                   <th>Height</th>
                                   <th>Weight</th>
                                   <th>Address 1</th>
                                   <th>Address 2</th>
                                   <th>Country</th>
                                   <th>Postal Code</th>
                                   <th>Restricted</th>
                                   <th>Date (From)</th>
                                   <th>Date (To)</th>
                                   <th>Time (From)</th>
                                   <th>Time (To)</th>
                                   <th>Name</th>
                                   <th>Phone</th>
                                   <th>Email</th>
                                   <th>Company Name</th>
                                   <th>Email Notification</th>
                              </tr>
                         </thead>
                         <tbody id="upload_result">

                         </tbody>
                    </table>
               </div>
          </div>
     </div>
     <div class="col-sm-12 no-padding margin_top_10">
          <h3 class="order_title">
               <span class="pull-right">
                    <span class="btn-cancel" ng-click="show_part_one()">
                         <?= lang('back_btn') ?>                                                  
                    </span>  
                    <button type="submit" class="btn btn-primary btn-sm">
                         <?= lang('next') ?>                                                  
                    </button>         
               </span>
          </h3>
     </div>
</form>