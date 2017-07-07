<div class="content">
     <div animate-panel>
          <div class="row" ng-controller="reportsCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">                            
                              <div class="col-lg-12 no-padding margin_bottom_10">
                                   <div>
                                        <h2>Reports</h2>
                                        <!--<canvas radarchart options="radarOptions" data="radarData" responsive=true height="200"></canvas>-->
                                   </div>
                                   <div>
                                        <!--<canvas doughnutchart options="doughnutOptions" data="doughnutData" height="140" responsive=true></canvas>-->
                                   </div>
                                   <div>
                                        <div class="col-lg-6 clearfix" style="height: 30px;">
                                             <canvas barchart options="barOptions" data="barData" height="140" responsive=true ></canvas>
                                        </div>
                                        <div class="col-lg-6 clearfix">
                                             <canvas linechart options="sharpLineOptions" data="sharpLineData" height="140" responsive=true ></canvas>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>