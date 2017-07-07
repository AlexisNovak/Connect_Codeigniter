<?php $this->load->view('home/header'); ?>
<section class="inner-banner-row">
     <div class="inner-banner"><img src="<?= base_url() ?>resource/home/images/inner-banner.jpg" alt="">
          <div class="banner-caption">
               <h1>Contact Us</h1>
          </div>
     </div>
</section>
<section class="inner-midle contact-bg">
     <div class="page-holder">
          <div class="contact-top">
               <h1>We love to hear from you!</h1>
               <p>as much as we wish to hear your lovely voices, we currently only <br>
                    have a small team trying to make this world a better place.</p>
               <p>Yep, please email us for now :)</p>
          </div>
          <div class="contact-btm-row">
               <div class="contact-btm-box"><img src="<?= base_url() ?>resource/home/images/support-pic.jpg" alt="">
                    <div class="contact-btm-box-cnt">
                         <h3>Corporate Sales</h3>
                         <p>If you like to host 6Connect solution for your internal use, please contact us</p>
                         <a href="mailto:corporate-sales@6connect.biz">corporate-sales@6connect.biz</a> </div>
               </div>
               <div class="contact-btm-box partners"><img src="<?= base_url() ?>resource/home/images/partnership-pic.jpg" alt="">
                    <div class="contact-btm-box-cnt">
                         <h3>Partnership</h3>
                         <a href="mailto:partners@6connect.biz">partners@6connect.biz</a> </div>
               </div>
          </div>
     </div>
</section>
<?php $this->load->view('home/footer'); ?>
</body>
</html>