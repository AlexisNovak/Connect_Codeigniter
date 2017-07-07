<?php if (isset($partner)) { ?><div class="content">
            <div animate-panel>
                 <div class="row">
                      <div class="col-sm-12">
                           <div class="col-sm-12  no-padding" style="background:#fff;border: 1px solid #E4E5E7;">
                                <div class="hpanel">
                                     <div class="panel-body" style="border:none !important;">
                                          <div class="col-sm-6 no-padding" style="background:#fff;">
                                               <form class="form-horizontal" ng-submit="save()">
                                                    <div class="form-group">
                                                         <label class=" col-sm-3 control-label" ><?= lang('partner_name') ?></label>
                                                         <div class="col-sm-9">
                                                              <input type="text" class="form-control" ng-model="partner.p_name" required>    
                                                              <span class="help-inline text-danger" ng-show="errors.p_name_error">
                                                                   {{errors.p_name_error}}
                                                              </span>
                                                         </div>
                                                    </div>
                                                    <div class="form-group">
                                                         <label class=" col-sm-3 control-label" ><?= lang('partner_url') ?></label>
                                                         <div class="col-sm-9">
                                                              <input type="text" class="form-control" ng-model="partner.p_url" required>    
                                                              <span class="help-inline text-danger" ng-show="errors.p_url_error">
                                                                   {{errors.p_url_error}}
                                                              </span>
                                                              <p><?= lang('partner_url_info') ?></p>
                                                         </div>
                                                    </div>
                                                    <div class="form-group">
                                                         <label class=" col-sm-3 control-label" ><?= lang('partner_shortname') ?></label>
                                                         <div class="col-sm-9">
                                                              <input type="text" class="form-control" ng-model="partner.p_shortname" ng-disabled="partner.partner_id" required>    
                                                              <span class="help-inline text-danger" ng-show="errors.p_shortname_error">
                                                                   {{errors.p_shortname_error}}
                                                              </span>
                                                         </div>
                                                    </div>
                                                    <div class="form-group">
                                                         <label class=" col-sm-3 control-label" ><?= lang('partner_domain') ?></label>
                                                         <div class="col-sm-9">
                                                              <select class="form-control" ng-model="partner.p_domain" required> 
                                                                   <option value="http">HTTP only</option>
                                                                   <option value="https">HTTPS only</option>
                                                                   <option value="both">Both HTTP & HTTPS</option>
                                                              </select>   
                                                              <span class="help-inline text-danger" ng-show="errors.p_domain_error">
                                                                   {{errors.p_domain_error}}
                                                              </span>
                                                         </div>
                                                    </div>
                                                    <div class="form-group">
                                                         <label class=" col-sm-3 control-label" ><?= lang('partner_color') ?></label>
                                                         <div class="col-sm-9">
                                                              <p ng-show="partner.p_color_path" style="margin-bottom: 20px;">
                                                                   <a ng-href="{{partner.p_color_path}}" target="_blank">{{partner.p_color_name}}</a> 
                                                                   <a ng-href="{{partner.p_download_path}}" style="margin-left: 5px" title="Download css" data-toggle="tooltip" data-placement="top">
                                                                        <i class="glyphicon glyphicon-download-alt text-info"></i>
                                                                   </a>
                                                                   <span ng-click="show_remove_warning()" class="btn btn-sm btn-default pull-right">Reset to default</span>
                                                              </p>
                                                              <input type="text" class="form-control hidden" id="color_scheme" ng-model="partner.p_color">    
                                                              <div id="color_css" class="dropzone"></div>
                                                              <span class="help-inline text-danger" ng-show="errors.p_color_error">
                                                                   {{errors.p_color_error}}
                                                              </span>
                                                         </div>
                                                    </div>
                                                    <div class="form-group text-right">
                                                         <div class="col-sm-9 col-sm-offset-3">
                                                              <span ng-hide="partner.p_color_path" style="font-size: 14px;line-height: 34px;font-weight: 600;" class="pull-left">
                                                                   <a ng-href="{{partner.default_css}}" style="color:#706DC4;" title="Download CSS template" data-toggle="tooltip" data-placement="top">
                                                                        Download CSS template
                                                                   </a>
                                                              </span> 
                                                              <button type="submit" class="btn btn-primary"><?php echo lang('save_btn'); ?></button>
                                                              <button type="button" class="btn btn-small" ng-click="goback()"><?= lang('cancel_btn') ?></button>
                                                         </div>
                                                    </div>
                                               </form>
                                          </div>
                                          <div class="angular_popup popup_sm pull-right warning_box" ng-show="remove_css_popup"> 
                                               <h3><?= lang('warning') ?><i class="fa fa-close pull-right" ng-click="cancel_remove_warning()"></i></h3>
                                               <p style="text-align: center;"><?= lang('delete_confirm') ?></p>
                                               <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="remove_css()" style=""><?= lang('yes') ?></span>
                                                    <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_remove_warning()"><?= lang('no') ?></span>
                                               </div>
                                          </div>
                                          <div class="col-sm-6" ng-if="partner_exist">
                                               <div id="embedded" class="embed_wrap">
                                                    <span class="copy_btn btn btn-primary pull-right" clip-copy="getTextToCopy()"  clip-click="notify_selection()" title="copy">
                                                         copy snippet
                                                    </span>
                                                    <p>Embedded Code</p>
                                                    <div class="pre">

                                                    </div>
                                               </div>
                                               <div id="embedded2" class="embed_wrap" ng-if="partner.sec">
                                                    <span class="copy_btn btn btn-primary pull-right" clip-copy="getTextToCopy2()"  clip-click="notify_selection()" title="copy">
                                                         copy snippet
                                                    </span>
                                                    <p>Embedded Code</p>
                                                    <div class="pre">

                                                    </div>
                                               </div>
                                          </div>
                                     </div>
                                </div>
                           </div>
                      </div>
                 </div>
            </div>
       </div>
       <script>
            $(function () {
                 $("[data-toggle=tooltip]").tooltip();
                 var myDropzone = new Dropzone("#color_css", {
                      url: BASE_URL + "partner_management/upload_file",
                      dictDefaultMessage: "<?= lang('partner_color_info_edit') ?>",
                      addRemoveLinks: true,
                      acceptedFiles: ".css",
                      init: function () {
                           this.on("addedfile", function (file) {
                                if (myDropzone.files.length > 1) {
                                     myDropzone.removeFile(myDropzone.files[0]);
                                }
                           });
                           this.on("removedfile", function (file) {
                                $("#color_scheme").val("");
                                $("#color_scheme").trigger('input');
                           });
                           this.on("success", function (response, result) {
                                if (JSON.parse(result).files !== undefined) {
                                     $("#color_scheme").val(JSON.parse(result).files);
                                     $("#color_scheme").trigger('input');
                                } else {
                                     myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                                }
                           });
                      }
                 });
            });
       </script>
  <?php } else {
       ?>
       <script>
            window.location = "<?php echo site_url(); ?>";
       </script>
  <?php }
?>