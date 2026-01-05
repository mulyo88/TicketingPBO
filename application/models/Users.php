<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Model {

	var $Company;

    function __construct()
    {
        parent:: __construct();
        $this->Company = $this->config->item('Company');
    }

	function ceklogin($username,$password)
	{
		$sql="SELECT * FROM login WHERE UserID='$username' AND PasswordCI='$password' ";
		$eksekusi=$this->db->query($sql);
		return $eksekusi;
	}

	function getUsers() {
		return $this->db->query("
			SELECT 
				UserID,UserName,Email,NIK,PasswordCI 
			FROM Login 
			ORDER BY UserName ASC
		")->result_object();
	}

	public function getUserByUsername($UserID, $id = null) {

		// $input = $_POST['angka'];
		// $var = "/^[0-9]*$/";
		// if(!preg_match($var,$input)){
		// echo "Data tidak sesuai ketentuan, masukan hanya Angka  saja";
		// }

		$id_tidak_null = '';
		if($id !='' || $id !=NULL ){
			$id_tidak_null = "AND id='$id'";
		}
		$myquery = $this->db->query("SELECT * FROM Login WHERE UserID='$UserID' OR  NIK='$UserID'  ".$id_tidak_null."  ")->row_object();
		return $myquery;


		// $query = $this

		// $myquery = $this->db->query("
		// SELECT A.* FROM Login A WHERE (UserID='$UserID' OR NIK='$UserID')
		
		// ")

		$this->db->where('UserID', $UserID);
		$this->db->or_where('NIK', $UserID);
		if ($id) {
			$this->db->where('id', $id);
		}
		return $this->db->get('Login')->row_object();
	}

	function insertUser($data) {
		return $this->db->insert('Login', $data);
	}

	function updateUser($data, $UserID) {
		$this->db->where('UserID', $UserID);
		return $this->db->update('Login', $data);
	}

	function deleteUser($UserID) {
		$this->db->where('UserID', $UserID);
		return $this->db->delete('Login');
	}

	

}

