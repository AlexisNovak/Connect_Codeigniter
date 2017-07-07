<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-sm-12">
                    <div class="hpanel">
                         <div class="panel-body">                              
                              <div class="col-sm-12 no-padding margin_bottom_10">  
                                   <div class="form-holder order_form" id="new_order_form_holder">
                                        <div class="clearfix margin_bottom_10" ng-show="processing">
                                             <p class="text-success text-center"> <?= lang('order_processing') ?> 
                                                  <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
                                             </p>
                                        </div>
                                        <div id="order-part-1" ng-show="show_part.one">
                                             <!--collection and delivery details start-->
                                             <?php $this->load->view('order/mpart1') ?>
                                             <!--collection and delivery details end-->
                                        </div>
                                        <div class="clearfix"></div>

                                        <div id="order-part-2" ng-show="show_part.two">
                                             <!--delivery details starts-->
                                             <?php $this->load->view('order/mpart2') ?>
                                             <!--delivery details ends-->
                                        </div>
                                        <div class="clearfix"></div>

                                        <div id="order-part-3" ng-show="show_part.three">
                                             <!--organisation and service details starts-->
                                             <?php $this->load->view('order/mpart3') ?>
                                             <!--organisation and service details ends-->
                                        </div>
                                        <div id="order-part-4" ng-show="show_part.four">
                                             <!--review starts-->
                                             <?php $this->load->view('order/mpart4') ?>
                                             <!--review ends-->
                                        </div>
                                        <div id="preview-template" style="display: none;">
                                             <div class="dz-preview dz-file-preview">
                                                  <div class="dz-details">
                                                       <div class="up_suc_msg up_status">Upload Success!</div>
                                                       <div class="up_err_msg up_status">Upload Failed!</div>
                                                       <div class="up_fname">Filename<br><span data-dz-name></span></div>
                                                       <div class="up_err_msg"><span data-dz-errormessage></span></div>
                                                       <img data-dz-thumbnail />
                                                       <div class="up_suc"><span> <img src="<?= base_url('resource/images/upload_suc.png') ?>"></span></div>
                                                       <div class="up_err"><span></span></div>
                                                  </div>
                                                  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>


                                             </div>
                                        </div>
                                   </div>
                                   <script>

                                        $(function () {
                                             $('button[type=submit]').hide();
                                             $('body').append('<div id="preview_box_lg_wrap"><div id="preview_box_lg"><div class="close_preview pull-right"><i class="glyphicon glyphicon-remove"></i></div>          <img src="" alt="preview"></div></div>')
                                             $('.o-datepicker').daterangepicker({
                                                  timePicker: true,
                                                  singleDatePicker: true,
                                                  format: 'MM/DD/YYYY h:mm A',
                                                  minDate: new Date(),
                                                  timePickerIncrement: 30,
                                                  timePicker12Hour: true,
                                                  timePickerSeconds: false
                                             });
                                             var myDropzone = new Dropzone("#order_template", {
                                                  url: BASE_URL + "orders/multiorders/upload_and_process",
                                                  dictDefaultMessage: "<?= lang('order_file_info_a') ?><br><span class='sub'><?= lang('order_file_info_a_sub') ?></span>",
                                                  createImageThumbnails: false,
                                                  acceptedFiles: ".xlsx",
                                                  previewTemplate: $("#preview-template").html(),
                                                  init: function () {
                                                       this.on("addedfile", function (file) {
                                                            $("#order_template").addClass('active');
                                                            if (myDropzone.files.length > 1) {
                                                                 myDropzone.removeFile(myDropzone.files[0]);
                                                            }

                                                       });
                                                       this.on("dragenter", function () {
                                                            $("#order_template .dz-message span").html("<?= lang('drag_enter_info') ?>");
                                                            $("#order_template").addClass('active');

                                                       });
                                                       this.on("drop", function () {
                                                            $("#order_template .dz-message span").html("<?= lang('order_file_info_a') ?><br><span class='sub'><?= lang('order_file_info_a_sub') ?></span>");
                                                            $('button[type=submit]').hide();

                                                       });
                                                       this.on("removedfile", function (file) {
                                                            $("#uploaded_image").val("");
                                                            $("#uploaded_image").trigger('input');
                                                       });
                                                       this.on('sending', function (file, xhr, formData) {
                                                            formData.append('type', $("#template_type").val());
                                                       });
                                                       this.on("success", function (response, result) {
                                                            var rs = JSON.parse(result);
                                                            if (rs.files !== undefined) {
                                                                 $("#uploaded_file_link").attr('href', "<?= base_url() ?>" + 'filebox/massconsignment/' + rs.files);
                                                                 $("#uploaded_file_link").text(rs.files);
                                                                 $('button[type=submit]').show();
                                                                 $("#uploaded_image").val(rs.files);
                                                                 $("#uploaded_image").trigger('input');
                                                                 $("#upload_err").html("");
                                                                 $("#upload_err").hide();
                                                                 if (rs.template != undefined) {
                                                                      $("#verify_block .v_custom .count").text(rs.template);
                                                                      $("#verify_block .v_custom").show();
                                                                 }
                                                                 else {
                                                                      $("#verify_block .v_custom").hide();
                                                                 }
                                                                 if (rs.total != undefined) {
                                                                      $("#verify_block .v_suc .count").text(rs.total);
                                                                      $("#verify_block .v_suc span").remove();
                                                                      if (rs.exceed !== undefined && rs.exceed === 1) {
                                                                           $("#verify_block .v_suc").append('<span><?= lang('delveries_exceed') ?></span>');
                                                                      }
                                                                      $("#verify_block .v_suc").show();
                                                                      $("#upload_result").html(rs.error_free);
                                                                 }
                                                                 else {
                                                                      $("#verify_block .v_suc").hide();
                                                                 }
                                                                 if (rs.err_rec != undefined && rs.err_rec > 0) {

                                                                      $("#verify_block .v_err .count").text(rs.err_rec);
                                                                      $("#verify_block .v_err .link").html(rs.link);
                                                                      $("#verify_block .v_err").show();
                                                                 } else if (rs.total > 0) {
                                                                      $("#verify_block .v_err").hide();
                                                                      $('button[type=submit]').show();
                                                                 }
                                                                 $("#verify_block").show();
                                                            } else {
                                                                 $("#verify_block").hide();
                                                                 myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                                                                 if (rs.error) {
                                                                      $("#upload_err").html(rs.error);
                                                                      $("#upload_err").show();
                                                                 }
                                                            }
                                                       });
                                                       this.on("error", function (response, result) {
                                                            $('button[type=submit]').hide();
                                                       });
                                                  }
                                             });
                                        });

                                   </script>                                         
                              </div>
                              <div class="clear"></div>
                         </div>
                    </div>
                    <?php $this->load->view('order/contactpopup') ?>
               </div>

          </div>
     </div>
</div>

