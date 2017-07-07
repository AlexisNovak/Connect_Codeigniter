<!DOCTYPE html>
<html ng-app="6connect">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Page title set in pageTitle directive -->
          <title page-title></title>
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo outer_base_url(); ?>resource/images/favicons.png" />
          
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap/dist/css/bootstrap.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/style.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/responsive.css">
     </head>
     <body>
<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12 no-padding">
                    <div class="hpanel">
                         <div class="panel-body">   
                              <div class="col-lg-10 col-lg-offset-1">
                                   <div class="no_org_box">
                                        <div class="icon_wrap">
                                             <img src="<?= outer_base_url('resource/images/favicons.png') ?>">
                                        </div>
                                        <div>
                                             <p class="no_org_title"><?= lang('no_org_title') ?></p>
                                             <p class="no_org_info">You are not authorised to access this page.<br> Please contact Admin</p>
                                                </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
     </body>
</html>