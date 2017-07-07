<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rel_role_permission_model extends CI_Model {

  /**
   * Get all role permissions
   *
   * @access public
   * @return object all account permissions
   */
  function get()
  {
    return $this->db->get('role_permission')->result();
  }

  /**
   * Get permission details by role id
   *
   * @access public
   * @param int $role_id
   * @return object account details object
   */
  function get_by_role_id($role_id)
  {
    $this->db->select('member_permission.*');
    $this->db->from('role_permission');
    $this->db->join('member_permission', 'role_permission.permission_id = member_permission.id');
    $this->db->where("role_permission.role_id = $role_id AND member_permission.suspendedon IS NULL");

    return $this->db->get()->result();

    return $this->db->get()->result();
  }



  /**
   * Get role details by permission id
   *
   * @access public
   * @param int $permission_id
   * @return object account details object
   */
  function get_by_permission_id($permission_id)
  {
    $this->db->select('member_role.*');
    $this->db->from('role_permission');
    $this->db->join('member_role', 'role_permission.role_id = member_role.id');
    $this->db->where("role_permission.permission_id = $permission_id AND member_role.suspendedon IS NULL");

    return $this->db->get()->result();
  }

  /**
   * Check if role already has this permission assigned
   *
   * @access public
   * @param int $user_id
   * @param int $permission_id
   * @return object account details object
   */
  function exists($role_id, $permission_id)
  {
    $this->db->from('role_permission');
    $this->db->where('role_id', $role_id);
    $this->db->where('permission_id', $permission_id);

    return ( $this->db->count_all_results() > 0 );
  }

  // --------------------------------------------------------------------
  
  /**
   * Create a new account permission
   *
   * @access public
   * @param int $role_id
   * @param int $permission_id
   * @return void
   */
  function update($role_id, $permission_id)
  {
    // Insert
    if (!$this->exists($role_id, $permission_id))
    {
      $this->db->insert('role_permission', array('role_id' => $role_id, 'permission_id' => $permission_id));
    }
  }


  /**
   * Batch update role permissions.
   *
   * @access public
   * @param int $role_id
   * @param array $permission_ids
   * @return void
   */
  function update_batch($role_id, $permission_ids)
  {
    // Blank array, then no insert for you
    if( count($permission_ids) > 0)
    {
      // Create a new batch
      $batch = array();
      foreach($permission_ids as $permission_id)
      {
        $batch[] = array(
          'role_id' => $role_id,
          'permission_id' => $permission_id
          );
      }

      // Insert all the new roles
      $this->db->insert_batch('role_permission', $batch);
    }
  }

  /**
   * Delete all current permissions and replace with array of permissions passed in.
   *
   * @access public
   * @param int $role_id
   * @param array $permission_ids
   * @return void
   */
  function delete_update_batch($role_id, $permission_ids)
  {
    // Delete all current roles
    $this->delete_by_role($role_id);

    // Batch update the account roles
    $this->update_batch($role_id, $permission_ids);
  }

  /**
   * Delete single instance by account/permission
   *
   * @access public
   * @param int $role_id
   * @param int $permission_id
   * @return void
   */
  function delete($role_id, $permission_id)
  {
    $this->db->delete('role_permission', array('role_id' => $role_id, 'permission_id' => $permission_id));
  }


  /**
   * Delete all by role id
   *
   * @access public
   * @param int $permission_id
   * @return void
   */
  function delete_by_role($role_id)
  {
    $this->db->delete('role_permission', array('role_id' => $role_id));
  }


  /**
   * Delete all by permissions id
   *
   * @access public
   * @param int $permission_id
   * @return void
   */
  function delete_by_permission($permission_id)
  {
    $this->db->delete('role_permission', array('permission_id' => $permission_id));
  }
}