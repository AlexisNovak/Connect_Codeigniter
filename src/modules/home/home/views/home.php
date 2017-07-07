<?php
  $this->load->view('header');
?> 
<section id="section1">
     <div class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
               <div class="item active">
                    <div class="cover" style="background:url(<?php echo base_url(); ?>resource/images/cover-1.jpg);background-size:cover;">
                         <div class="padding_for_text"></div>
                         <div class="p1 center-block" style="width:480px;"><h1>FIND & COMPARE PRICES</h1></div>
                         <div class="p2 center-block" style="width:555px;"><h1>AMONG MULTIPLE COURIERS</h1></div>
                         <div class="padding_for_text"></div>
                         <div class="sign_up_btn center-block"><a href="<?= site_url('account/sign_up') ?>">Sign Up <strong>Free</strong></a></div>
                         <div class="learn_more center-block">LEARN MORE</div>
                         <div class="arrow_down center-block"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
                    </div>
               </div>
<!--               <div class="item">
                    <div class="cover" style="background:url(<?php echo base_url(); ?>resource/images/cover-2.jpg);background-size:cover;">
                         <div class="padding_for_text"></div>
                         <div class="p1 center-block" style="width:415px;"><h1>REAL TIME TRACKING</h1></div>
                         <div class="padding_for_text"></div>
                         <div class="sign_up_btn center-block"><a href="<?= site_url('account/sign_up') ?>">Sign Up <strong>Free</strong></a></div>
                         <div class="learn_more center-block">LEARN MORE</div>
                         <div class="arrow_down center-block"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
                    </div>
               </div>-->
<!--               <div class="item">
                    <div class="cover" style="background:url(<?php echo base_url(); ?>resource/images/cover-3.jpg);background-size:cover;">
                         <div class="padding_for_text"></div>
                         <div class="p1 center-block" style="width:455px;"><h1>PRE-AUDITED RELIABLE</h1></div>
                         <div class="p2 center-block" style="width:390px;"><h1>COURIER COMPANY</h1></div>
                         <div class="padding_for_text"></div>
                         <div class="sign_up_btn center-block"><a href="<?= site_url('account/sign_up') ?>">Sign Up <strong>Free</strong></a></div>
                         <div class="learn_more center-block">LEARN MORE</div>
                         <div class="arrow_down center-block"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>
                    </div>
               </div>-->
          </div>
     </div>
</section>

<section id="section2">
     <div class="container">
          <div class="col-md-12">
               <div class="col-md-8 col-sm-9 section-description">
                    <h1>What is 6Connect?</h1>
                    <hr>
                    <div class="clearfix"></div>
                    <div class="text">6Connect is a platform to allow enterprise to<br>compare prices and assign delivery jobs to<br>multiple pre-audited courier companies with<br>delivery management and tracking capabilities.</div>
               </div>
               <div class="col-md-4 col-sm-3 section-description-image">
                    <img width="100%" src="<?php echo base_url(); ?>resource/images/6Connect-desc.jpg" />
               </div>
          </div>
     </div>
</section>

<section id="section3">
     <div class="cover" style="background:url(<?php echo base_url(); ?>resource/images/cover-4.jpg);background-size:cover;">
          <div class="container">
               <div class="col-md-6 col-md-offset-6">
                    <div class="title">
                         <h1>How?</h1>
                    </div>
                    <div class="text">
                         <h4>Via 6 Connect, enterprise can compare services <br>among different couriers and track them from the<br>same dashboard.</h4>
                    </div>
                    <ul class="how_use_step list-unstyled">
                         <li>
                              <div class="circle">
                                   <div class="background">
                                        <h1>1</h1>
                                   </div>
                              </div>
                              <div class="text-block">
                                   <p>Enterprise will log into 6Connect to compare price and place delivery order to selected courier com-panies.</p>
                              </div>
                         </li>
                         <li>
                              <div class="circle">
                                   <div class="background">
                                        <h1>2</h1>
                                   </div>
                              </div>
                              <div class="text-block">
                                   <p>Selected couriers will receive the order via email.<br>They will come to collect and dellver the parcels.</p>
                              </div>
                         </li>
                         <li>
                              <div class="circle">
                                   <div class="background">
                                        <h1>3</h1>
                                   </div>
                              </div>
                              <div class="text-block">
                                   <p>Courier will update the delivery status and you will be able to monltor the status from 6Connect & get notified.</p>
                              </div>
                         </li>
                    </ul>
               </div>
          </div>
     </div>
</section>

