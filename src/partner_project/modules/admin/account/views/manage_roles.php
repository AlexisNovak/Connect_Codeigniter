
    <div class="span10">

      <h2><?php echo lang('roles_page_name'); ?></h2>

      <div class="well">
        <?php echo lang('roles_page_description'); ?>
      </div>

      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th><?php echo lang('roles_column_role'); ?></th>
            <th><?php echo lang('roles_column_users'); ?></th>
            <th><?php echo lang('roles_permission'); ?></th>
            <th>
              <?php if( $this->authorization->is_permitted('create_roles') ): ?>
                 <a ui-sref="manage_roles_save" class="btn btn-primary btn-small"><?=lang('website_create')?></a>
              <?php endif; ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( $roles as $role ) : ?>
            <tr>
              <td><?php echo $role['id']; ?></td>
              <td>
                <?php echo $role['name']; ?>
                <?php if( $role['is_disabled'] ): ?>
                  <span class="label label-important"><?php echo lang('roles_banned'); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <?php if( $role['user_count'] > 0 ) : ?>
                  <?php echo anchor('account/manage_users/filter/role/'.$role['id'], $role['user_count'], 'class="badge badge-info"'); ?>
                <?php else : ?>
                  <span class="badge">0</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if( count($role['perm_list']) == 0 ) : ?>
                  <span class="label">No Permissions</span>
                <?php else : ?>
                  <ul class="inline">
                    <?php foreach( $role['perm_list'] as $itm ) : ?>
                       <li> <a ui-sref="manage_permissions_save({id:<?php echo $itm['id']?>})" title="<?=$itm['title']?>"><?=$itm['key']?></a></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </td>
              <td>
                <?php if( $this->authorization->is_permitted('update_roles') ): ?>
                   <a ui-sref="manage_roles_save({id:<?=$role['id']?>})" class="btn btn-small"><?=lang('website_update')?></a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>