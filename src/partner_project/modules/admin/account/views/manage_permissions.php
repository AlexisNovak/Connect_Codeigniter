
<div class="span10">

     <h2><?php echo lang('permissions_page_name'); ?></h2>

     <div class="well">
          <?php echo lang('permissions_page_description'); ?>
     </div>

     <table class="table table-condensed table-hover">
          <thead>
               <tr>
                    <th>#</th>
                    <th><?php echo lang('permissions_column_permission'); ?></th>
                    <th><?php echo lang('permissions_description'); ?></th>
                    <th><?php echo lang('permissions_column_inroles'); ?></th>
                    <th>
                         <?php if ($this->authorization->is_permitted('create_users')): ?>
                                <a ui-sref="manage_permissions_save" class="btn btn-primary btn-small">Create<a>
                                     <?php endif; ?>
                                   </th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                        <?php foreach ($permissions as $perm) : ?>
                                               <tr>
                                                    <td><?php echo $perm['id']; ?></td>
                                                    <td>
                                                         <?php echo $perm['key']; ?>
                                                         <?php if ($perm['is_disabled']): ?>
                                                              <span class="label label-important"><?php echo lang('permissions_banned'); ?></span>
                                                         <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $perm['description']; ?></td>
                                                    <td>
                                                         <?php if (count($perm['role_list']) == 0) : ?>
                                                              <span class="label">None</span>
                                                         <?php else : ?>
                                                              <ul class="inline">
                                                                   <?php foreach ($perm['role_list'] as $itm) : ?>
                                                                        <li>  <a ui-sref="manage_roles_save({id:<?= $itm['id'] ?>})" title="<?= $itm['title'] ?>"><?= $itm['name'] ?></a></li>
                                                                   <?php endforeach; ?>
                                                              </ul>
                                                         <?php endif; ?>
                                                    </td>
                                                    <td>
                                                         <?php if ($this->authorization->is_permitted('update_permissions')): ?>
                                                              <a ui-sref="manage_permissions_save({id:<?php echo $perm['id'] ?>})" class="btn btn-small"><?= lang('website_update') ?></a>
                                                         <?php endif; ?>
                                                    </td>
                                               </tr>
                                          <?php endforeach; ?>
                                   </tbody>
                                   </table>

                                   </div>