<div class="content padding_0">
     <div  animate-panel>
          <div class="row" ng-controller="courierservicesCtrl">
               <div class="col-lg-12 no-padding">
                    <div class="col-lg-12" ng-show="services_detail">
                         <div class="hpanel">
                              <div class="panel-body" style="padding:20px !important;">
                                   <div class="col-lg-12" style="padding:20px !important;">
                                        <legend><?= lang('service_detail_title') ?>
                                             <span class="btn btn-primary btn-md pull-right col-md-1 back_btn" style="margin-top: -10px;" ng-click="detail_back()">Back</span>
                                             <span class="clr"></span></legend>
                                   </div>  
                              </div> 
                         </div> 
                    </div>
                    <div class="col-lg-12" ng-show="servicelist_content">
                         <div class="hpanel">
                              <div class="panel-body">
                                   <div class="col-lg-12 no-padding margin_bottom_10" >

                                        <div class="clearfix"></div>    
                                        <div class="col-lg-4 border">
                                             <a href="#"><h4>Add New Organisation</h4>
                                             <p>Start using 6Connect by creating your first organisation. An organisation could be your company, clubs, associations or your family,</p> </a>
                                        </div>
                                        <div class="col-lg-3 border">
                                             <a href="#"><h4>Single Order</h4>
                                             <p>Need to make a quick delivery<br> now? Click here to create a new order immediately</p> </a>
                                        </div>
                                        <div class="col-lg-4 border">
                                             <a href="#"><h4>Multiple Orders</h4>
                                             <p>Have more than one deliveries? Click here to request delivery service from one collection<br> point to multiple delivery points</p></a>
                                        </div>
                                   </div>
                                   <div class="col-lg-12">
                                        <div class="col-lg-4">
                                             <div id="container" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>
                                        </div>
                                        <div class="col-lg-8">
                                             <div class="table-responsive">
                                                  <table id="services_list"   class="table table-striped table-bordered table-hover ">
                                                       <thead>
                                                            <tr>
                                                                 <th>Delivery ID</th>
                                                                 <th>Delivery Location</th>
                                                                 <th>Service/Courier</th>
                                                                 <th>Current status</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            <tr>
                                                                 <td>Data1</td>
                                                                 <td></td>
                                                                 <td></td>
                                                                 <td></td>
                                                            </tr>
                                                       </tbody>
                                                  </table>
                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div>
                    </div>               

               </div>
          </div>
     </div>
</div>
<script type="text/javascript">
     $(function () {
    // Create the chart
    $('#container').highcharts({
        chart: {
            type: 'pie',
            width: 300
        },
        
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: false,
                    format: '{point.name}: {point.y:.1f}%',
                }
            }
        },
        title: false,
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        credits: false,
        series: [{
            name: "Brands",
            colorByPoint: true,
            data: [{
                name: "Microsoft Internet Explorer",
                y: 56.33,
                drilldown: "Microsoft Internet Explorer"
            }, {
                name: "Chrome",
                y: 24.03,
                drilldown: "Chrome"
            }, {
                name: "Firefox",
                y: 10.38,
                drilldown: "Firefox"
            }]
        }],
        drilldown: {
            series: [{
                name: "Microsoft Internet Explorer",
                id: "Microsoft Internet Explorer",
                data: [
                    ["v11.0", 24.13]
                ]
            }, {
                name: "Chrome",
                id: "Chrome",
                data: [
                    ["v40.0", 5]
                ]
            }, {
                name: "Firefox",
                id: "Firefox",
                data: [
                    ["v35", 2.76]
                ]
            }]
        }
    });
});
</script>
