<!DOCTYPE html>
<html>
     <head>
          <style>
               body{
                    width:1000px;
                    margin: auto;
               }
               table{
                    width: 900px;
                    margin: 20px auto;
                    border-collapse:collapse;
                    background-color: #fff;
               }
               td{
                    width:150px; 
                    border: 1px solid #ddd;
                    padding: 10px;
                    background-color: #f9f9f9;
               }             
               
          </style>
     </head>
     <body>
           <table>
               <tr>
                    <td colspan="3" style="text-align: center;font-weight: bold;"><?php echo $performance['title'];?></td>
               </tr>
               <tr>
                    <td><?php echo $performance['columns'][0]; ?></td>
                    <td><?php echo $performance['columns'][1]; ?></td>
                    <td><?php echo $performance['columns'][2]; ?></td>
               </tr>
              <?php
               foreach ($performance['entry'] as $key=>$value) {
                 ?>
     <tr>
          <td><?php echo $value['label']; ?></td>
          <td><?php echo $value['success']; ?></td>
          <td><?php echo $value['failed']; ?></td> 
     </tr>
                      <?php   
               }
                 ?>
          </table>
           
     </body>
</html>