<style>
     .thed{
          text-transform: capitalize;
          width: 200px;
     }
</style>
<div class="content">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()" class="btn btn-primary btn-sm close_btn"><i class="glyphicon glyphicon-remove"></i></button>
          <div class="row"> 
               <div class="col-sm-12">
                    <div class="hpanel">
                         <div class="panel-body">
                              <div class="col-lg-12">
                                   <div class="col-lg-12">
                                        <legend>Service Tender Details</legend>
                                        <table class="req_details table table-bordered table-striped">

                                             <tbody>
                                                  <tr>
                                                       <td class="thed">Name</td>
                                                       <td><?= $request->title ?></td>
                                                  </tr>                                                 
                                                  <tr>
                                                       <td class="thed">Description</td>
                                                       <td><?= $request->description ?></td>
                                                  </tr>
<!--                                                  <tr>
                                                       <td class="thed"><?= lang('srequest_type') ?></td>
                                                       <td><?= $request->type ?></td>
                                                  </tr>-->
                                                  <tr>
                                                       <td class="thed">Deliveries per month</td>
                                                       <td><?= $request->delivery_p_m ?> deliveries/month</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="thed">Service duration</td>
                                                       <td><?= $request->duration ?> months</td>
                                                  </tr>
                                                  <tr>
                                                       <td class="thed">Payment term</td>
                                                       <td><?= $request->payment ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="thed">Other conditions</td>
                                                       <td><?= $request->other_conditions ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="item_head"><?= lang('srequest_expiry') ?></td>
                                                       <td>  <?php echo date('d-m-Y H:i A', strtotime($request->expiry)); ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="thed">Your Bid</td>
                                                       <td><?= isset($bid->bid_id) ? '<span class="label label-success">' . $bid->service . '</sapn>' : "-" ?></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="item_head"><?= lang('srequest_uploads') ?></td>
                                                       <td>  
                                                            <?php foreach ($request->uploads as $upload) {
                                                                   ?> <p><a href="<?= $upload['path'] ?>" download><?= $upload['name'] ?></a></p>
                                                              <?php } ?>
                                                       </td>
                                                  </tr>
                                             </tbody>              
                                        </table>
                                   </div>
                              </div>


                              <div class="col-lg-12 clearfix" id="msg_sec">
                                   <div class="col-lg-12">
                                        <h3>Questions for Customer</h3>
                                        <div class="ask_querier">
                                             <textarea rows="3" class="form-control" placeholder="please write your question here..."  ng-model="comment.content"></textarea>
                                             <button type="button" class="btn btn-default ask_btn" ng-click="addcomment()">Ask</button>
                                        </div>
                                   </div>
                                   <div class="col-lg-12" id="messages">

                                        <?php
                                          if (isset($messages) && is_array($messages)) {
                                               foreach ($messages as $message) {
                                                    ?>
                                                    <div class="question">
                                                         <div class="q_head">
                                                              <div class="q_title"><?php
                                                                   if ($message->by_you == 1 && $message->type == "message") {
                                                                        echo 'Ask by you';
                                                                   } else if ($message->by_you == 0 && $message->type == "message") {
                                                                        echo 'Ask by other courier';
                                                                   } else {
                                                                        echo 'Comment by customer';
                                                                   }
                                                                   ?></div>
                                                              <div class="q_time"><?php echo date('Y-m-d h:i A', strtotime($message->created_date)); ?></div>
                                                         </div>
                                                         <div class="q_text">
                                                              <p><?= $message->msg ?></p>
                                                              <?php
                                                              if ($message->reply) {
                                                                   ?>
                                                                   <div class="q_response">
                                                                        <div class="q_head">
                                                                             <div class="q_title">Respond from customer</div>
                                                                             <div class="q_time"><?php echo date('Y-m-d h:i A', strtotime($message->updated_date)); ?></div>
                                                                        </div>
                                                                        <div class="q_text">
                                                                             <p><?= $message->reply ?></p>

                                                                        </div>
                                                                   </div>   <?php
                                                              } else if ($message->by_you != 2) {
                                                                   ?>
                                                                   <div class="q_response">
                                                                        <div class="q_text">
                                                                             <p><strong>Customer not yet responded to your question.</strong></p>

                                                                        </div>
                                                                   </div>
                                                                   <?php
                                                              }
                                                              ?>
                                                         </div>
                                                    </div>
                                                    <?php
                                               }
                                          }
                                        ?>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

