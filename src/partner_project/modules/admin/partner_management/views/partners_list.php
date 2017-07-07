<div class="content">
     <div animate-panel>
          <div class="row" ng-controller="partnerCtrl">
               <div class="col-lg-12">
                    <div class="hpanel">
                         <div class="panel-body">                             

                              <div class="col-lg-12 no-padding margin_bottom_10 search_toolbar">
                                   <!-- angular popup starts -->
                                   <div class="angular_popup create_org pull-right"  ng-show="create_partner_form">  
                                        <h3><?= lang("create_new_partner") ?><i class="fa fa-close pull-right" ng-click="cancel_create_partner()"></i></h3>
                                        <div class="form-holder">
                                             <form class="form-horizontal" name="newPartner" ng-submit="newPartner.$valid && add_partner()">
                                                  <fieldset>
                                                       <div class="form-group">
                                                            <div class="col-sm-12">
                                                                 <input type="text" class="form-control" placeholder="<?= lang('partner_name') ?>" ng-model="partner.p_name" required>    
                                                                 <span class="help-inline text-danger" ng-show="errors.p_name_error">
                                                                      {{errors.p_name_error}}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <div class="col-sm-12">
                                                                 <input type="text" class="form-control" placeholder="<?= lang('partner_url') ?>" ng-model="partner.p_url" required>    
                                                                 <span class="help-inline text-danger" ng-show="errors.p_url_error">
                                                                      {{errors.p_url_error}}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <div class="col-sm-12">
                                                                 <input type="text" class="form-control" placeholder="<?= lang('partner_shortname') ?>" ng-model="partner.p_shortname" ng-disabled="partner.partner_id" required>    
                                                                 <span class="help-inline text-danger" ng-show="errors.p_shortname_error">
                                                                      {{errors.p_shortname_error}}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <div class="col-sm-12">
                                                                 <select class="form-control" ng-model="partner.p_domain" required> 
                                                                      <option value="">select domain</option>
                                                                      <option value="http">HTTP only</option>
                                                                      <option value="https">HTTPS only</option>
                                                                      <option value="both">Both HTTP & HTTPS</option>
                                                                 </select>   
                                                                 <span class="help-inline text-danger" ng-show="errors.p_domain_error">
                                                                      {{errors.p_domain_error}}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <div class="col-sm-12">
                                                                 <p ng-if="partner.p_color_path"><a ng-href="{{partner.p_color_path}}" target="_blank"><i class="glyphicon glyphicon-file"></i> {{partner.p_color_name}}</a></p>
                                                                 <input type="text" class="form-control hidden" id="color_scheme" ng-model="partner.p_color">    
                                                                 <div id="color_css" class="dropzone"></div>
                                                                 <span class="help-inline text-danger" ng-show="errors.p_color_error">
                                                                      {{errors.p_color_error}}
                                                                 </span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <div class="col-sm-12 text-right">
                                                                 <button type="submit" class="btn btn-primary"><?php echo lang('save_btn'); ?></button>
                                                                 <button type="button" class="btn btn-small" ng-click="cancel_create_partner()"><?= lang('cancel_btn') ?></button>
                                                            </div>
                                                       </div>
                                                  </fieldset>
                                             </form>
                                        </div>
                                   </div>
                                   <!-- ends angular popup -->
                                   <div class="clearfix"></div>
                                   <div class="pull-left">
                                        <div  class="dataTables_length">
                                             <form>
                                                  <label>
                                                       Show
                                                       <select class="form-control"  name="perpage" ng-model="partnerlistdata.perpage"  
                                                               ng-options="partnerperpages as partnerperpages.label for partnerperpages in partnerperpage" ng-change="perpagechange()">
                                                            <option style="display:none" value class>15</option>
                                                       </select>
                                                       entries
                                                  </label>
                                             </form>
                                        </div>
                                   </div>
                                   <div class="pull-right no-padding">
                                        <div class="table_filter" id="schemes_list_filter" style="display: inline-flex;" >
                                             <form>
                                                  <label class=" pull-left no-padding " style="padding-right:2px !important;"><?= lang('search_label') ?></label>
                                                  <span class="no-padding table_filter" style="display: inline-flex;">
                                                       <input ng-change="findpartner()" aria-controls="partner_list"  class="form-control input-sm" type="search" ng-model="partnerlistdata.filter">
                                                  </span>
                                             </form>
                                        </div>
                                        <div class="no-padding table_filter" style="display: inline-flex;"><span ng-click="create_partner()" class="btn btn-sm btn-info"><?= lang('new_partner') ?></span></div>
                                   </div>


                              </div>
                              <div class="clearfix"></div>
                              <div class="clr"></div>
                              <div class="angular_popup add_member pull-right"  ng-show="assign_member_form">  
                                   <h3><?= lang('assign_puser') ?><i class="fa fa-close pull-right" ng-click="cancel_assign_member()"></i></h3>
                                   <div class="form-horizontal">
                                        <div class="form-holder">
                                             <div class="form-group" style="margin-bottom: 10px !important;">
                                                  <div class="col-sm-12">
                                                       <input type="text" class="form-control" ng-model="searchname" placeholder="eg@example.com" required  ng-change="getall()"> 
                                                       <div ng-show="isSearch" style="background-color: #fff;max-height: 170px;overflow:auto;">
                                                            <ul class="list-group" style="margin-bottom: 0px !important;">
                                                                 <li ng-repeat="mem in members track by $index" ng-click="setMember(mem)" class="list-group-item list-group-item-info" >
                                                                      <a>{{mem.Username}} ({{mem.Email}})</a>
                                                                 </li>

                                                            </ul>
                                                       </div>
                                                  </div>
                                             </div>
                                             <p ng-if="isInvite" class="field_error"><?= lang('unknown_member') ?></p>
                                             <div class="col-sm-12 no-padding">
                                                  <button type="button" class="btn btn-primary" ng-click="assign_user()" ng-disabled="isDisabled"><?= lang('add_btn') ?></button>
                                                  <button type="button" class="btn btn-default" ng-click="cancel_assign_member()"><?= lang('cancel_btn') ?></button>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="table-responsive">
                                   <table id="partner_list" class="table table-striped table-bordered table-responsive">
                                        <thead>
                                             <tr>
     <!--                                                  <th><?= lang('partner_id') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.partner_id.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.partner_id.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.partner_id.reverse == false}" class="pull-right" ng-click="sort('partner_id')"></i>  
                                                  </th>-->
                                                  <th><?= lang('partner_name') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.partner_name.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.partner_name.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.partner_name.reverse == false}" class="pull-right" ng-click="sort('partner_name')"></i>  
                                                  </th>
                                                  <th><?= lang('partner_shortname') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.shortname.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.shortname.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.shortname.reverse == false}" class="pull-right" ng-click="sort('shortname')"></i>  
                                                  </th>
                                                  <th style="width:30%"><?= lang('partner_domain') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.domain.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.domain.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.domain.reverse == false}" class="pull-right" ng-click="sort('domain')"></i>  
                                                  </th>
                                                  <th><?= lang('partner_url') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.partner_url.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.partner_url.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.partner_url.reverse == false}" class="pull-right" ng-click="sort('partner_url')"></i>  
                                                  </th>
                                                  <th><?= lang('partner_user') ?>
                                                       <i ng-class="{'glyphicon glyphicon-sort':partnerheaders.username.reverse == undefined, 'glyphicon glyphicon-sort-by-attributes-alt': partnerheaders.username.reverse == true, 'glyphicon glyphicon-sort-by-attributes': partnerheaders.username.reverse == false}" class="pull-right" ng-click="sort('username')"></i>  
                                                  </th>
                                                  <th><?= lang('action') ?>
                                                  </th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr ng-repeat="partner in partnerlist|orderBy:orderByField:reverseSort">
                                                  <!--<td><a ui-sref="view_partner({partner_id:{{partner.partner_id}}})" class="link_color"> {{partner.partner_id}}</a></td>-->
                                                  <td><a ui-sref="view_partner({partner_id:{{partner.partner_id}}})" class="link_color"> {{partner.partner_name}}</a></td>
                                                  <td>{{partner.shortname}}</td>
                                                  <td>
                                                       <span ng-if="partner.domain == 'http'">HTTP only</span>
                                                       <span ng-if="partner.domain == 'https'">HTTPS only</span>
                                                                                    <span ng-if="partner.domain == 'both'">Both HTTP & HTTPS</span>
                                                  </td>
                                                  <td>{{partner.partner_url}}</td>
                                                  <td>
                                                                                    <span ng-if="partner.username">{{partner.username}}({{partner.email}})</span>
                                                       <span ng-if="!partner.username">-</span>
                                                  </td>
                                                  <td>
                                                                    <span class="btn btn-sm btn-default" ng-click="show_assign_user(partner.partner_id)" ng-if="!partner.username"><?= lang('assign_puser') ?></span>
                                                       <span class="btn btn-sm btn-default" ng-click="show_assign_user(partner.partner_id)" ng-if="partner.username"><?= lang('change_puser') ?></span>
                                                  </td>
                                             </tr>

                                             <tr class="no-data">
                                                  <td colspan="6"><?= lang('nothing_to_display') ?></td>
                                             </tr>
                                        </tbody>
                                   </table>
                              </div>
                              <div class="col-md-12 no-padding">
                                   <div class="col-md-4 no-padding">
                                        <div ng-show="total" style="line-height: 35px;">Showing {{start}} to {{end}} of {{total}} entries</div>
                                   </div> 
                                   <div class="col-md-8 text-right no-padding">

                                        <paging
                                             class="small"
                                             page="partnerlistdata.currentPage" 
                                             page-size="partnerlistdata.perpage_value" 
                                             total="partnerlistdata.total"
                                             adjacent="{{adjacent}}"
                                             dots="{{dots}}"
                                             scroll-top="{{scrollTop}}" 
                                             hide-if-empty="false"
                                             ul-class="{{ulClass}}"
                                             active-class="{{activeClass}}"
                                             disabled-class="{{disabledClass}}"
                                             show-prev-next="true"
                                             paging-action="getPartners(page)">
                                        </paging> 
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>
     </div>
</div>
<script>
     $(function () {
          var myDropzone = new Dropzone("#color_css", {
               url: BASE_URL + "partner_management/upload_file",
               dictDefaultMessage: "<?= lang('partner_color_info') ?>",
               addRemoveLinks: true,
               acceptedFiles: ".css",
               init: function () {
                    this.on("addedfile", function (file) {
                         if (myDropzone.files.length > 1) {
                              myDropzone.removeFile(myDropzone.files[0]);
                         }
                    });
                    this.on("removedfile", function (file) {
                         $("#color_scheme").val("");
                         $("#color_scheme").trigger('input');
                    });
                    this.on("success", function (response, result) {
                         if (JSON.parse(result).files !== undefined) {
                              $("#color_scheme").val(JSON.parse(result).files);
                              $("#color_scheme").trigger('input');
                         } else {
                              myDropzone.removeFile(myDropzone.files[myDropzone.files.length - 1]);
                         }
                    });
               }
          });
     });
</script>