<style>
     .order_img .dz-message {
          margin: 70px auto auto;
     }
</style>
<div class="col-xs-12" style="min-height: 400px;">    
     <div class="col-xs-12 no-padding">
          <div class="col-xs-12 col-sm-6 well">
               <h3><?= lang('new_pod_title') ?></h3>
               <div class="form-holder">
                    <form  class="form-horizontal col-xs-12" ng-submit="save_pod()">
                         <?php echo form_fieldset(); ?>
                         <div class="form-group">
                              <label class="control-label"><?= lang('pod_image') ?></label>
                              <input type="text" ng-model="pod.pod_image" id="uploaded_image" class="hidden">
                              <div class="order_img dropzone" id="pod_img_upload" style="margin: 0;position: relative;">

                              </div>
                              <span class="help-block m-b-none text-danger" ng-show="pod.errors.pod_image">{{pod.errors.pod_image}}</span>

                         </div>                    
                         <div class="form-group">
                              <label class="control-label"><?= lang('pod_remarks') ?></label>   
                              <textarea ng-model="pod.remark" class="form-control" rows="3"></textarea>
                         </div>
                         <div class="form-group">
                              <span style="float: left;margin:auto 10px;"> 
                                   <input ng-model="pod.signature" icheck type="checkbox">
                              </span><p><?= lang('pod_sign') ?></p>
                         </div>
                         <div class="form-group">
                              <div class="text-right">
                                   <button type="submit" class="btn btn-primary"><?php echo lang('save_btn'); ?></button>
                                   <button type="button" class="btn btn-default" ng-click="cancel_pod()"><?php echo lang('cancel_btn'); ?></button>
                              </div>
                         </div>
                         <?php echo form_fieldset_close(); ?>
                    </form>
               </div>
          </div>
          <div class="clearfix"></div>
          <div class="signature_block" ng-show="signature.pod_image_url">
               <h3>Signature</h3>
               <div class="pod_img_wrap">
                    <img ng-src="{{signature.pod_image_url}}">

               </div>
          </div>
     </div>
     <div class="col-xs-12" ng-show="pods.length > 0">

          <h3>Other Images</h3>

          <div class="pod_block" ng-repeat="pod in pods">
               <div class="pod_img_wrap">
                    <img src="{{pod.pod_image_url}}">

               </div>
          </div>                     
     </div>
</div>
<script>
     var myDropzone;
             function create_dropzone() {
             myDropzone = new Dropzone("#pod_img_upload", {url: BASE_URL + "couriers/assigned_orders/upload",
                     dictDefaultMessage: "<?= lang('pod_picture_info') ?>",
                     addRemoveLinks: true,
                     acceptedFiles: ".jpg,.jpeg,.png",
                     init: function () {
                     this.on("addedfile", function (file) {
                     if (myDropzone.files.length > 1)
                             myDropzone.removeFile(myDropzone.files[0]);
                     });
                             this.on("removedfile", function (file) {
//                             $("#uploaded_image").val("");
//                                     $("#uploaded_image").trigger('input');
//                                    // $.post('<?php echo site_url('couriers/assigned_orders/remove_photo'); ?>', {"image": file.uploaded_name});
                             });
                             this.on("success", function (response, result) {
                             if (JSON.parse(result).files !== undefined) {
                             myDropzone.files[myDropzone.files.length - 1].uploaded_name = JSON.parse(result).files;
                                     $("#uploaded_image").val(JSON.parse(result).files);
                                     $("#uploaded_image").trigger('input');
                             } else {
                             myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                             }
                             });
                     }
             });
             }
     $(document).ready(function () {
     create_dropzone();
     });
             function clean_dropzone() {
             myDropzone.destroy();
                     create_dropzone();
             }
</script>