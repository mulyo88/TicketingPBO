<?php
class ImageModel extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert_image($filename) {
        $data = array(
            'Picture' => $filename
        );
        return $this->db->insert('DataAsset', $data);
    }

    public function get_images() {
        $query = $this->db->get('DataAsset');
        return $query->result_array();
    }


    public function Update_Document($filename) {
        $data = array(
            'Picture' => $filename
        );
        return $this->db->insert('DocAssetIfpro', $data);
    }
}
