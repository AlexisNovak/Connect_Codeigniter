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
                    border-collapse: collapse;
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
               .title{
                    font-size:12pt;
                    border-bottom: 2px solid #000;
               }
          </style>
     </head>
     <body>
          <div class="title">
               <p><?= isset($invoice['title']) ? $invoice['title'] : "" ?></p>
          </div>
          <div>
               <p>Date : <?= isset($invoice['date']) ? $invoice['date'] : "" ?></p>
               <p>Bill To : <?= isset($invoice['user']) ? $invoice['user'] : "" ?></p>
          </div>
          <table>
               <thead>
                    <tr>
                         <?php
                           foreach ($invoice['columns'] as $column) {
                                ?>
                                <td><?= $column ?></td>
                                <?php
                           }
                         ?>
                    </tr>
               </thead>
               <tbody>
                    <?php
                      $total = 0;
                      foreach ($invoice['entry'] as $value) {
                           $total+=$value['price'];
                           ?>
                           <tr>
                                <td><?= $value['day'] ?></td>
                                <td><?= $value['consignment_no'] ?></td>
                                <td><?= $value['org_name'] ?></td>
                                <td><?= $value['collection'] ?></td>
                                <td><?= $value['delivery'] ?></td>
                                <td><?= $value['status'] ?></td>
                                <td style="text-align: right;"><?= "$" . $value['price'] ?></td>
                           </tr>
                           <?php
                      }
                    ?>      
               </tbody>
               <tbody>
                    <tr>
                         <td colspan="6" style="text-align: right;">Total</td>
                         <td style="text-align: right;"><?= "$" . $total ?></td>
                    </tr>
               </tbody>
          </table>
          <div style="width:28%;float:right;margin-left: 2%;margin-top:20px;">
               <p class="pwd_by">Powered By</p>
               <div class="pwd_connect">
                    <img src="<?php echo base_url(); ?>resource/images/6connect.png" height="30px;" style="float:left;">
                    <span style="color:#FFBF00;font-size:10pt;">6</span><span style="color:#35495E;font-size:10pt;">Connect</span><br>
                    <span style="font-size:6pt;">Deliveries simplified for you</span>
               </div>
          </div>
     </body>
</html>