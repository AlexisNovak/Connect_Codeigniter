<?php

  if (!defined('BASEPATH'))
       exit('No direct script access allowed');

  class Options_model extends MY_Model {

       public function __construct() {
            parent::__construct('options', 'id');
       }

       private function _optionExists($name, $group) {
            return $this->countWhere(array('name' => $name, 'group' => $group)) > 0;
       }

       public function addOption($name, $value = '', $group = 'default_unload') {
            $this->setOption($name, $value, $group);
       }

       public function deleteOption($name, $group = 'default_unload') {
            $this->deleteWhere(array('name' => $name, 'group' => $group));
       }

       public function getOption($name, $default = false, $group = 'default_unload') {
            $option = $this->getOneWhere(array('name' => $name, 'group' => $group));
            return $option ? unserialize($option->value) : $default;
       }

       public function setOption($name, $value, $group = 'default_unload') {
            if (is_array($value))
                 $value = serialize($value);

            if (!$this->_optionExists($name, $group))
                 return $this->insertRow(array('name' => $name, 'value' => $value, 'group' => $group));
            else
                 return $this->updateWhere(array('name' => $name, 'group' => $group), array('value' => $value));
       }

       public function addGroupOptions($names, $group = 'default_unload') {
            
       }

       public function deleteGroupOption($group = 'default_unload') {
            
       }

       public function getGroupOption($group = 'default_unload') {
            
       }

       public function setGroupOption($data, $group = 'default_unload') {
            
       }

       public function get_modules() {

            return $this->db->select('*')
                            ->from(IMS_DB_PREFIX . 'module')
                            ->get()->result();
       }

  }
  