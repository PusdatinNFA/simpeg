<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * PengelolaSdmKawasan controller
 */
class Pengelolasdmkawasan extends Admin_Controller
{
	protected $permissionCreate = 'PengelolaSdmKawasan.Masters.Create';
	protected $permissionDelete = 'PengelolaSdmKawasan.Masters.Delete';
	protected $permissionEdit   = 'PengelolaSdmKawasan.Masters.Edit';
	protected $permissionView   = 'PengelolaSdmKawasan.Masters.View';
	protected $permissionGenerate   = 'PengelolaSdmKawasan.Masters.Generate';
	protected $permissionDownload   = 'PengelolaSdmKawasan.Masters.Download';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters/pengelola_sdm_kawasan_model');
		$this->load->model('masters/pengelola_sdm_kawasan_detail_model');
		$this->load->model('pegawai/kawasan_model');
		$kawasans = $this->kawasan_model->find_all();
		Template::set('kawasans', $kawasans);

		$this->load->model('pegawai/unitkerja_model');
		$satkers = $this->unitkerja_model->find_satker();
		Template::set('satkers', $satkers);
	}

	/**
	 * Display a list of kelompok data.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->auth->restrict($this->permissionView);
		Template::set('toolbar_title', "Pengelola Kawasan SDM");
		Template::set("collapse", true);
		Template::set_view("masters/pengelola_sdm_kawasan");
		Template::render();
	}

	/**
	 * Create a pengelola sdm object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict($this->permissionCreate);

		if (isset($_POST['save'])) {
			if ($insert_id = $this->save_PengelolaSdmKawasan()) {
				log_activity($this->auth->user_id(), 'Insert PengelolaSdmKawasan : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/Pengeloladdmkawasan');
			}

			// Not validation error
			if (!empty($this->pengelola_sdm_kawasan_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->pengelola_sdm_kawasan_model->error, 'error');
			}
		}

		Template::set('toolbar_title', "Tambah Pengelola Kawasan SDM");
		Template::set_view('masters/pengelola_sdm_kawasan');

		Template::render();
	}

	/**
	 * Create a pengelola sdm object.
	 *
	 * @return void
	 */
	public function createrule()
	{
		$this->auth->restrict($this->permissionCreate);
		$id = $this->uri->segment(4);
		if (isset($_POST['save'])) {
			if ($insert_id = $this->save_PengelolaSdmKawasan("insert",$id)) {
				log_activity($this->auth->user_id(), 'Insert PengelolaSdmKawasan : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/Pengelolasdmkawasan');
			}

			// Not validation error
			if (!empty($this->pengelola_sdm_kawasan_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->pengelola_sdm_kawasan_model->error, 'error');
			}
		}
		Template::set('pengelolasdm', $this->pengelola_sdm_kawasan_model->find_by("id", $id));
		$rules = $this->pengelola_sdm_kawasan_detail_model->find_rule($id);
		$rules[count($rules)] = (string) (count($rules)+1);
		$rule = count($rules);
        Template::set('rules', $rules);
		Template::set('rule', $rule);
		Template::set('isNew', 1);

		Template::set('toolbar_title', "Tambah Rule Pengelola Sdm Kawasan");
		Template::set_view('masters/pengelola_sdm_kawasan_crud');

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
		$rule = $this->uri->segment(5);
		if (empty($id)) {
			Template::set_message("error", 'error');

			redirect('/masters/Pengelolasdmkawasan/create');
		}
		if (isset($_POST['save'])) {
			$this->auth->restrict($this->permissionEdit);
			if ($this->save_PengelolaSdmKawasan('update', $id)) {

				log_activity($this->auth->user_id(), "Update PengelolaSdmKawasan " . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Disimpan", 'success');
				redirect('/masters/PengelolaSdmKawasan');
			} else {
				Template::set_message("Data gagal Disimpan " . $this->pengelola_sdm_kawasan_model->error, 'error');
			}

			// Not validation error
			if (!empty($this->pengelola_sdm_kawasan_model->error)) {
				Template::set_message("Data gagal Disimpan" . $this->pengelola_sdm_kawasan_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			$this->auth->restrict($this->permissionDelete);

			if ($this->pengelola_sdm_kawasan_model->delete($id)) {
				log_activity($this->auth->user_id(), 'Hapus PengelolaSdmKawasan: ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Dihapus", 'success');

				redirect('/masters/PengelolaSdmKawasan');
			}

			Template::set_message("Data Gagal Dihapus" . $this->pengelola_sdm_kawasan_model->error, 'error');
		}

		Template::set('pengelolasdm', $this->pengelola_sdm_kawasan_model->find_by("id", $id));
		Template::set_view('masters/pengelola_sdm_kawasan_crud');

		$rules = $this->pengelola_sdm_kawasan_detail_model->find_rule($id);
		if (count($rules) == 0) {
			// Template::set_message("error", 'error');

			redirect('/masters/Pengelolasdmkawasan/createrule/'.$id);
		}
		// $rule = 1;
        Template::set('rules', $rules);
        Template::set('akawasans', $this->pengelola_sdm_kawasan_detail_model->find_kawasan_rule($id,$rule));
        Template::set('asatkers', $this->pengelola_sdm_kawasan_detail_model->find_satker_rule($id,$rule));
		Template::set('rule', $rule);
		Template::set('toolbar_title', "Update Pengelola Kawasan SDM");
		Template::render();
	}

	public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		//	die($id);
		if ($this->pengelola_sdm_kawasan_model->delete($id)) {
			log_activity($this->auth->user_id(), "Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
			Template::set_message("Delete PengelolaSdmKawasan sukses", 'success');
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
    private function save_PengelolaSdmKawasan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            //die("masuk");
            $_POST['id'] = $id;
        }

        $this->form_validation->set_rules($this->pengelola_sdm_kawasan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->pengelola_sdm_kawasan_model->prep_data($this->input->post());
        // if (empty($data['id']))    
			UNSET($data['id']);
        
        $return = false;
        if ($type == 'insert') {
            // $id = $this->pengelola_sdm_kawasan_model->insert($data);
			$return = $this->pengelola_sdm_kawasan_model->update_where("id",$id, $data);
            // if (is_numeric($id)) {
            //     $return = $id;
            // }
        } elseif ($type == 'update') {
            $return = $this->pengelola_sdm_kawasan_model->update_where("id",$id, $data);
        }

        //todo set detail
		$rule = $this->input->post('rule');
        if ($id){
			$this->pengelola_sdm_kawasan_detail_model->where('kategori',$rule);
            $this->pengelola_sdm_kawasan_detail_model->update_where('pengelola_sdm_kawasan_id',$id,array('status' => 0));
        }
        $kawasans = $this->input->post('kawasans');
        $satkers = $this->input->post('satkers');

        foreach ($kawasans as $kawasan) {
            foreach ($satkers as $satker) {
                $data_insert = array(
                    'pengelola_sdm_kawasan_id' => $id,
                    'kawasan_id' => $kawasan,
                    'unit_kerja_id' => $satker,
                    'kategori' => $rule,
                );
                $this->pengelola_sdm_kawasan_detail_model->insert($data_insert);
            }
        }
        return $return;
    }

	public function ajax_list()
	{
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');

		$length = $this->input->post('length');
		$start = $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";

		$this->pengelola_sdm_kawasan_model->where("status ",1);
		$total = $this->pengelola_sdm_kawasan_model->count_all();;
		$output = array();
		$output['draw'] = $draw;


		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if ($search != "") {
			$this->pengelola_sdm_kawasan_model->where('upper("nama") LIKE \'%' . strtoupper($search) . '%\'');
			$this->pengelola_sdm_kawasan_model->or_where('upper("nama") LIKE \'%' . strtoupper($search) . '%\'');
		}
		$this->pengelola_sdm_kawasan_model->limit($length, $start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "nama";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pengelola_sdm_kawasan_model->order_by($iSortCol, $sSortCol);
		//$this->pengelola_sdm_kawasan_model->where("deleted",null);
		$records = $this->pengelola_sdm_kawasan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if ($search != "") {
			$this->pengelola_sdm_kawasan_model->where('upper("nama") LIKE \'%' . strtoupper($search) . '%\'');
			$this->pengelola_sdm_kawasan_model->or_where('upper("nama") LIKE \'%' . strtoupper($search) . '%\'');
			$jum	= $this->pengelola_sdm_kawasan_model->count_all();
			$output['recordsTotal'] = $output['recordsFiltered'] = $jum;
		}

		$nomor_urut = $start + 1;
		if (isset($records) && is_array($records) && count($records)) :
			foreach ($records as $record) {
				$row = array();
				$row[]  = $nomor_urut;
				$row[]  = $record->nama;
				// $row[]  = $record->nama;
				// $row[]  = $record->nama;
				$btn_actions = array();
				if ($this->auth->has_permission($this->permissionCreate)) {
					$btn_actions[] = "<a href='" . base_url() . "masters/PengelolaSdmKawasan/createrule/" . $record->id . "' data-toggle='tooltip' title='Tambah rule' tooltip='Tambah rule' class='btn btn-sm btn-info show-modal-custom'><i class='fa fa-plus'></i> </a>";
				}
				if ($this->auth->has_permission($this->permissionEdit)) {
					$btn_actions[] = "<a href='" . base_url() . "masters/PengelolaSdmKawasan/edit/" . $record->id . "/1' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
				}
                if ($this->auth->has_permission($this->permissionGenerate)) {
					$btn_actions[] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Generate Data' class='btn btn-sm btn-success btn-generate'><i class='fa fa-refresh'></i></a>";
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
		$records = $this->pengelola_sdm_kawasan_model->order_by('id')->find_all();
		return download_excel($records, "Master Pengelola Kawasan SDM");
	}

    public function generate()
	{
		$this->auth->restrict($this->permissionGenerate);
		$id = $this->input->post('kode');
		//	die($id);
		if ($id) {
			$rules 		= $this->pengelola_sdm_kawasan_detail_model->find_rule($id);
			foreach ($rules as $rule) {
				
				$akawasans  = $this->pengelola_sdm_kawasan_detail_model->find_kawasan_rule($id,$rule);
				$asatkers   = $this->pengelola_sdm_kawasan_detail_model->find_satker_rule($id,$rule);

				// $this->load->model('pegawai/pegawai_model');
				$this->db->where_in('kawasan_id',$akawasans);
				$this->db->where_in('UNOR_INDUK_ID',$asatkers);
				$this->db->where_not_in('KEDUDUKAN_HUKUM_ID',STATUS_NON_ACTIVE);
				$data_update = array(
					'pengelola_sdm_kawasan_id' => $id,
					'UPDATED_DATE' => date("Y-m-d")
				);
				$this->db->update('hris.pegawai',$data_update);
				log_activity($this->auth->user_id(), "Generate data pengelola kawasan untuk pegawai kawasan :" .json_encode($akawasans). ', unit kerja : '.json_encode($asatkers) .' pengelola kawsan ' .$id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Generate Pengelola Sdm Kawasan sukses", 'success');
			}
			echo "Sukses";
		} else {
			echo "Gagal";
		}

		exit();
	}

}
