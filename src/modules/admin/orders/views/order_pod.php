<style>
     a[download]:hover{
          color: #f8c400;
     }
</style>
<div class="col-xs-12">
     <?php
       if (isset($signature)) {
            ?>
            <div class="signature_block">
                 <div class="cfm_sub_h2">Signature</div>
                 <div class="pod_img_wrap">
                      <img src="<?= $signature->pod_image_url ?>" ng-click="openLightboxModal('<?= str_replace('filebox/pod', 'filebox/pod/original', $signature->pod_image_url) ?>')">
                 </div>
            </div>
            <?php }
     ?>
</div>
<div class="col-xs-12">

     <?php
       if (isset($pods) && is_array($pods)) {
            ?><div class="cfm_sub_h2">Other Images</div>
            <?php
            foreach ($pods as $pod) {
                 ?>
                 <div class="pod_block">
                      <div class="pod_img_wrap">
                           <img src="<?= $pod->pod_image_url ?>" ng-click="openLightboxModal('<?= str_replace('filebox/pod', 'filebox/pod/original', $pod->pod_image_url) ?>')">
                           <a href="<?= str_replace('filebox/pod', 'filebox/pod/original', $pod->pod_image_url) ?>" download style="text-align: right;"><i class="fa fa-download" style="display: block; position: relative; bottom: 25px; font-size: 20px;right:10px"></i></a>
                      </div>
                 </div>
                 <?php
            }
       }
     ?>
</div>