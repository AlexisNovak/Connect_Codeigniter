<div class="col-xs-12">
    <?php
    if (isset($signature)) {
        ?>
        <div class="signature_block">
             <div class="cfm_sub_h2">Signature</div>
            <div class="pod_img_wrap">
                <img src="<?= $signature->pod_image_url ?>" ng-click="openLightboxModal('<?= $signature->pod_image_url ?>')">

            </div>
        </div>
        <?php
    }?>
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
                    <img src="<?= $pod->pod_image_url ?>" ng-click="openLightboxModal('<?= $pod->pod_image_url ?>')">

                </div>
            </div>
            <?php
        }
    }
    ?>
</div>