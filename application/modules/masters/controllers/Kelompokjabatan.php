<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kelompok Jabatan controller
 */
class Kelompokjabatan extends Admin_Controller
{
	protected $permissionCreate = 'KelompokJabatan.Masters.Create';
	protected $permissionDelete = 'KelompokJabatan.Masters.Delete';
	protected $permissionEdit   = 'KelompokJabatan.Masters.Edit';
	protected $permissionView   = 'KelompokJabatan.Masters.View';
	protected $permissionDownload   = 'KelompokJabatan.Masters.Download';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('jabatan/kelompok_jabatan_model');
	}

	/**
	 * Display a list of kelompok data.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->auth->restrict($this->permissionView);
		Template::set('toolbar_title', "Kelompok Jabatan");
		Template::set("collapse", true);
		Template::set_view("masters/kelompok_jabatan");
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
			if ($insert_id = $this->save_kelompok_jabatan()) {
				log_activity($this->auth->user_id(), 'Insert Kelompok Jabatan : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/kelompokjabatan');
			}

			// Not validation error
			if (!empty($this->kelompok_jabatan_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->kelompok_jabatan_model->error, 'error');
			}
		}

		Template::set('toolbar_title', "Tambah Kelompok Jabatan");
		Template::set_view('masters/kelompok_jabatan_crud');

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

			redirect('/masters/kelompokjabatan');
		}
		if (isset($_POST['save'])) {
			$this->auth->restrict($this->permissionEdit);

			if ($this->save_kelompok_jabatan('update', $id)) {

				log_activity($this->auth->user_id(), "Update Kelompok Jabatan " . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Disimpan", 'success');
				redirect('/masters/kelompokjabatan');
			} else {
				Template::set_message("Data gagal Disimpan " . $this->kelompok_jabatan_model->error, 'error');
			}

			// Not validation error
			if (!empty($this->kelompok_jabatan_model->error)) {
				Template::set_message("Data gagal Disimpan" . $this->kelompok_jabatan_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			$this->auth->restrict($this->permissionDelete);

			if ($this->kelompok_jabatan_model->delete($id)) {
				log_activity($this->auth->user_id(), 'Hapus Kelompok Jabatan: ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Dihapus", 'success');

				redirect('/masters/kelompokjabatan');
			}

			Template::set_message("Data Gagal Dihapus" . $this->kelompok_jabatan_model->error, 'error');
		}

		Template::set('kelompok_jabatan', $this->kelompok_jabatan_model->find_by("id", $id));
		Template::set_view('masters/kelompok_jabatan_crud');

		Template::set('toolbar_title', "Ubah Kelompok Jabatan");
		Template::render();
	}

	public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		//	die($id);
		if ($this->kelompok_jabatan_model->delete($id)) {
			log_activity($this->auth->user_id(), "Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
			Template::set_message("Delete Kelompok Jabatan sukses", 'success');
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
    private function save_kelompok_jabatan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            //die("masuk");
            $_POST['id'] = $id;
        }

        // Validate the data
        // $this->form_validation->set_rules('KODE_JABATAN','KODE_JABATAN','required|max_length[20]|unique[jabatan.KODE_JABATAN' . $extra_unique_rule . ']');

        $this->form_validation->set_rules($this->kelompok_jabatan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->kelompok_jabatan_model->prep_data($this->input->post());
        // if (empty($data['id']))    
			UNSET($data['id']);
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->kelompok_jabatan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->kelompok_jabatan_model->update_where("id",$id, $data);
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

		//$this->kelompok_jabatan_model->where("deleted ",null);
		$total = $this->kelompok_jabatan_model->count_all();;
		$output = array();
		$output['draw'] = $draw;


		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if ($search != "") {
			$this->kelompok_jabatan_model->where('upper("kel_jabatan") LIKE \'%' . strtoupper($search) . '%\'');
			$this->kelompok_jabatan_model->or_where('upper("kel_jabatan") LIKE \'%' . strtoupper($search) . '%\'');
		}
		$this->kelompok_jabatan_model->limit($length, $start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "kel_jabatan";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->kelompok_jabatan_model->order_by($iSortCol, $sSortCol);
		//$this->kelompok_jabatan_model->where("deleted",null);
		$records = $this->kelompok_jabatan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if ($search != "") {
			$this->kelompok_jabatan_model->where('upper("kel_jabatan") LIKE \'%' . strtoupper($search) . '%\'');
			$this->kelompok_jabatan_model->or_where('upper("kel_jabatan") LIKE \'%' . strtoupper($search) . '%\'');
			$jum	= $this->kelompok_jabatan_model->count_all();
			$output['recordsTotal'] = $output['recordsFiltered'] = $jum;
		}

		$nomor_urut = $start + 1;
		if (isset($records) && is_array($records) && count($records)) :
			foreach ($records as $record) {
				$row = array();
				$row[]  = $nomor_urut;
				$row[]  = $record->kel_jabatan;
				$btn_actions = array();
				if ($this->auth->has_permission($this->permissionEdit)) {
					$btn_actions[] = "<a href='" . base_url() . "masters/kelompokjabatan/edit/" . $record->id . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
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
		$records = $this->kelompok_jabatan_model->order_by('id')->find_all();
		return download_excel($records, "Master Kelompok Jabatan");
	}
}