<section id="section4" class="features">
     <div class="container">
          <div class="row">
               <div class="col-md-12">
                    <div class="section-title white">
                         <p>Features</p>
                    </div>
               </div>
          </div>
          <div class="row">
               <ul class="list-unstyled features-list">
                    <li>
                         <div class="text-center">
                              <div class="background">
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon1"></div>
                                   </div>
                                   <div class="features-text">
                                        <div class="title"><h4>ONLINE</h4></div>
                                        <div class="text">Order, dispatch and track deliveries and view order histrory on your desktop</div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background">
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon2"></div>
                                   </div>
                                   <div class="features-text">
                                        <div class="title"><h4>REAL-TIME TRACKING</h4></div>
                                        <div class="text">Receive real time email of your completed deliveries</div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background">
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon3"></div>
                                   </div>
                                   <div class="features-text">
                                        <div class="title"><h4>PREAUDITED RELIABLE COURIER COMPANIES</h4></div>
                                        <div class="text">Audit list for courier companies On the platform so you will have a piece of mind</div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background">
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon4"></div>
                                   </div>
                                   <div class="features-text">
                                        <div class="title"><h4>ELECTRONIC SIGNATURES</h4></div>
                                        <div class="text">Instant confirmation of successful deliveries through electronic signatures</div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background-down">
                                   <div class="features-text">
                                        <div class="title"><h4>BATCH ORDERS</h4></div>
                                        <div class="text">Upload multiple deliveries simltaneously online or througn API integration</div>
                                   </div>
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon5"></div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background-down">
                                   <div class="features-text">
                                        <div class="title"><h4>ECOMMERCE ENABLED</h4></div>
                                        <div class="text">Online delivery platform integrated to your shopping cart</div>
                                   </div>
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon6"></div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background-down">
                                   <div class="features-text">
                                        <div class="title"><h4>ACCOUNTABILITY</h4></div>
                                        <div class="text">Track the usage of every department and employee.<br>Prevent misuse of company resources for personal use.</div>
                                   </div>
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon7"></div>
                                   </div>
                              </div>
                         </div>
                    </li>
                    <li>
                         <div class="text-center">
                              <div class="background-down">
                                   <div class="features-text">
                                        <div class="title"><h4>MULTIPLE COST CENTERS</h4></div>
                                        <div class="text">Set up and manage related business units with multiple users</div>
                                   </div>
                                   <div class="features-icon-circle">
                                        <div class="features-icons icon8"></div>
                                   </div>
                              </div>
                         </div>
                    </li>
               </ul>
          </div>
     </div>
</section>

<section id="section5">
     <div class="q-area">
          <div class="page-holder">
               <div class="q-slides">
                    <ul class="slides">
                         <li><blockquote> <q>Our schools has to make deliveries often to parents. 6Connect had help us to simplify the logistics, and help us find many reliable best courier services. </q> <cite> <span class="img"><img src="<?= base_url() ?>resource/home/images/image02.jpg" alt="" width="50" height="50" /></span> <span class="txt"><strong class="ttl">Lawrence Lim</strong> Blossom Childcare Centre - Principle </span> </cite> </blockquote></li>
                         <li><blockquote> <q>2Our schools has to make deliveries often to parents. 6Connect had help us to simplify the logistics, and help us find many reliable best courier services. </q> <cite> <span class="img"><img src="<?= base_url() ?>resource/home/images/image02.jpg" alt="" width="50" height="50" /></span> <span class="txt"><strong class="ttl">Lawrence Lim</strong> Blossom Childcare Centre - Principle </span> </cite> </blockquote></li>
                         <li><blockquote> <q>3Our schools has to make deliveries often to parents. 6Connect had help us to simplify the logistics, and help us find many reliable best courier services. </q> <cite> <span class="img"><img src="<?= base_url() ?>resource/home/images/image02.jpg" alt="" width="50" height="50" /></span> <span class="txt"><strong class="ttl">Lawrence Lim</strong> Blossom Childcare Centre - Principle </span> </cite> </blockquote></li>
                    </ul>
               </div>
          </div>
     </div>
</section>

<section id="section6">
     <div class="cover" style="background:url(<?php echo base_url(); ?>resource/images/cover-1.jpg);background-size:cover;">
          <div class="container">
               <div class="row">
                    <div class="center-block get_start_btn"></div>
               </div>
               <div class="row description-text">
                    <h2>We went to simplify logistic needs for everyone, So<br>6Connect Basic Plan is provided free with no string attached,<br>You can even invite your existing courier On board.</h2>
               </div>
               <div class="sign_up_btn center-block"><a href="<?= site_url('account/sign_up') ?>">Sign Up <strong>Now</strong></a></div>
          </div>
     </div>
</section>

<?php $this->load->view('footer'); ?>
<script type="text/javascript" src="<?= base_url() ?>resource/home/js/jquery.bxslider.min.js"></script> 
<script>
     $(function () {
          $(".carousel").carousel({
               interval: 3000
          });
          if ($('.q-slides .slides').length) {
               $('.q-slides .slides').bxSlider({
                    mode: 'fade',
                    auto: true,
                    controls: false
               });
          }
     });
</script>
</body>
</html>