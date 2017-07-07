<?php

  class Tags extends MY_Controller {

       function __construct() {
            parent::__construct();
            $this->load->model('tags_model');
       }

       public function taglist() {
            $result = array();
            $search = NULL;
            if ($this->input->post('filter')) {
                 $search = $this->input->post('filter', TRUE);
            }
            $category = 1;
            $tags = $this->tags_model->get_tags($search, $category);
            if ($tags) {
                 foreach ($tags as $tag) {
                      $result[] = ucfirst($tag->tag);
                 }
            }
            echo json_encode($result);
            exit();
       }

  }
  