<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	var $Company;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users');
		$this->Company = $this->config->item('Company');
		// tblBarangMasuk_Header_Pembuatan();
		// tblBarangMasuk_Detail_Pembuatan();
		// tblPemakaian_MOS_Pembuatan();
		// Pembuatan_tbl_FileMOS();
		// membuatFolderMosFile();
		// PenambahanTEAMPO_TEAMPC();

		
		}


		

	function index($Lakukan = '')
	{

		if ($this->input->post() ||  $Lakukan != '') {
			$username = $this->input->post('username', TRUE);
			$user = $this->Users->getUserByUsername($this->input->post('username', TRUE));

			$NIK_tidak_ada_di_login = 0;
			if ($user) {
				$NIK_tidak_ada_di_login = 1;
			} else {
				$NIK_tidak_ada_di_login = 0;
			}

			if ($NIK_tidak_ada_di_login == 0) {

				$dapat_nama_karyawan = $this->db->query("SELECT Nama, Password_dari_CI, NIK, Password_dari_CI FROM Karyawan WHERE NIK='$username' AND Active='1' ");

				if ($dapat_nama_karyawan->num_rows() == 0) {
					is_pesan_saya('error', '<u>' . $username . '</u> sudah tidak aktif ');
					redirect('Auth');
					exit;
				}

				$Password_dari_CI = '$1$VgN0LLc6$k.2knAm0n5I6tJdhtJIh2.';
				$this->db->query("UPDATE Karyawan SET Password_dari_CI='$Password_dari_CI' WHERE NIK='$username'   ");
				$Company = $this->config->item('Company');

				$dapat_nama_karyawan = $dapat_nama_karyawan->row_array();

				$UserID_buatan = $dapat_nama_karyawan['Nama'];
				$UserID_buatan = explode(' ', $UserID_buatan);
				$UserID_buatan = strtolower($UserID_buatan[0]);


				$mydata = array(

					'UserID' => $UserID_buatan,
					'UserName' => $dapat_nama_karyawan['Nama'],
					'AksesCompany' => $Company,

					'Password_dari_CI' => $Password_dari_CI,
					'NIK' => $dapat_nama_karyawan['NIK'],
					'PasswordCI' => $Password_dari_CI,
					'Active' => 1

				);

				$cek_UserID_sudah_ada = $this->db->where('UserID', $UserID_buatan)->get('Login')->num_rows();
				if ($cek_UserID_sudah_ada > 0) {
					$mydata['UserID'] = $UserID_buatan . '1';
				}

				$this->db->trans_begin();

				$this->db->insert('Login', $mydata);

				//ubah login gak aktif kalau ada nama yang sama
				$NamaDiLogin = $mydata['UserName'];
				$NIKDiLogin = $mydata['NIK'];

				$this->db->query("UPDATE Login SET Active='0' WHERE UserName='$NamaDiLogin' AND NIK !='$NIKDiLogin' ");

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$this->index('0');
				} else {
					$this->db->trans_commit();
					$this->index('1');
				}
			}

			if ($user->Active != '1') {
				is_pesan_saya('error', 'Akses Ditolak, User tidak aktif');
				redirect('Auth', 'refresh');
			}

			$myquery = $this->db->query(
				"
				SELECT item FROM
				dbo.SplitString( 
					(SELECT AksesCompany FROM Login WHERE (UserID = '$username' OR NIK='$username' ) ), 
					',')

				"
			);

			$AksesCompany = '';

			foreach ($myquery->result() as $data_company) :

				if ($data_company->item == $this->Company) {
					$AksesCompany = $this->Company;
				}
			endforeach;


			if ($AksesCompany == '' or $AksesCompany == NULL) {
				is_pesan_saya('error', 'Akses ditolak');
				redirect('Auth', 'refresh');
			}

			if ($user) {

				if (password_verify($this->input->post('password'), $user->PasswordCI)) {

					$this->session->set_userdata([
						'MIS_LOGGED_ID'   => $user->UserID,
						'MIS_LOGGED_CORP'   => $this->input->post('corp'),
						'MyCompany' => $this->Company,
						'MIS_LOGGED_NAME'   => $user->UserName,
						'MIS_LOGGED_TOKEN'  => json_encode($user),
						'MIS_LOGGED_NIK'    => $user->NIK,
						'PecahToken'    => json_decode(json_encode($user))
					]);

					// print_rr($this->session->userdata());
					// exit;

					redirect('Welcome/index');
				} else {
					is_pesan_saya('error', 'Username dan password tidak sesuai');
					redirect('Auth');
				}
			} else {
				is_pesan_saya('error', 'Username dan password tidak sesuai');
				redirect('Auth');
			}
		} else {

			$this->load->view('login');
		}



		// print_rr($this->session->all_userdata());
		// exit;


		// $token = $this->input->get('token');
		// if ($token == NULL) {
		// 	// redirect('http://localhost/Real-Point/dashboard');die;
		// 	redirect('http://localhost/MyIfpro');
		// 	die;
		// }
		// $pisahToken = explode('_', $token);
		// $get = $this->db->query("SELECT * FROM Login WHERE UserID='$pisahToken[1]'")->row_array();
		// if ($pisahToken[0] != $get['TokenLogin']) {
		// 	$this->session->set_flashdata('error_login', 'Your token does not match, please try again');
		// 	redirect('http://localhost/MyIfpro');
		// 	die;
		// } else {
		// 	$getToken2 = $pisahToken[1];
		// 	$username = $getToken2;
		// 	$user = $this->Users->getUserByUsername($getToken2);
		// 	$NIK_tidak_ada_di_login = 0;
		// 	if ($user) {
		// 		$NIK_tidak_ada_di_login = 1;
		// 	} else {
		// 		$NIK_tidak_ada_di_login = 0;
		// 	}
		// 	if ($NIK_tidak_ada_di_login == 0) {
		// 		$dapat_nama_karyawan = $this->db->query("SELECT Nama, Password_dari_CI, NIK, Password_dari_CI FROM Karyawan WHERE NIK='$username' AND Active='1' ");
		// 		if ($dapat_nama_karyawan->num_rows() == 0) {
		// 			is_pesan_saya('error', '<u>' . $username . '</u> sudah tidak aktif ');
		// 			redirect('Auth');
		// 			exit;
		// 		}
		// 		// $Password_dari_CI = '$1$VgN0LLc6$k.2knAm0n5I6tJdhtJIh2.';
		// 		$this->db->query("UPDATE Karyawan SET Password_dari_CI='$Password_dari_CI' WHERE NIK='$username'   ");
		// 		$Company = $this->config->item('Company');
		// 		$dapat_nama_karyawan = $dapat_nama_karyawan->row_array();
		// 		$UserID_buatan = $dapat_nama_karyawan['Nama'];
		// 		$UserID_buatan = explode(' ', $UserID_buatan);
		// 		$UserID_buatan = strtolower($UserID_buatan[0]);
		// 		$mydata = array(
		// 			'UserID' => $UserID_buatan,
		// 			'UserName' => $dapat_nama_karyawan['Nama'],
		// 			'AksesCompany' => $Company,
		// 			'Password_dari_CI' => $Password_dari_CI,
		// 			'NIK' => $dapat_nama_karyawan['NIK'],
		// 			'PasswordCI' => $Password_dari_CI,
		// 			'Active' => 1
		// 		);
		// 		$cek_UserID_sudah_ada = $this->db->where('UserID', $UserID_buatan)->get('Login')->num_rows();
		// 		if ($cek_UserID_sudah_ada > 0) {
		// 			$mydata['UserID'] = $UserID_buatan . '1';
		// 		}
		// 		$this->db->trans_begin();
		// 		$this->db->insert('Login', $mydata);
		// 		//ubah login gak aktif kalau ada nama yang sama
		// 		$NamaDiLogin = $mydata['UserName'];
		// 		$NIKDiLogin = $mydata['NIK'];
		// 		$this->db->query("UPDATE Login SET Active='0' WHERE UserName='$NamaDiLogin' AND NIK !='$NIKDiLogin' ");
		// 		if ($this->db->trans_status() === FALSE) {
		// 			$this->db->trans_rollback();
		// 			$this->index('0');
		// 		} else {
		// 			$this->db->trans_commit();
		// 			$this->index('1');
		// 		}
		// 	}
		// 	if ($user->Active != '1') {
		// 		is_pesan_saya('error_login', 'Access Denied, User is inactive');
		// 		// redirect('http://localhost/Real-Point/dashboard');die;
		// 		redirect('http://localhost/MyIfpro/');
		// 		die;
		// 	}
		// 	$myquery = $this->db->query("SELECT item FROM dbo.SplitString((SELECT AksesCompany FROM Login WHERE (UserID = '$username' OR NIK='$username' )),',')");
		// 	$AksesCompany = '';
		// 	foreach ($myquery->result() as $data_company) :
		// 		if ($data_company->item == $this->Company) {
		// 			$AksesCompany = $this->Company;
		// 		}
		// 	endforeach;
		// 	if ($AksesCompany == '' or $AksesCompany == NULL) {
		// 		is_pesan_saya('error', 'Access Denied, you do not have access to this menu');
		// 		// redirect('http://localhost/Real-Point/dashboard');die;
		// 		redirect('http://localhost/MyIfpro/');
		// 		die;
		// 	}
		// 	if ($user) {
		// 		$this->session->set_userdata([
		// 			'MIS_LOGGED_ID'   => $user->UserID,
		// 			'MIS_LOGGED_CORP'   => $this->input->post('corp'),
		// 			'MyCompany' => $this->Company,
		// 			'MIS_LOGGED_NAME'   => $user->UserName,
		// 			'MIS_LOGGED_TOKEN'  => json_encode($user),
		// 			'MIS_LOGGED_NIK'    => $user->NIK,
		// 			'PecahToken'    => json_decode(json_encode($user))
		// 		]);
		// 		$this->check_terakhir_login();
		// 		redirect('Welcome/index');
		// 	} else {
		// 		// redirect('http://localhost/Real-Point');die;
		// 		redirect('http://localhost/MyIfpro/');
		// 		die;
		// 	}
		// }

		
	}

	public function updatePassword()
	{
		if ($this->input->post()) {
			$token = json_decode($this->session->userdata('MIS_LOGGED_TOKEN'));
			$this->Users->updateUser([
				'PasswordCI' => crypt($this->input->post('Password'), '')
			], $token->UserID);
			$this->session->set_flashdata('Berhasil', 'success', 'Password anda berhasil diubah!');
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	private function check_terakhir_login()
	{
		$date = array('last_login' => date('Y-m-d H:i:s'));
		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		$this->db->set($date)->where('UserID', $UserID)->update('Login');
	}

	public function logout()
	{
		$date = array('last_login' => date('Y-m-d H:i:s'));
		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		$this->db->set($date)->where('UserID', $UserID)->update('Login');
		$this->session->unset_userdata('login');
		redirect('auth');
	}
}
