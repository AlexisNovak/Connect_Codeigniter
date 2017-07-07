<style type="text/css">
     .dropzone {
      min-height: 150px;
      border: 5px dashed #f8c400;
      background: #EBEBEB;
      color: #164C56;
      padding: 20px 20px;
 }
 .dropzone .sub{
     color: #34495E;
 }
</style>
<div class="col-lg-12 no-padding margin_bottom_10">
 <div class="clearfix margin_bottom_10" ng-show="processingattach">
  <p class="text-success text-center"> <?= lang('uploadattach_ment') ?> 
   <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
</div>
<div class="clearfix"></div>
<form method="post" name="attachments" multiple id="attachmentsform" enctype="multipart/form-data">
  <input type="text" ng-model="upload" multiple id="uploaded_image" class="hidden" required>
  <div class="dropzone_wrap">
   <div class="dropzone " multiple id="order_template_attach">
   </div>
</div>
</form>
    <!-- <form method="post" name="attachments" id="attachmentsform" enctype="multipart/form-data">
         <input type="file" id="attachmentfile" name="attachment" class="hidden">
    </form>

    <div class="col-lg-12" style="padding-top: 5px">
         <button type="button" class="btn btn-default ask_btn pull-right" ng-click="addattachment(<?php echo $order->consignment_id; ?>)" ng-disabled="isDisabled">Add Attachment</button>
    </div> -->

</div>
<div class="clearfix"></div>
<div class="clr"></div>
<div class="table-responsive">     
 <table id="log_list" class="table table-striped table-bordered table-responsive">
  <thead>             
   <tr>
    <th><?= lang('attachments') ?>
    </th>
    <th></th>
</tr>
</thead>
<tbody>
   <tr ng-repeat="attach in attachmentslist">                   
        <td>
            <a ng-if="!imageCheck(attach.name)" href="<?= site_url('orders/downloadAttachment') ?>/{{attach.id}}" target="_blank">
                {{attach.name}}
           </a>
           <a ng-if="imageCheck(attach.name)" ng-click="openLightboxModal(attach.path)" target="_blank">
                {{attach.name}}
           </a>
      </td>

      <td>
           <a href="<?= site_url('orders/downloadAttachment') ?>/{{attach.id}}" target="_blank">
                <span>
                     <i class="fa fa-download"></i>
                </span>
           </a>
           <span class="text-danger" ng-click="deleteAttachments(attach.id, attach.name)">
                <i class="fa fa-trash"></i>
           </span>
      </td>
 </tr>
 <tr  class="no-data" ng-show="!attachmentslist.length">
    <td colspan="2"><?= lang('nothing_to_display') ?></td>
</tr>
</tbody>
</table>

</div>

<!-- <div class="col-md-12 no-padding">
     <div class="col-md-4 no-padding">
          <div ng-show="attach.total" style="line-height: 35px;">Showing {{attach.start}} to {{attach.end}} of {{attach.total}} entries</div>
     </div> 
     <div class="col-md-12 text-right no-padding">

          <paging
               class="small"
               page="loglistdata.currentPage" 
               page-size="loglistdata.perpage_value" 
               total="attach.total"
               adjacent="{{adjacent}}"
               dots="{{dots}}"
               scroll-top="{{scrollTop}}" 
               hide-if-empty="false"
               ul-class="{{ulClass}}"
               active-class="{{activeClass}}"
               disabled-class="{{disabledClass}}"
               show-prev-next="true"
               paging-action="getAttachments(page)">
          </paging> 
     </div>
</div> -->
<script>
 var SITE_URL = "<?= site_url() ?>";
 $(function () {

  var myDropzone = new Dropzone("#order_template_attach", {
   url: BASE_URL + 'orders/uploadattachments',
   dictDefaultMessage: "<h1><?= lang('order_file_info_attachments') ?></h1><h5 class='sub'><?= lang('order_file_info_attachments_sub') ?></h5>",
   acceptedFiles: ".xls, .xlsx, .doc, .docx, .pdf, .png, .jpg",
   addRemoveLinks: true,
   uploadMultiple: true,
   maxFiles: 15,
   dictMaxFilesExceeded: "Only 15 images allowed.",
   init: function () {
    this.on("addedfile", function (file) {
     $("#order_template_attach").addClass('active');

});
    this.on("dragenter", function () {
     $("#order_template_attach .dz-message span").html("<?= lang('drag_enter_info') ?>");
     $("#order_template_attach").addClass('active');

});
    this.on("drop", function () {
     $("#order_template_attach .dz-message span").html("<h1><?= lang('order_file_info_attachments') ?></h1><h5 class='sub'><?= lang('order_file_info_attachments_sub') ?></h5>");
     $('button[type=submit]').hide();

});
    this.on("removedfile", function (file) {
     $("#uploaded_image").val("");
     $("#uploaded_image").trigger('input');
});
    this.on('sending', function (file, xhr, formData) {
     formData.append('order_id', "<?php echo $order->consignment_id; ?>");
});
    this.on("success", function (response, result) {
     angular.element('#order_template_attach').scope().getAttachments();
     angular.element('#order_template_attach').scope().$apply();
});
    this.on('complete', function (response, result, file) {
     if (response.status != true) {
      angular.element('#order_template_attach').scope().notifydata(response.xhr.response);
      angular.element('#order_template_attach').scope().$apply();
 }
 var length = myDropzone.files.length;
 if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
      for (var i = 0; i < length; i++) {
       myDropzone.removeFile(myDropzone.files[0]);
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