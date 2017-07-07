<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl_role_model extends CI_Model {

  /**
   * Get all roles
   *
   * @access public
   * @return object all role details
   */
  function get()
  {
    return $this->db->get('member_role')->result();
  }

  // --------------------------------------------------------------------
  
  /**
   * Get role by id
   * @param int $role_id
   * @access public
   * 
   * @return object role details
   */
  function get_by_id($role_id)
  {
    return $this->db->get_where('member_role', array('id' => $role_id))->row();
  }

  // --------------------------------------------------------------------

  /**
   * Get role by name
   * @param string $role_name
   * @access public
   * 
   * @return object role details
   */
  function get_by_name($role_name)
  {
    return $this->db->get_where('member_role', array('name' => $role_name))->row();
  }

  // --------------------------------------------------------------------

  /**
   * Get roles by user_id
   *
   * @access public
   * @param int $user_id
   * @return object all account roles
   */
  function get_by_user_id($user_id)
  {
    $this->db->select('member_role.*');
    $this->db->from('member_role');
    $this->db->join('rel_account_role', 'member_role.id = rel_account_role.role_id');
    $this->db->where("rel_account_role.user_id = $user_id AND member_role.suspendedon IS NULL");
    
    return $this->db->get()->result();
  }

  // --------------------------------------------------------------------

  /**
   * Get count of users assigned to provided role
   *
   * @access public
   * @param int $role_id
   * @return int user count
   */
  function get_user_count($role_id)
  {
    // SELECT COUNT(user_id) FROM rel_account_role WHERE role_id=1 GROUP BY role_id
    $this->db->select('user_id');
    $this->db->from('rel_account_role');
    $this->db->where('role_id', $role_id);
    $query = $this->db->get();

    return $query->num_rows();
  }

  // --------------------------------------------------------------------

  /**
   * Get roles by user_id
   *
   * @access public
   * @param string $role_name
   * @param int $user_id
   * @return object all account roles
   */
  function has_role($role_name, $user_id)
  {
    $this->db->select('member_role.*');
    $this->db->from('member_role');
    $this->db->join('rel_account_role', 'member_role.id = rel_account_role.role_id');
    $this->db->where("rel_account_role.user_id = $user_id AND member_role.suspendedon IS NULL AND member_role.name = '$role_name'");
    
    return ($this->db->count_all_results() > 0);
  }

  // --------------------------------------------------------------------
  
  /**
   * Update or create role details
   *
   * @access public
   * @param int $role_id
   * @param array $attributes
   * @return integer role id
   */
  function update($role_id, $attributes = array())
  {
    // Update
    if ($this->get_by_id($role_id))
    {
      $this->db->where('id', $role_id);
      $this->db->update('member_role', $attributes);
    }
    // Insert
    else
    {
      $this->db->insert('member_role', $attributes);
      $role_id = $this->db->insert_id();
    }

    return $role_id;
  }

  // --------------------------------------------------------------------

  /**
   * Update role suspended datetime
   *
   * @access public
   * @param int $role_id
   * @return void
   */
  function update_suspended_datetime($role_id)
  {
    $this->load->helper('date');

    $this->db->update('member_role', array('suspendedon' => mdate('%Y-%m-%d %H:%i:%s', now())), array('id' => $role_id));
  }

  // --------------------------------------------------------------------
  
  /**
   * Remove role suspended datetime
   *
   * @access public
   * @param int $role_id
   * @return void
   */
  function remove_suspended_datetime($role_id)
  {
    $this->db->update('member_role', array('suspendedon' => NULL), array('id' => $role_id));
  }

  // --------------------------------------------------------------------

  /**
   * Delete role
   *
   * @access public
   * @param int $role_id
   * @return void
   */
  function delete($role_id)
  {
    $this->db->delete('member_role', array('id' => $role_id));
  }
}