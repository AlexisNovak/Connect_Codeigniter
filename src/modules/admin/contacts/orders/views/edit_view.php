<div class="content" ng-class="{'padding_0':$state.current.name === 'organisation.orders.edit_order'}">
     <div  animate-panel>
          <div class="row" >
               <div class="col-sm-12" ng-class="{'padding_0':$state.current.name === 'organisation.orders.edit_order'}">


                    <div class="hpanel">
                         <div class="panel-body">                              
                              <div class="col-sm-12 no-padding margin_bottom_10">  
                                   <div class="form-holder order_form">
                                        <div class="clearfix margin_bottom_10" ng-show="processing">
                                             <p class="text-success text-center"> <?= lang('order_processing') ?> 
                                                  <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">

                                        </div>
                                        <div id="order-part-1" ng-show="show_part.one">
                                             <!--collection and delivery details start-->
                                             <?php $this->load->view('order/edit_part1') ?>
                                             <!--collection and delivery details end-->
                                        </div>
                                        <div class="clearfix"></div>

                                        <div id="order-part-2" ng-show="show_part.two">
                                             <!--organisation and service details starts-->
                                             <?php $this->load->view('order/edit_part2') ?>
                                             <!--organisation and service details ends-->
                                        </div>
                                        <div class="clearfix"></div>

                                        <div id="order-part-3" ng-show="show_part.three">
                                             <!--review starts-->
                                             <?php $this->load->view('order/edit_part3') ?>
                                             <!--review ends-->
                                        </div>

                                   </div>
                                   <script>
                                        $(function () {
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
                                             var myDropzone = new Dropzone("#order_img_upload", {
                                                  url: BASE_URL + "orders/upload",
                                                  dictDefaultMessage: "<?= lang('order_picture_info') ?>",
                                                  addRemoveLinks: true,
                                                  acceptedFiles: ".jpg,.jpeg,.png,.gif",
                                                  init: function () {
                                                       this.on("addedfile", function (file) {
                                                            $(".previous_upload").css("background-size", "0%");
                                                            if (myDropzone.files.length > 1)
                                                                 myDropzone.removeFile(myDropzone.files[0]);

                                                       });
                                                       this.on("removedfile", function (file) {
                                                            $("#uploaded_image").val("");
                                                            $("#uploaded_image").trigger('input');
                                                            if (myDropzone.files.length === 0) {
                                                                 $(".previous_upload").css("background-size", "contain");
                                                            }
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
                                             $(".dropzone").on('click', '.dz-preview', function () {
                                                  $("body #preview_box_lg img").attr('src', ROOT_PATH + 'filebox/orders/' + $("#uploaded_image").val());
                                                  $("body #preview_box_lg_wrap").show();
                                             });
                                             $("body").on('click', '.close_preview', function () {
                                                  $("body #preview_box_lg_wrap").hide();
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

