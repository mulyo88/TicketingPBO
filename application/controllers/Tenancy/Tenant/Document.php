<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		is_login();
        $this->load->model('Tenancy/Tenant/Document_model');
        $this->load->model('Tenancy/Tenant/Tenant_model');
        $this->load->model('Tenancy/MasterData/Property/Common_Code_model');
        $this->load->helper(['form', 'url']);
	}

    /**
     * Display a listing of the resource.
     */
	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Document';
		$data['bodyclass'] = 'skin-blue';
		
		$data['results'] = $this->Document_model->get_all_builder();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Document/index', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Create Document';
		$data['bodyclass'] = 'skin-blue';
		
        $data['tenant'] = $this->Tenant_model->get_all_is_active();
        $data['common_code'] = $this->Common_Code_model->get_by_code('DOCUMENT_LOO');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Document/create', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store()
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Tenant/Document/create');
		}

        $this->db->trans_begin();
        try {
            $tenant = $this->Tenant_model->get_by_id($this->input->post('tenant_id'));
            $exists = $this->Document_model->get_by_id_count($this->input->post('tenant_id'));
            if ($exists > 0 ) {
                is_pesan('error','code : '.$tenant->code.' already exists.');
                redirect('Tenancy/Tenant/Document/create');
            }

            $document   = $this->input->post('document');
            $files      = $_FILES;

            $dir = $this->config->item("tnc_tenant")."/".$this->input->post('tenant_id');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $this->load->library('upload');
            $config['upload_path']      = $dir;
            $config['allowed_types']    = '*';
            $this->upload->initialize($config);

            $count = count($_FILES['file']['name']);
            $date = new \Datetime('now');
            for ($i = 0; $i < $count; $i++) {
                if (!empty($files['file']['name'][$i])) {
                    $_FILES['file']['name']     = $files['file']['name'][$i];
                    $_FILES['file']['type']     = $files['file']['type'][$i];
                    $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
                    $_FILES['file']['error']    = $files['file']['error'][$i];
                    $_FILES['file']['size']     = $files['file']['size'][$i];

                    if ($this->upload->do_upload('file')) {
                        $upload_data = $this->upload->data();
                        $this->Document_model->insert([
                            'tenant_id'     => $this->input->post('tenant_id'),
                            'common_id'     => $document[$i],
                            'file'          => $upload_data['file_name'],
                            'is_active'     => 1,
                            'username'      => $user_id,
                            'created_at'    => $date->format('Y-m-d H:i:s'),
                            'updated_at'    => $date->format('Y-m-d H:i:s'),
                        ]);
                    } else {
                        is_pesan('error','Upload failed!');
                        redirect('Tenancy/Tenant/Document/index');
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Insert failed, please try again.');
                redirect('Tenancy/Tenant/Document/create');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Inserted successfully!');
                redirect('Tenancy/Tenant/Document/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Document/create');
        }
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Update Document';
		$data['bodyclass'] = 'skin-blue';
		
        $data['tenant'] = $this->Tenant_model->get_all_is_active();
        $data['common_code'] = $this->Common_Code_model->get_by_code('DOCUMENT_LOO');
		$data['result'] = $this->Tenant_model->get_by_id($id);
		$data['document_result'] = $this->Document_model->get_by_tenant_id($id);

        $this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Tenancy/Tenant/Document/edit', $data);
		$this->load->view('templates/footer');
	}

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

		if (!$this->input->post()) {
			is_pesan('error','Unfornuately, something went wrong.');
			redirect('Tenancy/Tenant/Document/edit/'.$id);
		}

        $this->db->trans_begin();
        try {
            $document   = $this->input->post('document');
            $files      = $_FILES;

            $dir = $this->config->item("tnc_tenant")."/".$this->input->post('tenant_id');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $this->load->library('upload');
            $config['upload_path']      = $dir;
            $config['allowed_types']    = '*';
            $this->upload->initialize($config);

            $count = count($_FILES['file']['name']);
            $date = new \Datetime('now');

            for ($i = 0; $i < $count; $i++) {
                if (!empty($files['file']['name'][$i])) {
                    $_FILES['file']['name']     = $files['file']['name'][$i];
                    $_FILES['file']['type']     = $files['file']['type'][$i];
                    $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
                    $_FILES['file']['error']    = $files['file']['error'][$i];
                    $_FILES['file']['size']     = $files['file']['size'][$i];
                    
                    $exists = $this->Document_model->get_by_tenant_id_common_id($this->input->post('tenant_id'), $document[$i]);
                    if ($exists) {
                        // update & replace file******
                        $file_path = $this->config->item("tnc_tenant")."/".$id."/". $exists->file;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }

                        if ($this->upload->do_upload('file')) {
                            $upload_data = $this->upload->data();
                            $this->Document_model->update($id, $document[$i], [
                                'file'          => $upload_data['file_name'],
                                'username'      => $user_id,
                                'updated_at'    => $date->format('Y-m-d H:i:s'),
                            ]);
                        } else {
                            is_pesan('error','Upload failed!');
                            redirect('Tenancy/Tenant/Document/index');
                        }
                    } else {
                        // insert ******
                        if ($this->upload->do_upload('file')) {
                            $upload_data = $this->upload->data();
                            $this->Document_model->insert([
                                'tenant_id'     => $this->input->post('tenant_id'),
                                'common_id'     => $document[$i],
                                'file'          => $upload_data['file_name'],
                                'is_active'     => 1,
                                'username'      => $user_id,
                                'created_at'    => $date->format('Y-m-d H:i:s'),
                                'updated_at'    => $date->format('Y-m-d H:i:s'),
                            ]);
                        } else {
                            is_pesan('error','Upload failed!');
                            redirect('Tenancy/Tenant/Document/index');
                        }
                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Update failed, please try again.');
                redirect('Tenancy/Tenant/Document/edit/'.$id);
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Update successfully!');
                redirect('Tenancy/Tenant/Document/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Document/edit/'.$id);
        }
	}

    public function destroy($id)
	{
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $this->db->trans_begin();
        try {
            $folder_path = $this->config->item("tnc_tenant")."/".$id;

            if (is_dir($folder_path)) {
                $this->delete_directory($folder_path);
            }

            $this->Document_model->delete_all($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                
                is_pesan('error','Delete failed, please try again.');
                redirect('Tenancy/Tenant/Document/index');
            } else {
                $this->db->trans_commit();
                
                is_pesan('error','Delete successfully!');
                redirect('Tenancy/Tenant/Document/index');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();

            is_pesan('error',$e->getMessage());
            redirect('Tenancy/Tenant/Document/index');
        }
	}

    private function delete_directory($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->delete_directory($path);
            } else {
                unlink($path);
            }
        }

        return rmdir($dir);
    }
}