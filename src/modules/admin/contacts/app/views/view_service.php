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
                                <?php foreach ($service as $key => $value) { ?>
                                	<tr>
										<td style="width: 300px; font-weight: bold; text-transform: capitalize;"><?= $key ?></td>
										<td><?= $value ?></td>
									</tr>
								<?php } ?>
								</table>
							</div>
							<div class="col-lg-12 m-t-lg">
								<h4><?= lang('parcel_type_title') ?></h4>
								<table class="table table-bordered table-striped">
									<tr>
										<td style="width: 300px"><?= lang('parcel_type') ?></td>
										<td style=""><?= lang('parcel_type_price') ?></td>
									</tr>
									<tr ng-repeat="parceltype in parcelTypePrices">
										<td>{{ parceltype.display_name }}</td>
										<td>
											<div ng-show="parceltype.type != '0'">${{ parceltype.price || 'Unset' }}</div>
											<div ng-show="parceltype.type == '0'">
												<?php echo lang('parcel_type_max_cubic_volume');?>: <b>{{ parceltype.max_volume }}</b><br />
												<?php echo lang('parcel_type_cubic_volume_cost');?>: <b>${{ parceltype.volume_cost }}</b><br />
												<?php echo lang('parcel_type_max_weight');?>: <b>{{ parceltype.max_weight }}</b><br />
												<?php echo lang('parcel_type_weight_cost');?>: <b>${{ parceltype.weight_cost }}</b><br />
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-lg-12 m-t-lg">
								<h4><?=lang('surcharge_items')?></h4>
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th style="width: 20%"><?=lang('s_item_name')?></th>
											<th style="width: 15%"><?=lang('s_item_price')?></th>
											<th><?= lang('s_item_remarks') ?></th>
										</tr>
									</thead>
                                             <?php
                                               foreach ($items as $item) {
                                                    ?>
                                                    <tr>
										<td><?= $item->name ?></td>
										<td>$<?= number_format($item->price, 2) ?></td>
										<td><?= $item->remarks ?></td>
									</tr>
                                                    <?php
                                               }
                                               if (count($items) == 0) {
                                                    ?>
                                                    <tr class="no-data">
										<td colspan="3"><?= lang('nothing_to_display') ?></td>
									</tr>
                                                    <?php
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