<div class="content" id="neworg">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="new_org_banner p-md">
                                   <div class="col-xs-12">
                                        <p class="n_o_caption">Congratulation!<br>You have created an organisation</p>
                                        <p class="n_o_caption_sub">Lets get started 
                                             <a ui-sref="organisation.members({id:<?= $organisation->id ?>,flag:0})" class="play_btn">
                                             </a>
                                        </p>
                                   </div>
                              </div>    
                              <div class="clearfix"></div>

                              <div class="col-lg-12 no-padding">
                                   <div class="col-lg-12 margin_bottom_10">

                                   </div>
                                   <div class="col-lg-12 margin_bottom_10">
                                        <div class="col-lg-4 col-md-4 no-padding reponsive">
                                             <a ui-sref="organisation.members({id:<?= $organisation->id ?>,flag:0})" class="explore_box add_new_member">
                                                  <img src="<?= base_url() ?>resource/images/members.png">
                                                  <h4>Add Members</h4>
                                                  <p>Invite other colleagues into your organisation.</p> 
                                             </a>

                                        </div>
                                        <div class="col-lg-4 col-md-4 no-padding reponsive">
                                             <a ui-sref="organisation.services.active_services({id:<?= $organisation->id ?>,flag:0})" class="explore_box avail_services">
                                                  <img src="<?= base_url() ?>resource/images/services.png">
                                                  <h4>Available Services</h4>
                                                  <p>Explore the wide list of delivery services from our couriers and add them into your organisation.</p>
                                             </a>

                                        </div>
                                        <div class="col-lg-4 col-md-4 no-padding reponsive">
                                             <a class="explore_box org_help">
                                                  <img src="<?= base_url() ?>resource/images/help.png">
                                                  <h4>Get Help!</h4>
                                                  <p>Having difficulties?<br> Please contact us for assistance.</p>
                                             </a>

                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>             

               </div>
          </div>
     </div>
</div>