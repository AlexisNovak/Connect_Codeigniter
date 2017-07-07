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
                                   <div class="col-lg-12">
                                        <legend><?= lang('service_detail_title') ?></legend>
                                        <table class="table table-bordered table-striped">
                                             <?php
                                               foreach ($service as $key => $value) {
                                                    ?>
                                                    <tr>
                                                         <td
                                                              style="width: 300px; font-weight: bold; text-transform: capitalize;"><?= (strtolower($key) == 'is_public') ? "List as Public Service" : $key ?></td>
                                                         <td><?= (strtolower($key) == 'is_public') ? (($value == 1) ? "Yes" : "No") : $value ?></td>
                                                    </tr>
                                                    <?php
                                               }
                                             ?>
                                        </table>
                                   </div>
                                   <div class="col-lg-12 m-t-lg">
                                        <legend><?= lang('service_payment_terms') ?></legend>                                       
                                        <div class="col-xs-12">
                                             <span style="float: left;margin:auto 10px;"> 
                                                  <input type="checkbox" icheck ng-model="payment.credit"> 
                                             </span>
                                             <p><?= lang('credit_terms') ?></p>                                              

                                        </div>
                                        <div class="col-xs-12">
                                             <span style="float: left;margin:auto 10px;"> 
                                                  <input type="checkbox" icheck ng-model="payment.credit_direct"> 
                                             </span>
                                             <p><?= lang('credit_terms_direct') ?></p> 
                                        </div>
                                        <div class="col-xs-12">
                                             <span style="float: left;margin:auto 10px;"> 
                                                  <input type="checkbox" icheck ng-model="payment.sender"> 
                                             </span>
                                             <p><?= lang('cash_sender') ?></p> 
                                        </div>
                                        <div class="col-xs-12">
                                             <span style="float: left;margin:auto 10px;"> 
                                                  <input type="checkbox" icheck ng-model="payment.recipient"> 
                                             </span>
                                             <p><?= lang('cash_recipient') ?></p> 
                                        </div>
                                        <div class="col-xs-12">
                                             <button class="btn btn-info btn-sm" type="button" ng-click="update_payment()"><?= lang('save_btn') ?></button>
                                        </div>
                                   </div>
                                   <div class="col-lg-12 m-t-lg">
                                        <legend><?= lang('parcel_type_title') ?></legend>
                                        <?php if ($service['is_public'] == '1' || $service['is_public'] == '0') { ?>
                                               <div class="col-sm-6">
                                                    <form class="form-horizontal">
                                                         <div class="form-group">
                                                              <label class="control-label col-sm-3"><?php echo lang('parcel_type'); ?></label>
                                                              <div class="col-sm-9">
                                                                   <div class="form-group custom-select2">
                                                                        <ui-select ng-model="selectedParcelType.selected" theme="select2" ng-disabled="disabled" ng-change="selectParcel()" required style="width:100%">
                                                                             <ui-select-match placeholder="<?php echo lang('parcel_type_selection'); ?>">{{$select.selected.display_name}}</ui-select-match>
                                                                             <ui-select-choices repeat="ordertype in typelist | filter: {display_name: $select.search}">
                                                                                  <div ng-bind-html="ordertype.display_name | highlight: $select.search" class="type_title"></div>
                                                                                  <span ng-bind-html="ordertype.description | highlight: $select.search"></span>
                                                                             </ui-select-choices>
                                                                        </ui-select>
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.type">{{ errors.type}}</span>
                                                                   </div>
                                                              </div>
                                                         </div>
                                                         <div class="form-group">
                                                              <label class="control-label col-sm-3"><?php echo lang('parcel_type_price'); ?></label>
                                                              <div class="col-sm-4" style="padding:0">
                                                                   <div ng-show="insertedPrice.type == '0'">
                                                                        <?php echo lang('parcel_type_max_cubic_volume'); ?>:
                                                                        <input type="text" ng-model="insertedPrice.maxVolume" class="form-control" />
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.maxVolume">{{ errors.maxvolume}}</span>
                                                                        <?php echo lang('parcel_type_cubic_volume_cost'); ?>:
                                                                        <input type="text" ng-model="insertedPrice.cubicCost" class="form-control" />
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.cubicCost">{{ errors.cubicCost}}</span>
                                                                        <?php echo lang('parcel_type_max_weight'); ?>:
                                                                        <input type="text" ng-model="insertedPrice.maxWeight" class="form-control" />
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.maxWeight">{{ errors.maxWeight}}</span>
                                                                        <?php echo lang('parcel_type_weight_cost'); ?>:
                                                                        <input type="text" ng-model="insertedPrice.weightCost" class="form-control" />
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.weightCost">{{ errors.weightCost}}</span>
                                                                   </div>
                                                                   <div ng-show="insertedPrice.type != '0'">
                                                                        <input type="text" ng-model="insertedPrice.price" class="form-control" />
                                                                        <span class="help-block m-b-none text-danger" ng-show="errors.price">{{ errors.price}}</span>
                                                                   </div>
                                                              </div>
                                                         </div>
                                                         <div class="form-group">
                                                              <label class="control-label col-sm-3"></label>
                                                              <div class="col-sm-9" style="padding:0">
                                                                   <button class="btn btn-info btn-sm" ng-click="setPrice()"><?= lang('parcel_type_add_price') ?></button>
                                                              </div>
                                                         </div>
                                                    </form>
                                               </div>
                                          <?php } ?>
                                        <table class="table table-bordered table-striped">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 20%"><?= lang('parcel_type') ?></th>
                                                       <th style="width: 10%"><?= lang('parcel_type_price') ?></th>
                                                       <th style="width: 20%"><?= lang('action') ?></th>
                                                  </tr>


                                             <tbody>
                                                  <tr ng-repeat="parceltype in parcelTypePrices">
                                                       <td>
                                                            {{ parceltype.display_name}}
                                                       </td>
                                                       <td>
                                                            <div ng-show="parceltype.type != '0'">${{ parceltype.price || 'Unset' }}</div>
                                                            <div ng-show="parceltype.type == '0'">
                                                                 <?php echo lang('parcel_type_max_cubic_volume'); ?>: <b>{{ parceltype.max_volume}}</b><br />
                                                                 <?php echo lang('parcel_type_cubic_volume_cost'); ?>: <b>${{ parceltype.volume_cost}}</b><br />
                                                                 <?php echo lang('parcel_type_max_weight'); ?>: <b>{{ parceltype.max_weight}}</b><br />
                                                                 <?php echo lang('parcel_type_weight_cost'); ?>: <b>${{ parceltype.weight_cost}}</b><br />
                                                            </div>
                                                       </td>
                                                       <td style="white-space: nowrap">
                                                            <?php if ($service['is_public'] == '1' || $service['is_public'] == '0') { ?>
                                                                   <div class="buttons" ng-show="!rowform.$visible">
                                                                        <span class="text-danger" ng-click="removePrice(parceltype, $index)">
                                                                             <i class="fa fa-trash"></i>
                                                                        </span>
                                                                   </div>
                                                              <?php } ?>
                                                       </td>
                                                  </tr>
                                             </tbody>
                                             </thead>
                                        </table>
                                   </div>
                                   <div class="col-lg-12 m-t-lg">
                                        <legend><?= lang('surcharge_items') ?></legend>

                                        <table class="table table-bordered table-striped">
                                             <thead>
                                                  <tr>
                                                       <th style="width: 20%"><?= lang('s_item_name') ?></th>
                                                       <th style="width: 10%"><?= lang('s_item_price') ?></th>
                                                       <th><?= lang('s_item_remarks') ?></th>
                                                       <th><?= lang('s_item_location') ?></th>
                                                       <th style="width: 20%"><?= lang('action') ?></th>
                                                  </tr>


                                             <tbody>
                                                  <tr ng-repeat="item in surchargeitems">
                                                       <td><span editable-text="item.name" e-name="name"
                                                                 e-form="rowform" e-required> {{ item.name || 'empty' }} </span>
                                                       </td>
                                                       <td><span editable-text="item.price" e-name="price"
                                                                 e-form="rowform" e-required> {{ item.price}} </span>
                                                       </td>
                                                       <td><span editable-textarea="item.remarks" e-name="remarks"
                                                                 e-form="rowform" e-rows="4" e-cols="50" e-required> {{
                                                                           item.remarks || 'empty' }} </span></td>
                                                       <td><span editable-select="item.location" e-name="location"
                                                                 e-form="rowform" e-ng-options="s.value as s.text for s in locations"> {{
                                                                           item.location_name || 'NA' }} </span></td>
                                                       <td style="white-space: nowrap">
                                                            <!-- form -->
                                                            <form editable-form name="rowform"
                                                                  onbeforesave="saveItem($data,item.id)"
                                                                  ng-show="rowform.$visible" class="form-buttons form-inline"
                                                                  shown="inserted == item">
                                                                 <button type="submit" ng-disabled="rowform.$waiting"
                                                                         class="btn btn-primary btn-sm">
                                                                              <?= lang('save_btn') ?>
                                                                 </button>
                                                                 <button type="button" ng-disabled="rowform.$waiting"
                                                                         ng-click="cancel_item(rowform, $index)"
                                                                         class="btn btn-sm btn-default">
                                                                              <?= lang('cancel_btn') ?>
                                                                 </button>
                                                            </form>
                                                            <div class="buttons" ng-show="!rowform.$visible">
                                                                 <span class="text-primary" ng-click="rowform.$show()"><i
                                                                           class="fa fa-edit"></i></span> <span class="text-danger"
                                                                                                      ng-click="removeItem(item, $index)"><i class="fa fa-trash"></i></span>
                                                            </div>
                                                       </td>
                                                  </tr>
                                             </tbody>
                                             </thead>
                                        </table>
                                        <div class="no-padding table_filter"
                                             style="display: inline-flex;">
                                             <button class="btn btn-info btn-sm" ng-click="addItem()"><?= lang('add_item') ?></button>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>