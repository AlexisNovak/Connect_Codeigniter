<style>
    .order_img .dz-message {
        margin-top: 15%;}
    </style>
    <div class="content padding_0">
    <div  animate-panel>
        <div class="row">
            <div class="col-lg-12 no-padding" ng-controller="publicTrackingCtrl">
                <div class="col-lg-12">
                    <div class="hpanel dark">
                        <?php if ($is_admin): ?>
                            <div class="alert alert-custom" style="color: #222;">
                                <span style="float: left;margin:10px;"> 
                                    <input ng-model="enable_tracking" icheck type="checkbox" ng-change="show_enable_confirm()">
                                </span>  
                                <p style="font-weight: 600;"><?= lang('enable_tracking_info') ?></p>
                                <p><?= lang('enable_tracking_info_sub') ?></p>                                          
                            </div>
                        <?php endif; ?> 
                        <div class="angular_popup warning_box add_member" ng-show="show_confirm_popup" style="margin: 0;left: 20%;top: 0;"> 
                            <h3><?= lang('confirm') ?><i class="fa fa-close pull-right" ng-click="cancel_enable_confirm()"></i></h3>
                            <p class="text-center p-sm" ng-if="enable_tracking"><?= lang('enable_tracking_confirm') ?></p>
                            <p class="text-center p-sm" ng-if="!enable_tracking"><?= lang('disable_tracking_confirm') ?></p>
                            <div class="btn-holder"><span class="btn btn-info btn-sm margin_10" ng-click="proceed()" style=""><?= lang('yes') ?></span>
                                <span class="btn btn-primary btn-sm margin_10" ng-click="cancel_enable_confirm()"><?= lang('no') ?></span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-12 no-padding" ng-show="tracking_url"> 
                                <p class="api_sub_title"><?= lang('tracking_url') ?></p>
                                <p class="api_wraper">{{tracking_url}}</p>
                                <div class="clearfix"></div>
                                <div class="col-md-6 col-sm-12 no-padding">
                                    <div class="form-group">
                                        <p class="api_sub_title"><?= lang('tracking_intro') ?></p>
                                        <textarea class="form-control" rows="4" ng-model="tracking.info"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <p class="api_sub_title"><?= lang('tracking_logo') ?></p>
                                        <input type="text" ng-model="tracking.logo" id="traking_image" class="hidden">
                                        <div class="order_img dropzone" id="order_img_upload" style="margin-right: 0;">
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <button class="btn btn-sm btn-primary" ng-click="add_tracking_info()"><?= lang('save_btn') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                            var myDropzone = new Dropzone("#order_img_upload", {
                                url: BASE_URL + "app/organisation/upload_tracking_logo/<?= $org_id ?>",
                                dictDefaultMessage: "<?= lang('couriers_logo_info') ?>",
                                addRemoveLinks: true,
                                paramName: "file_upload",
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
                                        $("#traking_image").val("");
                                        $("#traking_image").trigger('input');
                                    });
                                    this.on("success", function (response, result) {
                                        if (JSON.parse(result).files !== undefined) {
                                            $("#traking_image").val(JSON.parse(result).files);
                                            $("#traking_image").trigger('input');
                                        } else {
                                            myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                                        }
                                    });
                                    $.get(BASE_URL + "app/organisation/upload_tracking_logo/<?= $org_id ?>").success(function (data) {
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
            </div>
        </div>
    </div>
</div>
