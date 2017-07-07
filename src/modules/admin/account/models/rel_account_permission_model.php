<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rel_account_permission_model extends CI_Model {

  /**
   * Get all override account permissions
   *
   * @access public
   * @return object all account permissions
   */
  function get()
  {
    return $this->db->get('member_permission')->result();
  }

  /**
   * Get account details by user_id
   *
   * @access public
   * @param int $user_id
   * @return object account details object
   */
  function get_by_user_id($user_id)
  {
    $this->db->select('member_permission.*');
    $this->db->from('member_permission');
    $this->db->join('member_permission', 'member_permission.permission_id = member_permission.id');
    $this->db->where("member_permission.user_id = $user_id AND member_permission.suspendedon IS NULL");

    return $this->db->get()->result();
  }

  /**
   * Check if account already has this permission assigned
   *
   * @access public
   * @param int $user_id
   * @param int $permission_id
   * @return object account details object
   */
  function exists($user_id, $permission_id)
  {
    $this->db->from('member_permission');
    $this->db->where('user_id', $user_id);
    $this->db->where('permission_id', $permission_id);

    return ( $this->db->count_all_results() > 0 );
  }

  // --------------------------------------------------------------------
  
  /**
   * Create a new account permission
   *
   * @access public
   * @param int $user_id
   * @param int $permission_id
   * @return void
   */
  function update($user_id, $permission_id)
  {
    // Insert
    if (!$this->exists($user_id, $permission_id))
    {
      $this->db->insert('member_permission', array('user_id' => $user_id, 'permission_id' => $permission_id));
    }
  }

  /**
   * Delete single instance by account/permission
   *
   * @access public
   * @param int $user_id
   * @param int $permission_id
   * @return void
   */
  function delete($user_id, $permission_id)
  {
    $this->db->delete('member_permission', array('user_id' => $user_id, 'permission_id' => $permission_id));
  }



  /**
   * Delete all permissions for account
   *
   * @access public
   * @param int $user_id
   * @return void
   */
  function delete_by_account($user_id)
  {
    $this->db->delete('member_permission', array('user_id' => $user_id));
  }



  /**
   * Delete all by permissions by id
   *
   * @access public
   * @param int $permission_id
   * @return void
   */
  function delete_by_permission($permission_id)
  {
    $this->db->delete('member_permission', array('permission_id' => $permission_id));
  }
}