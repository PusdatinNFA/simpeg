<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Tusi controller
 */
class Tusi extends Admin_Controller
{
	protected $permissionCreate = 'Tusi.Masters.Create';
	protected $permissionDelete = 'Tusi.Masters.Delete';
	protected $permissionEdit   = 'Tusi.Masters.Edit';
	protected $permissionView   = 'Tusi.Masters.View';
	protected $permissionDownload   = 'Tusi.Masters.Download';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters/tusi_model');
	}

	/**
	 * Display a list of kelompok data.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->auth->restrict($this->permissionView);
		Template::set('toolbar_title', "Tusi");
		Template::set("collapse", true);
		Template::set_view("masters/tusi");
		Template::render();
	}

	/**
	 * Create a tusi object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict($this->permissionCreate);

		if (isset($_POST['save'])) {
			if ($insert_id = $this->save_tusi()) {
				log_activity($this->auth->user_id(), 'Insert Tusi : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/tusi');
			}

			// Not validation error
			if (!empty($this->tusi_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->tusi_model->error, 'error');
			}
		}

		Template::set('toolbar_title', "Tambah Tusi");
		Template::set_view('masters/tusi_crud');

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

			redirect('/masters/tusi');
		}
		if (isset($_POST['save'])) {
			$this->auth->restrict($this->permissionEdit);

			if ($this->save_tusi('update', $id)) {

				log_activity($this->auth->user_id(), "Update Tusi " . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Disimpan", 'success');
				redirect('/masters/tusi');
			} else {
				Template::set_message("Data gagal Disimpan " . $this->tusi_model->error, 'error');
			}

			// Not validation error
			if (!empty($this->tusi_model->error)) {
				Template::set_message("Data gagal Disimpan" . $this->tusi_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			$this->auth->restrict($this->permissionDelete);

			if ($this->tusi_model->delete($id)) {
				log_activity($this->auth->user_id(), 'Hapus Tusi: ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Dihapus", 'success');

				redirect('/masters/tusi');
			}

			Template::set_message("Data Gagal Dihapus" . $this->tusi_model->error, 'error');
		}

		Template::set('tusi', $this->tusi_model->find_by("id", $id));
		Template::set_view('masters/tusi_crud');

		Template::set('toolbar_title', "Update Tusi");
		Template::render();
	}

	public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		//	die($id);
		if ($this->tusi_model->delete($id)) {
			log_activity($this->auth->user_id(), "Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
			Template::set_message("Delete Tusi sukses", 'success');
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
    private function save_tusi($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            //die("masuk");
            $_POST['id'] = $id;
        }

        // Validate the data
        // $this->form_validation->set_rules('KODE_JABATAN','KODE_JABATAN','required|max_length[20]|unique[jabatan.KODE_JABATAN' . $extra_unique_rule . ']');

        $this->form_validation->set_rules($this->tusi_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->tusi_model->prep_data($this->input->post());
        // if (empty($data['id']))    
			UNSET($data['id']);
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->tusi_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->tusi_model->update_where("id",$id, $data);
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

		//$this->tusi_model->where("deleted ",null);
		$total = $this->tusi_model->count_all();;
		$output = array();
		$output['draw'] = $draw;


		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if ($search != "") {
			$this->tusi_model->where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$this->tusi_model->or_where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
		}
		$this->tusi_model->limit($length, $start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->tusi_model->order_by($iSortCol, $sSortCol);
		//$this->tusi_model->where("deleted",null);
		$records = $this->tusi_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if ($search != "") {
			$this->tusi_model->where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$this->tusi_model->or_where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$jum	= $this->tusi_model->count_all();
			$output['recordsTotal'] = $output['recordsFiltered'] = $jum;
		}

		$nomor_urut = $start + 1;
		if (isset($records) && is_array($records) && count($records)) :
			foreach ($records as $record) {
				$row = array();
				$row[]  = $nomor_urut;
				$row[]  = $record->name;
				$btn_actions = array();
				if ($this->auth->has_permission($this->permissionEdit)) {
					$btn_actions[] = "<a href='" . base_url() . "masters/tusi/edit/" . $record->id . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
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
		$records = $this->tusi_model->order_by('id')->find_all();
		return download_excel($records, "Master Tusi");
	}
}
