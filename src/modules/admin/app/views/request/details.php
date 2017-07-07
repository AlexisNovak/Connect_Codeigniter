<div class="col-lg-12">
     <div class="table-responsive">
          <table class="table request_detail">
               <tr>
                    <td class="item_head"><?= lang('srequest_title') ?></td>
                    <td>  <?php echo $request->title; ?></td>
               </tr>
<!--               <tr>
                    <td class="item_head"><?= lang('srequest_type') ?></td>
                    <td>  <?php echo $request->type; ?></td>
               </tr>-->
               <tr>
                    <td class="item_head"><?= lang('srequest_description') ?></td>
                    <td>  <?php echo $request->description; ?></td>
               </tr>
               <tr>
                    <td class="item_head"><?= lang('srequest_duration') ?></td>
                    <td>  <?php echo $request->duration; ?> <?= lang('months') ?></td>
               </tr>
               <tr>
                    <td class="item_head"><?= lang('srequest_delpermonth') ?></td>
                    <td>  <?php echo $request->delivery_p_m; ?> <?= lang('srequest_delpermonth_sub') ?></td>
               </tr>
<!--               <tr>
                    <td class="item_head"><?= lang('srequest_price') ?></td>
                    <td>  <?php echo $request->price; ?></td>
               </tr>-->

               <tr>
                    <td class="item_head"><?= lang('srequest_payment') ?></td>
                    <td>  <?php echo $request->payment; ?></td>
               </tr>
               <tr>
                    <td class="item_head"><?= lang('srequest_compensation') ?></td>
                    <td>  <?php echo $request->other_conditions; ?></td>
               </tr>            
               <tr>
                    <td class="item_head"><?= lang('srequest_expiry') ?></td>
                    <td>  <?php echo date('d-m-Y H:i A', strtotime($request->expiry)); ?></td>
               </tr>
               <tr>
                    <td class="item_head"><?= lang('srequest_added_on') ?></td>
                    <td>  <?php echo date('d-m-Y', strtotime($request->added_on)); ?></td>
               </tr>
               <tr>
                    <td class="item_head"><?= lang('srequest_uploads') ?></td>
                    <td>  
                         <?php foreach ($request->uploads as $upload) {
                                ?> <p><a href="<?= $upload['path'] ?>" target="_blank"><?= $upload['name'] ?></a></p>
                           <?php } ?>
                    </td>
               </tr>
          </table>
     </div>
</div>
<div class="col-sm-12 no-padding margin_top_10" ng-if="<?php echo ($is_admin && $request->status == '1' ? 'true' : 'false'); ?>">
     <span class="pull-right">
          <button type="submit" class="btn btn-primary btn-sm" ng-click="change_request()">
               <?= lang('edit_btn') ?>
          </button>
     </span>
</div>
<div class="clr"></div>
<div class="angular_popup pull-right warning_box popup_mid" ng-show="save_request_popup"> 
     <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_request_change()"></i></h3>
     <p style="text-align: center;padding: 10px;"><?= lang('service_request_edit') ?></p>
     <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="edit_request_details(<?php echo $request->req_id ?>)" ><?= lang('yes') ?></span>
          <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_request_change()"><?= lang('no') ?></span>
     </div>
</div>

<!--start panel--> 
<!--<div class="panel panel-info">
     <div class="panel-heading">
<?php echo $request->title; ?>
     </div>
     <div class="panel-body">

          <div class="row service_detail">
               <div class="col-lg-8">
                    <strong><?= lang('srequest_duration') ?></strong>
                    <p>  <?php echo $request->duration; ?></p>
                    <strong><?= lang('srequest_price') ?></strong>
                    <p>  <?php echo $request->price; ?></p>
                    <strong><?= lang('srequest_duration') ?></strong>
                    <p>  <?php echo $request->duration; ?></p>
                    <strong><?= lang('srequest_payment') ?></strong>
                    <p>  <?php echo $request->payment; ?></p>
                    <strong><?= lang('srequest_other_conditions') ?></strong>
                    <p>  <?php echo $request->other_conditions; ?></p>
                    <strong><?= lang('srequest_delpermonth') ?></strong>
                    <p>  <?php echo $request->delivery_p_m; ?></p>
                    <strong><?= lang('srequest_description') ?></strong>
                    <p>  <?php echo $request->description; ?></p>
                    <strong><?= lang('srequest_added_on') ?></strong>
                    <p>  <?php echo date('d-m-Y', strtotime($request->added_on)); ?></p>
        </div>
   </div>
</div>
</div>-->
<!--end panel-->