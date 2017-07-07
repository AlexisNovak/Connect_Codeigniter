 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title><?php echo isset($title) ? $title.' - '.lang('website_title') : lang('website_title'); ?></title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo outer_base_url(); ?>resource/images/favicons.png" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/bower_components/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/bower_components/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/bower_components/animate.css/animate.css" />
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/bower_components/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/styles/style.css">
    <link rel="stylesheet" href="<?php echo outer_base_url();?>resource/styles/responsive.css">
    <script src="<?php echo outer_base_url();?>resource/bower_components/jquery/dist/jquery.min.js"></script>