
<h3>
     <?= lang('order_msg_title') ?>
     <small>Total : {{msgcount.reply}} <?= lang('order_msg_title_sub') ?></small>
</h3>
<div class="col-lg-12">
     <div class="ask_querier">
          <textarea rows="3" class="form-control" placeholder="please write your comments here..." ng-model="comment.content"></textarea>
          <button type="button" class="btn btn-default ask_btn" ng-click="addcomment()">Ask</button>
     </div>
</div>
<div class="col-lg-12">     
     <div class="clearfix margin_bottom_10" ng-show="processingattach">
          <p class="text-success text-center"> <?= lang('uploadattach_ment') ?> 
               <img src="<?php echo base_url(); ?>resource/images/loading-bars.svg" width="36" height="36" alt="<?= lang('loading') ?>">
     </div>
     <div class="col-lg-12" style="padding-top: 5px">
          <button type="button" class="btn btn-default ask_btn pull-right" ng-click="addattachment(<?php echo $order->consignment_id; ?>)" ng-disabled="isDisabled">Add Attachment</button>
     </div>
</div>

<div class="col-lg-12">
     <div class="question" ng-repeat="msg in messages| orderBy : '-time2' ">
          <div class="q_head">
               <div class="q_title">
                    <span ng-if='msg.type == "message"'>Question from <strong>{{msg.courier}}</strong></span> 
                    <span ng-if='msg.type == "comment"'>Commented by <strong>You</strong></span> 
               </div>
               <div class="q_time">{{msg.time}}</div>
          </div>
          <div class="q_text">
               <p>{{msg.msg}}</p>
               <div class="q_response" ng-if='msg.type == "message"'>
                    <div class="q_head" ng-if='msg.reply !== null'>
                         <div class="q_title">Respond from <strong>You</strong></div>
                         <div class="q_time">{{msg.replytime}}</div>
                    </div>
                    <div class="q_text" ng-if='msg.reply !== null'>
                         <p>{{msg.reply}}</p>

                    </div>

               </div>
               <div  ng-if='msg.reply == null && msg.type == "message"'>
                    <span onaftersave="savereply(msg)" e-class="xedit_lg" e-required editable-text="msg.reply" class="form-control">{{msg.reply||'Please key in your response here'}}</span>
               </div>
          </div>
     </div>
     <div  ng-if='msg.reply == null && msg.type == "message"'>
          <span onaftersave="savereply(msg)" e-class="xedit_lg" e-required editable-text="msg.reply" class="form-control">{{msg.reply||'Please key in your response here'}}</span>
     </div>
     <!--      <div class="question">
               <div class="q_head">
                    <div class="q_title">Question from &lt;Courier name&gt;</div>
                    <div class="q_time">12 Jan 2014 11.05 am</div>
               </div>
               <div class="q_text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id dolor vel turpis tincidunt vehicula. Sed scelerisque elit ac justo auctor, vitae pretium sapien ultricies. In fringilla tincidunt erat, ut sollicitudin tellus venenatis a. Nullam quis dolor tristique, feugiat neque quis, tincidunt urna. Pellentesque commodo, ante ac convallis gravida, lectus tellus ultrices lorem, vestibulum accumsan sem nibh sed felis. Vivamus quis justo ac quam pulvinar sagittis sit amet sit amet eros. Vestibulum vel mattis magna, et consectetur magna. Aliquam id massa in nibh sollicitudin sodales non eget nunc. Curabitur eget mi et eros efficitur lobortis. Sed sit amet felis id purus varius tristique. Quisque at rutrum massa.</p>
                    <div class="q_response">
                         <div class="q_head">
                              <div class="q_title">Respond from you</div>
                              <div class="q_time">13 Jan 2014 10.05 am</div>
                         </div>
                         <div class="q_text">
                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id dolor vel turpis tincidunt vehicula. Sed scelerisque elit ac justo auctor, vitae pretium sapien ultricies. In fringilla tincidunt erat, ut sollicitudin tellus venenatis a. Nullam quis dolor tristique, feugiat neque quis, tincidunt urna. Pellentesque commodo, ante ac convallis gravida, lectus tellus ultrices lorem, vestibulum accumsan sem nibh sed felis. Vivamus quis justo ac quam pulvinar sagittis sit amet sit amet eros. Vestibulum vel mattis magna, et consectetur magna. Aliquam id massa in nibh sollicitudin sodales non eget nunc. Curabitur eget mi et eros efficitur lobortis. Sed sit amet felis id purus varius tristique. Quisque at rutrum massa.</p>
     
                         </div>
                    </div>
               </div>
          </div>
          <div class="question">
               <div class="q_head">
                    <div class="q_title">Comment by ypu</div>
                    <div class="q_time">12 Jan 2014 11.05 am</div>
               </div>
               <div class="q_text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id dolor vel turpis tincidunt vehicula. Sed scelerisque elit ac justo auctor, vitae pretium sapien ultricies. In fringilla tincidunt erat, ut sollicitudin tellus venenatis a. Nullam quis dolor tristique, feugiat neque quis, tincidunt urna. Pellentesque commodo, ante ac convallis gravida, lectus tellus ultrices lorem, vestibulum accumsan sem nibh sed felis. Vivamus quis justo ac quam pulvinar sagittis sit amet sit amet eros. Vestibulum vel mattis magna, et consectetur magna. Aliquam id massa in nibh sollicitudin sodales non eget nunc. Curabitur eget mi et eros efficitur lobortis. Sed sit amet felis id purus varius tristique. Quisque at rutrum massa.</p>
               </div>
          </div>
          <div class="question">
               <div class="q_head">
                    <div class="q_title">Question from &lt;Courier name&gt;</div>
                    <div class="q_time">12 Jan 2014 11.05 am</div>
               </div>
               <div class="q_text">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id dolor vel turpis tincidunt vehicula. Sed scelerisque elit ac justo auctor, vitae pretium sapien ultricies. In fringilla tincidunt erat, ut sollicitudin tellus venenatis a. Nullam quis dolor tristique, feugiat neque quis, tincidunt urna. Pellentesque commodo, ante ac convallis gravida, lectus tellus ultrices lorem, vestibulum accumsan sem nibh sed felis. Vivamus quis justo ac quam pulvinar sagittis sit amet sit amet eros. Vestibulum vel mattis magna, et consectetur magna. Aliquam id massa in nibh sollicitudin sodales non eget nunc. Curabitur eget mi et eros efficitur lobortis. Sed sit amet felis id purus varius tristique. Quisque at rutrum massa.</p>
                    <div class="q_response">
                         <input type="text" placeholder="Please key in your response here" class="form-control">
                    </div>
               </div>
          </div>-->
</div>