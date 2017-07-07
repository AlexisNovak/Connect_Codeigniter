<div class="content">
     <div class="wrap" animate-panel>
          <button type="button" ng-click="goback()"
                  class="btn btn-primary btn-sm close_btn">
               <i class="glyphicon glyphicon-remove"></i>
          </button>
          <div class="row">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <?php
                                     $keys = array('courier_name', 'id', 'review', 'rating', 'customer_name', 'service_name');
                                   ?>
                                   <div class="col-lg-12">
                                        <legend>Feedback Details</legend>
                                        <table class="table table-bordered table-striped">
                                             <?php
                                               foreach ($feedback as $key => $value) {
                                                    if (in_array($key, $keys)) {
                                                         ?>
                                                         <tr>
                                                              <td style="width: 300px; font-weight: bold; text-transform: capitalize;"><?= str_replace('_', ' ', $key) ?></td>
                                                              <td><?php
                                                                   if ($key == 'id') {
                                                                        echo sprintf('%010d', $value);
                                                                   } else if ($key == 'rating') {
                                                                        ?>
                                                                        <span class="starRating " style="margin-bottom: 8px;vertical-align: top">
                                                                             <span class="stars" style="vertical-align: top">
                                                                                  <span  style="vertical-align: top;width: <?= ((floatval($value) / 5) * 100) . "%" ?> " ></span>
                                                                             </span>
                                                                        </span>
                                                                        <?php
                                                                   } else {
                                                                        echo $value;
                                                                   }
                                                                   ?></td>
                                                         </tr>
                                                         <?php
                                                    }
                                               }
                                             ?>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>