<!DOCTYPE html>
<html>
     <head>
          <style>
               body{
                    font-family:Helvetica,Arial,sans-serif;   
                    margin: auto;
                    font-size: 7pt;
                    color:#000;
               }
               table.main{
                    border-collapse: collapse;
                    background-color: #fff;
                    border: 1px solid #000;
               }
               table{                    
                    margin: 10px 0px;
                    width:100%;
               }              
               p{
                    padding: 0px;
                    margin: 0px;
               }
               td{   
                    padding:6px;
                    margin: 0px;
                    background-color: #fff;
                    vertical-align: middle;
                    border: 1px solid #000;
               }
               .cutting_line{
                    height: 0px;
                    border: none;
                    margin:15px 0px;
                    padding: 0;
                    border-top: 1px dashed #aaa;
               }
               .pwd_connect{
                    display: inline-block;
                    vertical-align: middle;
               }
               .pwd_connect img{
                    height:30px;
               }
          </style>
     </head>
     <body>
          <?php foreach ($orders as $order) { ?>
                 <div style="height: 31%;display: block;overflow:hidden;">
                      <?php for ($i = 1; $i < 4; $i++) { ?>
                           <div style="width:50%;float:left;">
                                <p><?= $order->courier_name ?> &nbsp;</p>
                                <p style="font-weight:bold;"><?= $order->service ?> &nbsp;</p>
                                <p style="background: #DBDBDB;">Payment Term:  <?php
                                                         if ($order->payment_type == 4 || $order->payment_type == 8) {
                                                              echo lang('review_credit_info');
                                                         } else {
                                                              if ($order->payment_type == 1)
                                                                   echo "Cash on Collection";
                                                              if ($order->payment_type == 2)
                                                                   echo "Cash on Delivery";
                                                         }
                                                         ?>&nbsp;</p>
                           </div>
                           <div style="width:50%;float:left;text-align: right;">
                                <img height="40px" src="<?= base_url('filebox/ciqrcode/' . $order->consignment_id . '.png'); ?>" style="float:right;margin-left: 10px;">
                                <p>       <strong> <?php echo $order->public_id; ?></strong><br>
                                     <img height="24px" src="<?= base_url('filebox/barcode/consignment_document_' . $order->public_id . '.png'); ?>">

                                </p>                            
                           </div>
                           <table class="main" style="page-break-inside:avoid;">
                                <tr>
                                     <td><strong><?= lang('collection_address') ?></strong></td>
                                     <td>
                                          <?= $order->collection_company_name ?><br>
                                          <?= $order->caddr ?><br>
                                          <?= $order->from_country ?><br>
                                          <?= $order->cpin ?>
                                     </td>
                                     <td><strong><?= lang('delivery_address') ?></strong></td>
                                     <td>
                                          <?= $order->delivery_company_name ?><br>
                                          <?= $order->daddr ?><br>
                                          <?= $order->to_country ?><br>
                                          <?= $order->dpin ?>
                                     </td>
                                </tr>
                                <tr>
                                     <td><strong>Pickup Time</strong></td>
                                     <td><?php echo date("m/d/Y h:m A", strtotime($order->cdate)) . " - " . date("m/d/Y h:m A", strtotime($order->cdate2)); ?></td>
                                     <td><strong>Delivery Time</strong></td>
                                     <td><?php echo date("m/d/Y h:m A", strtotime($order->ddate)) . " - " . date("m/d/Y h:m A", strtotime($order->ddate2)); ?></td>

                                </tr>
                                <tr>
                                     <td><strong>Contact</strong></td>
                                     <td><strong><?= $order->cname ?></strong><br>
                                          <?= $order->cphone ?><br>
                                          <?= $order->cemail ?></td>
                                     <td><strong>Contact</strong></td>
                                     <td><strong><?= $order->dname ?></strong><br>
                                          <?= $order->dphone ?><br>
                                          <?= $order->demail ?></td>
                                </tr>
                                <tr>
                                     <td><strong>Item(s) Description</strong></td> 
                                     <td>Parcel (<?= $order->consignment_type ?>) <br>
                                          x <?= $order->quantity ?> item(s)</td>
                                     <td colspan="2" rowspan="2" style="text-align:center;font-size:10pt;color:#999;vertical-align: middle">
                                          <?php
                                          if ($i == 1) {
                                               echo "Sign/Stamp when collecting";
                                          } else {
                                               echo "Sign/Stamp when delivered.";
                                          }
                                          ?>
                                     </td>
                                </tr>
                                <tr>
                                     <td><strong>Remarks</strong></td> 
                                     <td style="height:40px;overflow:hidden;"><?= $order->remarks ?></td>
                                </tr>
                           </table>
                           <div style="width:70%;float:left;">
                                <p><strong><?= lang('terms_title') ?></strong></p>
                                <p style="font-size:5pt;"><?= lang('terms') ?></p>
                           </div>
                           <div style="width:28%;float:left;margin-left: 2%;">
                                <p class="pwd_by">Powered By</p>
                                <div class="pwd_connect">
                                     <img src="<?php echo base_url(); ?>resource/images/6connect.png" height="30px;" style="float:left;">
                                     <span style="color:#FFBF00;font-size:10pt;">6</span><span style="color:#35495E;font-size:10pt;">Connect</span><br>
                                     <span style="font-size:6pt;">Deliveries simplified for you</span>
                                </div>
                           </div>
                           <?php if ($i != 3) {
                                ?>
                                <div class="cutting_line"></div>
                                <?php
                           }
                           ?>
                      <?php } ?>
                 </div>

            <?php } ?>
     </body>
</html>