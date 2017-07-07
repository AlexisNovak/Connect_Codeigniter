<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8">
          <title>6Connect</title>
          <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
          <link rel="shortcut icon"  href="<?php echo base_url(); ?>resource/images/favicons.png" />
          <link rel="stylesheet" href="<?= base_url() ?>resource/home/css/all.css" />
          <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
          <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
          <![endif]-->
          <style>
               body {
                    background: #F9F9F9;
               }
               a {
                    color: #133439 !important;
               }
               a:hover {
                    color: #144B55 !important; 
                    text-decoration: initial;
               }
               a.active {
                    color: #fbbd34 !important;
               }
               .navbar.header {
                    padding: 10px;
                    background: url(<?php echo base_url(); ?>resource/home/images/bg-header.png);
               }
               .navbar.header .list-inline>li {
                    padding: 0 20px !important;
               }
               .carousel {
                    width: 100%;
               }
               .carousel .cover {
                    height: 700px;
               }
               .carousel .cover .padding_for_text {
                    height: 170px;
               }
               .carousel .cover .p1, .carousel .cover .p2 {
                    background: #fbbd34;
                    padding: 15px;
               }
               .sign_up_btn {
                    padding: 10px 35px;
                    background: #fbbd34;
                    font-size: 26px;
                    width: 228px;
               }
               .learn_more {
                    padding: 10px;
                    background: #fbbd34;
                    font-size: 15px;
                    width: 118px;
                    margin-top: 30px;
                    color: #FFFFFF;
               }
               .arrow_down {
                    color: #fbbd34;
                    font-size: 25px;
                    width: 25px;
                    margin-top: 8px;
               }
               .arrow_down:hover {
                    -webkit-animation: downanimation 1s infinite;
                    animation: downanimation 1s infinite;
               }
               @-webkit-keyframes downanimation {
                    from {margin-top: 8px;}
                    to {margin-top: 25px;}
               }
               .white {
                    color: #FFFFFF;
               }
               section h1, section h2, section h3, section h4 {
                    padding: 0 !important;
                    margin: 0 !important;
               }
               #section2 .section-description {
                    padding: 35px 0;
               }
               #section2 .section-description hr {
                    width: 90%;
                    margin-top: 0px !important;
                    border-bottom: 3px solid #fbbd34;
                    float: left;
               }
               #section2 .section-description .text {
                    font-size: 19px;
                    letter-spacing: 2px;
               }
               #section2 .section-description-image {
                    padding: 35px;
               }
               #section2 .introduction_6connect {
                    width: 810px;
                    margin: 0 auto;
               }
               #section3 .cover {
                    width: 100%;
               }
               #section3 .title {
                    background: #133439;
                    padding: 15px;
                    color: #FFFFFF;
                    margin-top: 50px;
                    width: 125px;
               }
               #section3 .text {
                    width: 100%;
                    padding: 12px 15px;
                    background: #133439;
                    color: #FFFFFF;
                    letter-spacing: 2px;
               }
               #section3 .how_use_step {
                    width: 100%;
                    overflow: hidden;
                    float: left;
                    position: relative;
               }
               #section3 .how_use_step li {
                    margin: 15px 0;
                    overflow: hidden;
                    width: 100%;
                    height: 70px;
               }
               #section3 .how_use_step .circle {
                    background-color: #fbbd34;
                    height: 70px;
                    width: 70px;
                    -webkit-border-radius: 75px;
                    -moz-border-radius: 75px;
                    border-radius: 75px;
                    position: absolute;
                    z-index: 1;
                    padding: 5px;
               }
               #section3 .how_use_step .circle .background {
                    background: url(<?php echo base_url(); ?>resource/images/6Connect-how-to-use-circle.png);
                    width: 60px;
                    height: 60px;
               }
               #section3 .how_use_step .circle .background h1 {
                    text-align: center;
                    line-height: 60px;	
               }
               #section3 .how_use_step .text-block {
                    width: 100%;
                    height: 70px;
                    background-color: #fbbd34;
                    position: absolute;
                    left: 35px;
                    padding: 15px 50px;
               }
               #section3 .how_use_step .text-block p {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    line-height: 20px;
                    font-size: 15px;
               }
               #section4.features {
                    width: 100%;
                    background: #fbbd34;
                    padding: 30px 0;
               }
               #section4.features .section-title {
                    font-size: 60px !important;
                    font-weight: 500;
               }
               #section4.features .background {
                    margin: 0 auto;
                    background: url(<?php echo base_url(); ?>resource/images/6Connect-features.png) no-repeat -3px 20px;
                    width: 290px;
                    height: 400px;
                    background-size: 100%;
               }
               #section4.features .features-list li {
                    float: left;
               }
               #section4.features .features-icon-circle {
                    height: 70px;
                    width: 70px;
                    -webkit-border-radius: 75px;
                    -moz-border-radius: 75px;
                    border-radius: 75px;
                    margin: 0 auto;
                    box-shadow: 0px 0px 15px #787878;
                    background: -webkit-linear-gradient(#FFFFFF, #9B9B9B);
                    background: -o-linear-gradient(#FFFFFF, #9B9B9B);
                    background: -moz-linear-gradient(#FFFFFF, #9B9B9B);
                    background: linear-gradient(#FFFFFF, #9B9B9B);
                    overflow: hidden;
               }
               .features-icons {
                    width: 70px;
                    height: 70px;
                    background: url(<?php echo base_url(); ?>resource/images/6Connect-features-icons.png) no-repeat;
               }
               .features-icons.icon1 {
                    background-position: 0px 0px;
               }
               .features-icons.icon2 {
                    background-position: 0px -68px;
               }
               .features-icons.icon3 {
                    background-position: 0px -140px;
               }
               .features-icons.icon4 {
                    background-position: 0px -208px;
               }
               .features-icons.icon5 {
                    background-position: 0px -278px;
               }
               .features-icons.icon6 {
                    background-position: 0px -347px;
               }
               .features-icons.icon7 {
                    background-position: 0px -420px;
               }
               .features-icons.icon8 {
                    background-position: 0px -488px;
               }
               #section4.features .features-text .title {
                    margin-top: 60px;
                    color: #FFFFFF;
               }
               #section4.features .features-text .text {
                    width: 80%;
                    margin: 0 auto;
                    color: #FFFFFF;
                    line-height: 23px;
                    height: 112px;
               }
               #section4.features .background-down {
                    margin: 0 auto;
                    background: url(<?php echo base_url(); ?>resource/images/6Connect-features-down.png) no-repeat 0px 20px;
                    width: 290px;
                    height: 400px;
                    background-size: 100%;
               }
               #section4.features .background-down .title {
                    margin: 0 0 0px;
                    padding: 180px 0 0;
               }
               #section5 blockquote {
                    border: 0 !important;
               }
               #section6 .cover {
                    width: 100%;
                    padding: 60px;
               }
               .get_start_btn {
                    width: 156px;
                    height: 156px;
                    background: url(<?php echo base_url(); ?>resource/images/get_start_btn.png);
               }
               #section6 .description-text {
                    background: url(<?php echo base_url(); ?>resource/home/images/bg-header.png);
                    padding: 15px;
                    margin: 50px auto;
                    width: 80%;
                    text-align: center;
               }
               #section6 .description-text h2 {
                    line-height: 40px;
               }
               #section7 .container {
                    width: 100%;
                    background: #FFFFFF;
               }
               #section7 .container .footer-logo {
                    padding: 35px 35px 0 35px;
               }
               #section7 .container .footer-links {
                    padding: 10px;
               }
               #section7 .container .footer-links .title {
                    margin: 20px 0;
                    color: #144B55;
               }
               .join_6Parcels {
                    padding: 12px 15px 8px;
                    background: #144B55;
                    width: 210px;
                    color: #FFFFFF;
                    border-radius: 20px 20px 0px 0px;
                    -webkit-border-radius: 20px 20px 0px 0px;
                    -moz-border-radius: 20px 20px 0px 0px;
                    margin: 20px 10px 0;
               }
               @media screen and (max-width: 760px) {
                    .navbar .logo {
                         width: 80px;
                         height: 80px;
                         background: url(<?php echo base_url(); ?>resource/images/6connect-logo-heaer.png);
                         margin: 0 !important;
                    }
               }
               @media screen and (max-width: 1200px) {
                    #section4.features .features-list li {
                         float: none !important;
                    }
               }
          </style>
     </head>
     <body>

          <nav class="navbar navbar-fixed-top header">
               <div class="row">
                    <div class="col-md-4 col-sm-3 col-xs-1">
                         <a href="<?php echo site_url() ?>"><div class="logo"></div></a>
                    </div>
                    <div class="col-sm-offset-4 col-md-4 col-sm-5 col-xs-offset-1 col-xs-10">
                         <ul class="list-inline pull-right">
                              <li><h3><small><a class="active" href="<?php echo site_url(); ?>">Home</a></small></h3></li>
                              <li><h3><small><a href="<?php echo site_url('account/sign_in'); ?>">Login</a></small></h3></li>
                              <li><h3><small><a href="<?php echo site_url('contact_us'); ?>">Contact Us</a></small></h3></li>
                         </ul>
                    </div>
               </div>
          </nav>

         