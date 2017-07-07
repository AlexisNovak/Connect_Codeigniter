<table  style="width: 100%;border-collapse:collapse">
     <tr style="background-color:#f3f3f3;">
          <td colspan="2" style="color: #999;font-size: 18px;padding: 15px;text-align: center">We hope you have a good and smooth delivery with our couriers</td>
     </tr>
     <tr style="color: #164C56;background-color:#f3f3f3;padding: 15px;">
          <td style="width: 50%;padding: 25px;max-width: 40%;padding-top: 0px;">
               <b><span>Total Delivery Fee</span></b>
               <br>

               <div style="">
                    <div style="background-color:#f9c400;padding: 8px 10px;font-size: 16px">
                         <b>
                              SGD <?= round($order->price, 2) ?>
                         </b>
                    </div>
               </div>
          </td>
          <td style="padding: 25px;padding-top: 0px;">               
               <b><span>Receipt Date & Time</span></b>
               <br>
               <div style="">
                    <div style="background-color:#f9c400;padding: 8px 10px;font-size: 16px">
                         <b>
                              <?= gmdate("Y-m-d H:i:s O", time()) ?>
                         </b>
                    </div>
               </div>
          </td>
     </tr>
     <tr style="padding: 15px;">
          <td colspan="2">
               <table style="width: 100%">
                    <tr>
                         <td style="width: 50%">
                              <h2 style="font-size: 25px;text-decoration: underline">RECEIPT DETAIL</h2>
                              <span style="color: #999"><small>Payment method</small></span>
                              <p  style="margin-top: 2px">
                                   <b>
                                        <?php
                                          if ($order->payment_type == 1) {
                                               echo lang('cash_sender');
                                          } else if ($order->payment_type == 2) {
                                               echo lang('cash_recipient');
                                          }
                                        ?>
                                   </b>
                              </p>

                              <span style="color: #999"><small>Delivery Fee</small></span>
                              <p style="margin-top: 2px">
                                   <b>$ <?= $order->price ?>
                                   </b></p>

                              <span style="color: #999"><small>Issued By Courier</small></span>
                              <p style="margin-top: 2px"><b> <?= $order->courier_name ?></b></p>

                              <span style="color: #999"><small>Issued To</small></span>
                              <p style="margin-top: 2px"><b> <?= $customer->fullname ?></b></p>


                              <span style="color: #999"><small>Issuee's Company/Organization</small></span>
                              <p style="margin-top: 2px"><b><?= (isset($organization->name)) ? $organization->name : 'Personnel' ?></b></p>
                         </td>
                         <td style="width: 50%;">
                              <div style="background-color:#f3f3f3;padding: 15px;margin-top: 10px;">
                                   <h4 style="color: #f9c400">Booking Summery</h4>

                                   <span style="color: #999"><small>Item Type</small></span>
                                   <p  style="margin-top: 2px"><b><?= $item_type->display_name ?> X <?= $order->quantity ?></b></p>

                                   <span style="color: #999"><small>Collection Point</small></span>
                                   <p style="margin-top: 2px"><b><?= implode(',', json_decode($order->collection_address, true)) ?></b></p>

                                   <span style="color: #999"><small>Delivery Address</small></span>
                                   <p style="margin-top: 2px"><b> <?= implode(',', json_decode($order->delivery_address, true)) ?></b></p>

                                   <span style="color: #999"><small>Service Used</small></span>
                                   <p style="margin-top: 2px"><b> <?= $order->service ?></b> </p>

                                   <span style="color: #999"><small>Tracking ID</small></span>
                                   <p style="margin-top: 2px"> <b><?= $order->service ?> </b></p>
                              </div>
                         </td>
                    </tr>
               </table>
          </td>
     </tr>
</table>