<!DOCTYPE html>
<html ng-app="6connect">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Page title set in pageTitle directive -->
          <title page-title></title>
          <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
          <link rel="shortcut icon"  href="<?php echo outer_base_url(); ?>resource/images/favicons.png" />

          <!-- build:css(.) styles/vendor.css -->
          <!-- bower:css -->
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/angular-notify/dist/angular-notify.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/fontawesome/css/font-awesome.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/metisMenu/dist/metisMenu.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/animate.css/animate.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/sweetalert/lib/sweet-alert.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/fullcalendar/dist/fullcalendar.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap/dist/css/bootstrap.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/summernote/dist/summernote.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/ng-grid/ng-grid.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-tree/dist/angular-ui-tree.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/angular-xeditable/dist/css/xeditable.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/ui-select/dist/select.min.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/ui-select/dist/select2.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" />
          <!-- endbower -->
          <!-- endbuild -->

          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/jquery.tagit.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/tagit.ui-zendesk.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/chosen/chosen.min.css">
          <link rel="stylesheet" type="text/css" href="<?php echo outer_base_url(); ?>resource/bower_components/daterangepicker/daterangepicker-bs3.css" />
          <link rel="stylesheet" type="text/css" href="<?php echo outer_base_url(); ?>resource/styles/bootstrap-datetimepicker.css" /> 
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/bootstrap-timepicker.min.css">

          <!-- build:css({.tmp,app}) styles/style.css -->
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/fonts/pe-icon-7-stroke/css/helper.css" />
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/bower_components/angular-bootstrap-lightbox/dist/angular-bootstrap-lightbox.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/dropzone.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/style.css">
          <link rel="stylesheet" href="<?php echo outer_base_url(); ?>resource/styles/responsive.css">

          <?php
            if (isset($partner) && !empty($partner->color_scheme)) {
                 ?>
                 <link rel="stylesheet" href="<?php echo outer_base_url('filebox/partner/color_scheme/' . $partner->color_scheme); ?>">  
                 <?php
            }
          ?>

          <!-- endbuild -->
          <script>
               var BASE_URL = "<?php echo rtrim(site_url(), "/") . '/'; ?>";

               var ROOT_PATH = "<?= outer_base_url() ?>";

          </script>
     </head>

     <!-- Body -->
     <!-- appCtrl controller with serveral data used in theme on diferent view -->
     <!-- landing-scrollspy is directive for scrollspy used in landing page -->
     <body ng-controller="appCtrl" class=" ifrm-body {{$state.current.data.specialClass}}" landing-scrollspy tour backdrop="true" oncontextmenu="return false">

          <!-- Simple splash screen-->
          <div class="splash"> 
               <div class="color-line"></div>
               <div class="splash-title">
                    <h1>6Connect</h1>
                    <img src="<?php echo outer_base_url(); ?>resource/images/loading-bars.svg" width="64" height="64" />
               </div> </div>

          <!--[if lt IE 7]>
          <p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
          <![endif]-->
          <!-- Header -->

          <?php $this->load->view('header'); ?>


          <!-- Main Wrapper -->

          <div id="wrapper" class="wrapper_full">

               <!-- Page view wrapper -->
               <div small-header class="normalheader transition small-header">
                    <div class="hpanel" tour-step order="1"  content="Place your page title and breadcrumb. Select small or large header or give the user choice to change the size." placement="bottom">
                         <div class="panel-body">

                              <div id="hbreadcrumb" class="pull-right">
                                   <ol class="hbreadcrumb breadcrumb" ng-hide="$state.current.data.hidebreadcrumb">
                                        <li><a ui-sref="dashboard">Dashboard</a></li>
                                        <li ng-repeat="state in $state.$current.path" ng-switch="$last || !!state.abstract" ng-class="{active: $last}">
                                             <a ng-switch-when="false" href="#organisations">{{state.data.pageTitle}}</a>

                                             <span ng-switch-when="true">{{state.data.pageTitle}}</span>
                                        </li>
                                   </ol>
                                   <ol class="hbreadcrumb breadcrumb" ng-show="$state.current.data.hidebreadcrumb">
                                        <li><a ng-click="open_get_start()">{{$state.current.data.breadcrumb}}</a></li>                                     
                                   </ol>
                              </div>
                              <h2 class="font-light m-b-xs">
                                   <span ng-if="$state.includes('organisation')">Organisation > {{orgname}}</span>
                                   <span ng-if="!$state.includes('organisation')">{{ $state.current.data.pageTitle}}</span>
                              </h2>
                              <small>{{ $state.current.data.pageDesc}}</small>
                         </div>
                    </div>
               </div>
               <!--common pop out start-->
               <div ng-controller="courier_popupCtrl">
                    <div class="angular_popup_overlay_trans" ng-show="show_popup">
                         <div class="angular_popup courier_popup pull-right popup">  
                              <h2>Courier Profile<span class="pull-right" ng-click="cancel_popup()">
                                        <img src="<?= outer_base_url() . 'resource/styles/img/close_gray.png' ?>"></span>
                              </h2>
                              <div class="form-holder">
                                   <div class="courier-box"> 
                                        <div class="col-xs-12 col-sm-6">
                                             <span class="courier-img">
                                                  <img ng-src="{{u_courier.logo}}" alt="courier_logo">   
                                             </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 no-padding">
                                             <p class="courier_title">{{u_courier.name}}
                                                  <span class="starRating hidden">
                                                       <input id="rating5" type="radio" ng-model="u_courier.rating" value="5" disabled>
                                                       <label for="rating5">5</label>
                                                       <input id="rating4" type="radio" ng-model="u_courier.rating" value="4" disabled>
                                                       <label for="rating4">4</label>
                                                       <input id="rating3" type="radio" ng-model="u_courier.rating" value="3" disabled>
                                                       <label for="rating3">3</label>
                                                       <input id="rating2" type="radio" ng-model="u_courier.rating" value="2" disabled>
                                                       <label for="rating2">2</label>
                                                       <input id="rating1" type="radio" ng-model="u_courier.rating" value="1" disabled>
                                                       <label for="rating1">1</label>
                                                  </span></p>
                                             <p>
                                                  <span>{{u_courier.description}}</span>
                                                  <span ng-if="u_courier.description1 != null && showmore" ng-click="show_more()" class="link_more">more&hellip;</span>
                                                  <span ng-if="u_courier.description1 != null && !showmore">{{u_courier.description1}}</span>
                                                  <span ng-if="u_courier.description1 != null && !showmore" ng-click="show_more()" class="link_more">&hellip;less</span>
                                             </p>
                                             <p>Phone</p>
                                             <p class="courier_addr">{{u_courier.phone}}</p>
                                             <p>Office Address</p>
                                             <p class="courier_addr">{{u_courier.reg_address}}</p>
                                        </div>
                                        <div class="col-xs-12 m-t-md hidden">

                                             <p class="perform">Performance</p>                                           
                                             <p ng-if="u_courier.compliance_rating">Compliance Rating : <strong class="compliance" ng-class="u_courier.label_class">{{u_courier.compliance_rating}}</strong></p>
                                             <p>Deliveries in last 3 months : <strong>{{u_courier.completed}}</strong></p>
                                             <p>Success delivery rate : <strong>{{u_courier.success_rate}}</strong></p>
                                             <div class="clr"></div>
                                             <div class="">
                                                  <p class="perform">Reviews</p>
                                                  <div class="review" ng-repeat="review in u_courier.reviews">
                                                       <p class="user">{{review.username}}
                                                            <span class="starRating">
                                                                 <input id="rating5" type="radio" ng-model="review.score" value="5" disabled>
                                                                 <label for="rating5">5</label>
                                                                 <input id="rating4" type="radio" ng-model="review.score" value="4" disabled>
                                                                 <label for="rating4">4</label>
                                                                 <input id="rating3" type="radio" ng-model="review.score" value="3" disabled>
                                                                 <label for="rating3">3</label>
                                                                 <input id="rating2" type="radio" ng-model="review.score" value="2" disabled>
                                                                 <label for="rating2">2</label>
                                                                 <input id="rating1" type="radio" ng-model="review.score" value="1" disabled>
                                                                 <label for="rating1">1</label>
                                                            </span>
                                                       </p>
                                                       <p>{{review.review}}</p>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div> 
                    </div> 
               </div>
               <!--common pop out end-->


               <div ui-view  style="min-height:350px;"></div>

               <?php $this->load->view('footer'); ?>
          </div>
          <!-- build:js(.) scripts/vendor.js -->
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery/dist/jquery.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery-ui/jquery-ui.min.js"></script>
          <!-- Include Required Prerequisites -->
          <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/slimScroll/jquery.slimscroll.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular/angular.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-route/angular-route.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-animate/angular-animate.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery-flot/jquery.flot.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery-flot/jquery.flot.resize.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery-flot/jquery.flot.pie.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/flot.curvedlines/curvedLines.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/jquery.flot.spline/index.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-flot/angular-flot.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/metisMenu/dist/metisMenu.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/sweetalert/lib/sweet-alert.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/iCheck/icheck.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/sparkline/index.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/peity/jquery.peity.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-peity/angular-peity.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/sweetalert/lib/sweet-alert.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-notify/dist/angular-notify.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/chartjs/Chart.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angles/angles.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-utils/ui-utils.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-map/ui-map.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/moment/min/moment.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-calendar/src/calendar.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/summernote/dist/summernote.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-summernote/dist/angular-summernote.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/ng-grid/ng-grid-2.0.14.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-ui-tree/dist/angular-ui-tree.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-bootstrap-tour/dist/angular-bootstrap-tour.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-datatables/dist/angular-datatables.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-xeditable/dist/js/xeditable.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/ui-select/dist/select.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/highcharts-ng/dist/highcharts-ng.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/tc-angular-chartjs/dist/tc-angular-chartjs.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/highcharts/highstock.src.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/highcharts/exporting.js"></script>          
          <script src="<?php echo outer_base_url(); ?>resource/scripts/highcharts/no-data-to-display.js"></script>
          <script type="text/javascript" src="<?php echo outer_base_url(); ?>resource/bower_components/daterangepicker/daterangepicker.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/zeroclipboard/dist/ZeroClipboard.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/ng-clip/src/ngClip.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/bower_components/angular-bootstrap-lightbox/dist/angular-bootstrap-lightbox.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/ngAutocomplete.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/angular-moment.min.js"></script>
          <!-- endbuild -->

          <script src="<?php echo outer_base_url(); ?>resource/scripts/dropzone.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/tag-it.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/chosen.jquery.min.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/bootstrap-datetimepicker.js"></script>
          <script type="text/javascript" src="<?php echo outer_base_url(); ?>resource/scripts/bootstrap-timepicker.min.js"></script>
          <!-- build:js({.tmp,app}) scripts/scripts.js -->
          <script src="<?php echo outer_base_url(); ?>resource/scripts/homer.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/app.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/partner_config.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/filters/props.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/directives/directives.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/phonebookService.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/main.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/appControllers.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/accnotification.js"></script> 
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/courier_popup.js"></script>


          <script src="<?php echo outer_base_url(); ?>resource/scripts/directives/touchSpin.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/dashboard.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/members.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/organisation.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/activities.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/groups.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/services.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/orderservice.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/delivery_order.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/multiorder.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/userreports.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/overallreports.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/groupreports.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/partner.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/courier_management.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/mycontacts.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/service_request.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/available_services.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/pre_approved_bidders.js"></script>
          <script src="<?php echo outer_base_url(); ?>resource/scripts/controllers/org_tracking.js"></script>
          <!-- endbuild -->


          <script>
                                                                      var reload_w = function () {
                                                                           setTimeout(function () {
                                                                                $.post(BASE_URL + 'account/reloader').success(function (data) {
                                                                                     if (data == 1) {
                                                                                          location.reload();
                                                                                     }
                                                                                });

                                                                                reload_w();
                                                                           }, 5000);
                                                                      };
                                                                      $(function () {
                                                                           reload_w();
                                                                      });
          </script>
     </body>
</html>