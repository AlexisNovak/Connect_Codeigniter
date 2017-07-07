<?php

  class Tags_model extends CI_Model {

       function __construct() {
            parent::__construct();
       }

       public function get_tags($filter, $category = NULL) {
            if ($category !== NULL) {
                 $this->db->where('category', $category);
            }
            if ($filter !== NULL) {
                 $this->db->where("tag LIKE '%" . $filter . "%'");
            }
            return $this->db->select('tag')->from('tags')->limit(10)->get()->result();
       }

       public function add_tags($tags, $category) {
            $taglist = explode(',', $tags);
            if (is_array($taglist)) {
                 foreach ($taglist as $tag) {
                      $tagname = strtolower($tag);
                      $this->db->where('category', $category);
                      $this->db->where('tag',$tagname);
                      $q = $this->db->get('tags');

                      if ($q->num_rows() > 0) {
                           
                      } else {
                           $this->db->insert('tags', array('tag' => $tagname, 'category'=> $category));
                      }
                 }
            }
            return;
       }

  }
  