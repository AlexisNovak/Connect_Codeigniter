
<div class="col-lg-12 clearfix" id="msg_sec">
     <div class="col-lg-12">
          <h3>Questions for Customer</h3>
          <div class="ask_querier">
               <textarea rows="3" class="form-control" placeholder="please write your question here..."  ng-model="comment.content"></textarea>
               <button type="button" class="btn btn-default ask_btn" ng-click="addcomment(<?php echo $order->consignment_id; ?>)" ng-disabled="isDisabled">Ask</button>
          </div>
     </div>
     <div class="col-lg-12">
          <div class="clearfix margin_bottom_10" ng-show="processingattach">
               <p class="text-success text-center"> <?= lang('uploadattach_ment') ?> 
                    <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
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
                                     if ($message->by_you == 1) {
                                          echo 'Ask by you';
                                     } else if ($message->by_you == 0) {
                                          echo 'Ask by other courier';
                                     } else {
                                          echo 'Comment by customer';
                                     }
                                     ?></div>
                                <div class="q_time"><?php echo date('Y-m-d h:i A', strtotime($message->created_date)); ?></div>
                           </div>
                           <div class="q_text">
                                <p><?= $message->question ?></p>
                                <?php
                                if ($message->reply) {
                                     ?>
                                     <div class="q_response">
                                          <div class="q_head">
                                               <div class="q_title">Respond from  customer</div>
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
