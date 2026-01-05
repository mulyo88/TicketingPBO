<?php

function dapat_text_job($JobNo){
    if ($JobNo !='') {
        $CI =& get_instance();
        $query = $this->db->select('JobNo,JobNm,Lokasi')->where('JobNo',$JobNo)->get('Job')->row_array();
        return $query;
    }
    return '';
}

function cek_post_untuk_keamanan(){
    $CI =& get_instance();
    if (!$CI->input->post()) {
        redirect('Welcome','refresh');

    }
}

function mylist_ppn(){
    $CI =& get_instance();

    $sebelas_persen = (int) 11;
    $sebelas_persen_decimal = $sebelas_persen / 100;

    // $array = array(
    //     '11%' 
    // );
}

function list_company(){
    $CI =& get_instance();
    return array('MDH','KIP','KSB','KIB','KGS','KSC','REN','AMK','DLL');

}

function get_data_golongan(){
    $CI =& get_instance();
    $myquery = $CI->db->get('tbl_golongan')->result_array();
    return $myquery;

}

function get_data_grade(){
    $CI =& get_instance();

    $myquery = $CI->db->get('tbl_grade')->result_array();
    return $myquery;
}

function get_data_lokasikerja(){
    $CI =& get_instance();
    $myquery = $CI->db->get('LokasiKerja')->result_array();
    return $myquery;
}


function dapat_detail_rekening_pengirim($RekId){
    $CI =& get_instance();
    $query_rekening = $CI->db->query("SELECT * FROM Rekening WHERE RekId='$RekId'");
    $jumlah = $query_rekening->num_rows();

    if ($jumlah > 0) {
        $row = $query_rekening->row_array();
        return $row['Bank'].'/'.$row['AtasNama'].'/'.$row['NoRek'];
    }

    return '';



}



?>