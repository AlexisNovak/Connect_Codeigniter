<style>
     .dummy [class^="col-"]{
          overflow: auto;
     }
</style>
<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-md-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="row dummy">
                                   <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-md-12 no-padding">
                                             <div ng-hide="isEditing">
                                                  <div class="col-md-2 col-sm-3 text-left col-xs-5">
                                                       <div class="avatar">
                                                            <?php
                                                              if ($organisation->avatar == "" || !is_file(FCPATH . 'filebox/organisation/' . $organisation->avatar)) {
                                                                   $src = "resource/images/default_org.jpg";
                                                              } else {
                                                                   $src = 'filebox/organisation/' . $organisation->avatar;
                                                              }
                                                            ?>
                                                            <img id="org_profile_dp" src="<?= base_url($src) ?>" alt="avatar" class="img-circle m-b" ng-hide="isEditing">

                                                       </div> 



                                                       <div class="clr"></div>

                                                  </div> 
                                                  <div class="col-md-10 col-sm-9 col-xs-7">
                                                       <div class="org_current_details">
                                                            <?php if ($is_admin): ?>                                          
                                                                   <div class="org_edit_btn pull-right">
                                                                        <button type="button" class="btn btn-primary" ng-click="enableEditing()" id="edit_org_btn"><?= lang('edit_organisation') ?></button>
                                                                   </div>
                                                              <?php endif; ?>
                                                            <h2 class="text-capitalize">{{org.name}}</h2>
                                                            <p class="text-justify">{{org.description}}</p>
                                                       </div>

                                                  </div>
                                             </div>
                                             <div ng-show="isEditing">
                                                  <div class="text-right col-md-4 col-sm-5 col-xs-12 no-padding">
                                                       <div class="avatar">
                                                            <?php if ($is_admin): ?>
                                                                   <div class="org_img_wrap">
                                                                        <input type="text" ng-model="org.upload" id="uploaded_image" class="hidden">
                                                                        <div class="dropzone order_img" id="org_img_upload" style="height: 200px;width: 200px;">
                                                                        </div>
                                                                   </div> 

                                                              <?php endif; ?>
                                                       </div> 



                                                       <div class="clr"></div>

                                                  </div>

                                                  <div class="col-md-8 col-sm-7 col-xs-12">
                                                       <?php if ($is_admin): ?>
                                                              <div class="org_edit_form">
                                                                   <form name="editOrganisation" class="form-horizontal" ng-submit="editOrganisation.$valid && save()">

                                                                        <fieldset>
                                                                             <div class="form-group">
                                                                                  <label class="col-md-12 col-lg-5 edit_org"><?= lang('organisation_name') ?></label>
                                                                                  <div class="col-md-12 col-lg-7">
                                                                                       <input type="hidden" class="form-control" ng-model="org.id" required> 
                                                                                       <input type="text" class="form-control" ng-model="org.name" required> 
                                                                                       <span class="help-block m-b-none text-danger" ng-show="errors.name_error">{{errors.name_error}}</span>
                                                                                  </div>
                                                                             </div>
                                                                             <div class="form-group">
                                                                                  <label class="col-md-12 col-lg-5 edit_org"><?= lang('organisation_shortname') ?></label>
                                                                                  <div class="col-md-12 col-lg-7">
                                                                                       <input type="text" class="form-control" ng-model="org.shortname"> 
                                                                                       <span class="help-block m-b-none text-danger" ng-show="errors.shortname_error">{{errors.shortname_error}}</span>
                                                                                  </div>
                                                                             </div>
                                                                             <div class="form-group">
                                                                                  <label class="col-md-12 col-lg-5 edit_org"><?= lang('organisation_website') ?></label>
                                                                                  <div class="col-md-12 col-lg-7">
                                                                                       <input type="text" class="form-control" ng-model="org.website"> 
                                                                                       <span class="help-block m-b-none text-danger" ng-show="errors.website_error">{{errors.website_error}}</span>

                                                                                  </div>
                                                                             </div>
                                                                             <div class="form-group">
                                                                                  <label class="col-md-12 col-lg-5 edit_org"><?= lang('organisation_description') ?></label>
                                                                                  <div class="col-md-12 col-lg-7">
                                                                                       <textarea class="form-control" ng-model="org.description" rows="5"></textarea>
                                                                                       <span class="help-block m-b-none"></span>
                                                                                  </div>
                                                                             </div>                                                                             
                                                                             <div class="form-group">
                                                                                  <div class="col-sm-12 text-right btn-org">
                                                                                       <button type="submit" class="btn btn-primary" ng-class="{disabled:editOrganisation.$invalid}"><?= lang('edit_organisation_save') ?></button>
                                                                                       <button type="button" class="btn btn-default" ng-click="cancelEditing()"><?= lang('create_organisation_cancel') ?></button>
                                                                                  </div>
                                                                             </div>
                                                                        </fieldset>
                                                                   </form>
                                                              </div>                                              
                                                         <?php endif; ?>
                                                  </div>
                                             </div>

                                        </div>
                                   </div>

                              </div>
                              <div class="hr-line-dashed"></div>

                              <div class="row"> <!-- Nav tabs -->
                                   <div class="col-md-12">
                                        <ul class="nav nav-tabs org_tabs">
                                             <?php if ($is_member || $is_admin): ?>
                                                    <li ui-sref-active="active"><a ui-sref="organisation.members({id:<?= $organisation->id ?>,flag:0})"><?= lang('members_tab') ?></a></li>
                                               <?php endif; ?>
                                             <?php if ($is_admin): ?>

                                                                                                                                                                                                                                                                                                                                         <!--                                          <li ui-sref-active="active"><a ui-sref="organisation.settings({id:<?= $organisation->id ?>})"><?= lang('settings_tab') ?></a></li>-->
                                               <?php endif; ?>
                                             <?php if ($is_member || $is_admin): ?>
                                                                                                                                                                                                                                                                                                                                  <!--                                          <li ui-sref-active="active"><a ui-sref="organisation.activities({id:<?= $organisation->id ?>})"><?= lang('activity_tab') ?></a></li>-->
                                                    <li ui-sref-active="active"><a ui-sref="organisation.groups({id:<?= $organisation->id ?>,flag:0})"><?= lang('group_tab') ?></a></li>
                                               <?php endif; ?>
                                                    
                                             <!--                                             <li ui-sref-active="active">
                                                                                               <a ui-sref="organisation.orders({id:<?= $organisation->id ?>,flag:0})"><?= lang('order_tab') ?></a>
                                                                                          </li>-->
                                             <?php if ($is_admin): ?>
                                                    <li ui-sref-active="active" class="dropdown">
                                                         <a ui-sref="organisation.reports({id:<?= $organisation->id ?>,flag:0})" class="dropdown-toggle" data-toggle="dropdown"><?= lang('reports_tab') ?> <span class="caret"></span></a>
                                                         <ul class="dropdown-menu" role="menu">
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.reports.userperform({id:<?= $organisation->id ?>,flag:0})"><?= lang('user_performance') ?></a>
                                                              </li>
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.reports.groupperform({id:<?= $organisation->id ?>,flag:0})"><?= lang('group_performance') ?></a>
                                                              </li>                                           
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.reports.overall({id:<?= $organisation->id ?>,flag:0})"><?= lang('overall_performance') ?></a>
                                                              </li>
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.reports.export({id:<?= $organisation->id ?>,flag:0})"><?= lang('export_transaction') ?></a>
                                                              </li>
                                                         </ul>
                                                    </li>
                                               <?php endif; ?>
                                             <?php if ($is_admin): ?>
                                                    <li ng-class="{active:$state.includes('organisation.services') || $state.includes('organisation.pre_approved_bidders') || $state.includes('organisation.api') || $state.includes('organisation.tracking')}" class="dropdown">
                                                         <a class="dropdown-toggle" data-toggle="dropdown"><?= lang('advanced_tab') ?> <span class="caret"></span></a>

                                                         <ul class="dropdown-menu" role="menu">

                                                              <li ng-class="{active:$state.includes('organisation.services')}">
                                                                   <a ui-sref="organisation.services({id:<?= $organisation->id ?>,flag:0})"><?= lang('service_tab') ?></a>
                                                              </li>
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.pre_approved_bidders({id:<?= $organisation->id ?>})"><?= lang('pre_bidders_tab') ?></a>
                                                              </li>
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.api({id:<?= $organisation->id ?>,flag:0})"><?= lang('api_tab') ?></a>
                                                              </li>
                                                              <li ui-sref-active="active">
                                                                   <a ui-sref="organisation.tracking({id:<?= $organisation->id ?>,flag:0})"><?= lang('public_tracking') ?></a>
                                                              </li>
                                                         </ul>
                                                    </li>
                                                    <li ui-sref-active="active"><a ui-sref="organisation.payment_account({id:<?= $organisation->id ?>,flag:0})"><?= lang('manage_payment_account') ?></a></li>

                                               <?php endif; ?>
                                                    
                                                    
                                             <?php if ($is_member || $is_admin): ?>
                                                                                                                                                                                                                                                                                                                                  <!--                                          <li ui-sref-active="active"><a ui-sref="organisation.activities({id:<?= $organisation->id ?>})"><?= lang('activity_tab') ?></a></li>-->
                                                    <li ui-sref-active="active"><a ui-sref="organisation.parceltypes({id:<?= $organisation->id ?>,flag:0})">Parcel Types</a></li>
                                               <?php endif; ?>
                                                    
                                        </ul> 
                                        <div ui-view class="org-sub-pages"></div>
                                   </div>
                              </div>
                         </div>       
                    </div>
               </div>
          </div>
     </div>
</div>
<?php if ($is_admin): ?>

       <script type="text/javascript">
            var myDropzone = new Dropzone("#org_img_upload", {
                 url: BASE_URL + "app/organisation/uploaddp",
                 paramName: "file_upload",
                 dictDefaultMessage: "<?= lang('org_picture_info') ?>",
                 addRemoveLinks: true,
                 thumbnailWidth: 150,
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
                           $.post('<?php echo site_url('orders/remove_photo'); ?>', {"image": file.uploaded_name});
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
            $(function () {
                 $("#edit_org_btn").click(function () {
                      if (myDropzone.files.length > 0) {
                           myDropzone.removeAllFiles();
                      }
                      $.get(BASE_URL + "app/organisation/uploaddp/<?= $org_id ?>").success(function (data) {
                           var value = JSON.parse(data);
                           if (value != "") {
                                var mockFile = {name: value.name, size: value.size};
                                myDropzone.emit("addedfile", mockFile);
                                myDropzone.emit("thumbnail", mockFile, ROOT_PATH + "filebox/organisation/" + value.name);
                                myDropzone.emit("complete", mockFile);
                           }
                      });
                 });
            });

       </script>
  <?php endif; ?>