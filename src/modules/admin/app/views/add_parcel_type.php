<div class="content">
     <div  animate-panel>
          <div class="row">
               <div class="col-lg-12" ng-controller="custParcelCtrl">

                    <?php
                      $id = (isset($id)) ? $id : 0;
                    ?>
                    <script>
                    var ID="<?= $id ?>";
                    </script>
                    <div class="hpanel">

                         <div class="panel-body">
                              <div class="col-xs-8 no-padding">

                                   <form name="newTip" class="form-horizontal" ng-submit="newTip.$valid && save_new_type()">
                                        <fieldset>
                                             <div class="form-group">

                                                  <div class="col-sm-12">
                                                       <label>Name</label>
                                                       <input type="text" required ng-model="paytype.display_name" class="form-control" placeholder="Name">
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">
                                                       <label>Description</label>
                                                       <textarea class="form-control" required ng-model="paytype.description" rows="5" placeholder="Content"></textarea>
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12">

                                                       <label>Organisation</label>
                                                       <ui-select id="newordertype" ng-model="paytype.org_id" theme="bootstrap"  ng-disabled="disabled" ng-required="true" style="width:100%">
                                                            <ui-select-match placeholder="Select">{{$select.selected.name}}</ui-select-match>
                                                            <ui-select-choices repeat="org in org_list | filter: {name: $select.search}">
                                                                 <div ng-bind-html="org.name | highlight: $select.search" class="type_title"></div>
                                                            </ui-select-choices>
                                                       </ui-select> 
                                                  </div>
                                             </div>
                                             <div class="form-group">
                                                  <div class="col-sm-12 text-left">
                                                       <button type="submit" class="btn btn-primary"><?= lang('save_btn') ?></button>
                                                       <a ui-sref="cust_parcel_type"><button type="reset" class="btn btn-default" ng-click="cancel_new_tip_form()"><?= lang('cancel_btn') ?></button></a>
                                                  </div>
                                             </div>
                                        </fieldset>
                                   </form>
                              </div>
                              <div class="clearfix"></div>

                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
