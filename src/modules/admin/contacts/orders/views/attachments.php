<div class="col-lg-12 no-padding margin_bottom_10">
     <div class="clearfix margin_bottom_10" ng-show="processingattach">
          <p class="text-success text-center"> <?= lang('uploadattach_ment') ?> 
               <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
     </div>
     <div class="clearfix"></div>

     <form method="post" name="attachments" id="attachmentsform" enctype="multipart/form-data">
          <input type="file" id="attachmentfile" name="attachment" class="hidden">
     </form>

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
                         <a href="<?= site_url('orders/downloadAttachment') ?>/{{attach.id}}" target="_blank">
                              {{attach.name}}
                         </a>
                    </td>

                    <td>
                         <span class="text-danger" ng-click="deleteAttachments(attach.id)">
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

<div class="col-md-12 no-padding">
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
</div>
