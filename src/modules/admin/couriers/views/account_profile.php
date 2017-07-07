
<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 " > 
                    <div class="col-lg-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                         <div class="col-lg-8  no-padding" style="background:#fff;">
                              <div class="hpanel">
                                   <div class="panel-body" style="border:none !important;"> 

                                        <form  class="form-horizontal" ng-submit="save()">
                                             <?php echo form_fieldset(); ?>

                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="profile_company_name"><?php echo lang('profile_company_name'); ?></label>

                                                  <div class="col-sm-6">
                                                       <?php echo form_input(array('class' => "form-control", 'ng-model' => 'courier.profile_company_name', 'id' => 'profile_company_name', 'value' => set_value('profile_company_name') ? set_value('profile_company_name') : (isset($account->company_name) ? $account->company_name : ''), 'maxlength' => '24')); ?>
                                                       <span class="help-inline" ng-show="errors.profile_company_name_error">
                                                            {{errors.profile_company_name_error}}
                                                       </span>
                                                  </div>
                                             </div>

                                             <div class="form-group">
                                                  <label class="col-sm-3 control-label" for="profile_logo"><?php echo lang('profile_logo'); ?></label>

                                                  <div class="col-sm-6">

                                                       <?php
                                                         if (isset($profile_logo_error)) {
                                                              ?>
                                                              <span class="help-inline text-danger">
                                                                   <?php echo $profile_logo_error; ?>
                                                              </span>
                                                         <?php } ?>
                                                       <span class="help-inline" ng-show="errors.profile_logo_error">
                                                            {{errors.profile_logo_error}}
                                                       </span>
                                                       <input type="text" ng-model="courier.profile_logo" id="uploaded_image" class="hidden">
                                                       <div class="order_img dropzone" id="order_img_upload">
                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="form-group">
                                                  <div class="col-sm-9 text-right">
                                                       <button type="submit" class="btn btn-primary"><?php echo lang('profile_save'); ?></button>
                                                  </div>
                                             </div>
                                             <?php echo form_fieldset_close(); ?>
                                        </form>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
<script>
     var myDropzone = new Dropzone("#order_img_upload", {
          url: BASE_URL + "couriers/courierAccount/upload",
          dictDefaultMessage: "<?= lang('couriers_logo_info') ?>",
          addRemoveLinks: true,
          acceptedFiles: ".jpg,.jpeg,.png,.gif",
          init: function () {
               this.on("addedfile", function (file) {
                    if (myDropzone.files.length === 0) {
                         myDropzone.files[0] = file;
                    }
                    if (myDropzone.files.length > 1)
                         myDropzone.removeFile(myDropzone.files[0]);
               });
               this.on("removedfile", function (file) {
                    $("#uploaded_image").val("");
                    $("#uploaded_image").trigger('input');
               });
               this.on("success", function (response, result) {
                    if (JSON.parse(result).files !== undefined) {
                         $("#uploaded_image").val(JSON.parse(result).files);
                         $("#uploaded_image").trigger('input');
                    } else {
                         myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                    }
               });
               $.get(BASE_URL + "couriers/courierAccount/upload").success(function (data) {
                    var value = JSON.parse(data);
                    if (value != "") {
                         var mockFile = {name: value.name, size: value.size};
                         myDropzone.emit("addedfile", mockFile);
                         myDropzone.emit("thumbnail", mockFile, value.name);
                         myDropzone.emit("complete", mockFile);
                    }
               });
          }
     });
</script>
