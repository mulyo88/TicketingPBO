<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_get_file extends CI_Controller {

	function dapat_foto($NIK='',$pribadi=''){

		$file_foto = base_url().dapatkan_foto_karyawan_dari_sistem_lain($NIK,$pribadi);
		// $foto = '<img src="'.$file_foto.'" width="50px" height="50px"></img>';
		// echo substr($file_foto, -8);
		// return 'aaaaaaaaaa';
		echo $file_foto;
		die;
	}

	function dapat_foto_jadi($NIK=''){

		echo dapatkan_foto_karyawan_baru($NIK);
	}

	function lihat_file_full($LedgerNo='',$NIK=''){
      

		$dapat_data = $this->db->query("SELECT LedgerNo, NIK, NamaFile, TipeFile FROM tbl_dokumen_pendukung WHERE LedgerNo='$LedgerNo' AND NIK='$NIK' ")->row();
        
		$NIK = $dapat_data->NIK;
        $NamaFile = $dapat_data->NamaFile;
        $TipeFile = $dapat_data->TipeFile;

        $html_foto ='';

        echo '<embed src="'.base_url('assets/dokumen_pendukung_karyawan/'.$NamaFile).'" frameborder="0" width="100%" height="400px">';
        die;
	
        // if ($TipeFile == 'Foto') {
        //     echo '<img class="img img-thumbnail" src="'.base_url('assets/dokumen_pendukung_karyawan/'.$NamaFile).'" >';

        // }else{
        //     echo '<embed src="'.base_url('assets/dokumen_pendukung_karyawan/'.$NamaFile).'" frameborder="0" width="100%" height="400px">';
        // }

    }

	function percobaan_upload_file(){
		echo $_FILES['FotoKaryawan']['name'];
	}


	function tes(){
		print_rr($_POST);
	}

	function download_paksa($file_name='')
    {
    	if ($file_name == '') {
    		exit;
    	}
    	$this->load->helper('download');
        force_download(hapus_file_untuk_document_root('server',$file_name), null);
        // $this->load->helper('download');
        // force_download(FCPATH.'/assets/dokumen_pendukung_karyawan/'.$file_name, null);
    }

}

/* End of file Akses_get_file.php */
/* Location: ./application/controllers/Akses_get_file.php */