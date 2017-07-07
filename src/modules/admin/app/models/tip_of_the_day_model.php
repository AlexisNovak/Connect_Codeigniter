<?php
class Tip_of_the_day_model extends MY_Model{
    function __construct() {
        parent::__construct();
    }
    public function get_all_tips(){
        return $this->db->select('id,content')->from('tip_of_the_day')->get()->result();
    }
    public function add($data=array()){
        if($this->db->insert('tip_of_the_day',$data))
                return $this->db->insert_id();
        return 0;
    }
    public function update_tip($tip_id,$data=array()){
        return $this->db->where('id',$tip_id)->update('tip_of_the_day',$data);
    }
    public function delete_tip($id){
        return $this->db->where('id',$id)->delete('tip_of_the_day');
    }
    public function get_random_tip(){
        return $this->db->query('SELECT content FROM tip_of_the_day ORDER BY RAND() LIMIT 1')->row();
    }
}