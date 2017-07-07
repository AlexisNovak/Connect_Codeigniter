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
          </table>
     </div>
</div>
<div class="clr"></div>

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