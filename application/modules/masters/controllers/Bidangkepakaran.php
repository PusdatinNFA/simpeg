<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bidangkepakaran controller
 */
class Bidangkepakaran extends Admin_Controller
{
	protected $permissionCreate = 'Bidangkepakaran.Masters.Create';
	protected $permissionDelete = 'Bidangkepakaran.Masters.Delete';
	protected $permissionEdit   = 'Bidangkepakaran.Masters.Edit';
	protected $permissionView   = 'Bidangkepakaran.Masters.View';
	protected $permissionDownload   = 'Bidangkepakaran.Masters.Download';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters/kepakaran_model');
		$this->load->model('masters/bidang_kepakaran_model');
		$this->load->model('masters/bidang_penelitian_model');
	}

	/**
	 * Display a list of kelompok data.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->auth->restrict($this->permissionView);
		Template::set('toolbar_title', "Bidang Kepakaran");
		Template::set("collapse", true);
		Template::set_view("masters/bidang_kepakaran");
		Template::render();
	}

	/**
	 * Create a kelompok jabatan object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict($this->permissionCreate);

		if (isset($_POST['save'])) {
			if ($insert_id = $this->save_bidang_kepakaran()) {
				log_activity($this->auth->user_id(), 'Insert Bidang kepakaran : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/bidangkepakaran');
			}

			// Not validation error
			if (!empty($this->bidang_kepakaran_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->bidang_kepakaran_model->error, 'error');
			}
		}

		Template::set('toolbar_title', "Tambah Bidang kepakaran");
		Template::set_view('masters/bidangkepakaran_crud');

		Template::render();
	}

	/**
	 * Allows editing of jabatan data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = $this->uri->segment(4);
		if (empty($id)) {
			Template::set_message("error", 'error');

			redirect('/masters/bidangkepakaran');
		}
		if (isset($_POST['save'])) {
			$this->auth->restrict($this->permissionEdit);

			if ($this->save_bidang_kepakaran('update', $id)) {

				log_activity($this->auth->user_id(), "Update Bidang kepakaran " . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Disimpan", 'success');
				redirect('/masters/bidangkepakaran');
			} else {
				Template::set_message("Data gagal Disimpan " . $this->bidang_kepakaran_model->error, 'error');
			}

			// Not validation error
			if (!empty($this->bidang_kepakaran_model->error)) {
				Template::set_message("Data gagal Disimpan" . $this->bidang_kepakaran_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			$this->auth->restrict($this->permissionDelete);

			if ($this->bidang_kepakaran_model->delete($id)) {
				log_activity($this->auth->user_id(), 'Hapus Bidang kepakaran: ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Dihapus", 'success');

				redirect('/masters/bidangkepakaran');
			}

			Template::set_message("Data Gagal Dihapus" . $this->bidang_kepakaran_model->error, 'error');
		}

		Template::set('kepakaran', $this->bidang_kepakaran_model->find_by("id", $id));
		Template::set_view('masters/bidangkepakaran_crud');

		Template::set('toolbar_title', "Update Bidang kepakaran");
		Template::render();
	}

	public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		//	die($id);
		if ($this->bidang_kepakaran_model->delete($id)) {
			log_activity($this->auth->user_id(), "Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
			Template::set_message("Delete Bidang kepakaran sukses", 'success');
			echo "Sukses";
		} else {
			echo "Gagal";
		}

		exit();
	}

	/**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_bidang_kepakaran($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            //die("masuk");
            $_POST['id'] = $id;
        }

        // Validate the data
        // $this->form_validation->set_rules('KODE_JABATAN','KODE_JABATAN','required|max_length[20]|unique[jabatan.KODE_JABATAN' . $extra_unique_rule . ']');

        $this->form_validation->set_rules($this->bidang_kepakaran_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->bidang_kepakaran_model->prep_data($this->input->post());
        // if (empty($data['id']))    
			UNSET($data['id']);
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->bidang_kepakaran_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->bidang_kepakaran_model->update_where("id",$id, $data);
        }

        return $return;
    }

	public function ajax(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
		
		$q = $this->input->get('term');
		if(!empty($q)){
			$this->db->group_start();
			$this->bidang_kepakaran_model->where('upper(bidangkepakaran.namaid) LIKE \'%' . strtoupper($q) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaen) LIKE \'%' . strtoupper($q) . '%\'');
			$this->db->group_end();
		}
		$json = array();
		$datas = $this->bidang_kepakaran_model->find_all();
		foreach ($datas as $data) {
			$text = $data->namaid.' ('.$data->namaen.')';
			$json[] = array('id' => $data->id, 'text' => $text);
		}
		$result = array(
			"results" => $json
		);   
	    echo json_encode($result);
	}

	public function ajax_list()
	{
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');

		$length = $this->input->post('length');
		$start = $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";

		//$this->bidang_kepakaran_model->where("deleted ",null);
		$total = $this->bidang_kepakaran_model->count_all();;
		$output = array();
		$output['draw'] = $draw;


		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if ($search != "") {
			$this->db->group_start();
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaid) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaen) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaid) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaen) LIKE \'%' . strtoupper($search) . '%\'');
			$this->db->group_end();
		}
		$this->bidang_kepakaran_model->limit($length, $start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->bidang_kepakaran_model->order_by($iSortCol, $sSortCol);
		//$this->bidang_kepakaran_model->where("deleted",null);
		$records = $this->bidang_kepakaran_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if ($search != "") {
			$this->db->group_start();			
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaid) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaen) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaid) LIKE \'%' . strtoupper($search) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaen) LIKE \'%' . strtoupper($search) . '%\'');
			$jum	= $this->bidang_kepakaran_model->count_all();
			$output['recordsTotal'] = $output['recordsFiltered'] = $jum;
			$this->db->group_end();
		}

		$nomor_urut = $start + 1;
		if (isset($records) && is_array($records) && count($records)) :
			foreach ($records as $record) {
				$row = array();
				$row[]  = $nomor_urut;
				$row[]  = $record->kode;
				$row[]  = $record->kepakaranid.' (<i>'.$record->kepakaranen.'</i>)';
				$row[]  = $record->namaid.' (<i>'.$record->namaen.'</i>)';
				$btn_actions = array();
				if ($this->auth->has_permission($this->permissionEdit)) {
					$btn_actions[] = "<a href='" . base_url() . "masters/bidangkepakaran/edit/" . $record->id . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
				}

				if ($this->auth->has_permission($this->permissionDelete)) {
					$btn_actions[] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
				}
				$row[] = "<div class='btn-group'>" . implode(" ", $btn_actions) . "</div>";
				$output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
	}

	public function download()
	{
		$this->load->helper('aplikasi');
		$records = $this->bidang_kepakaran_model->order_by('id')->find_all();
		return download_excel($records, "Master Bidang Kepakaran");
	}
}
