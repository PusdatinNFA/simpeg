<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kawasan controller
 */
class Kawasan extends Admin_Controller
{
	protected $permissionCreate = 'Kawasan.Masters.Create';
	protected $permissionDelete = 'Kawasan.Masters.Delete';
	protected $permissionEdit   = 'Kawasan.Masters.Edit';
	protected $permissionView   = 'Kawasan.Masters.View';
	protected $permissionDownload   = 'Kawasan.Masters.Download';
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters/kawasan_model');
	}

	/**
	 * Display a list of kelompok data.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->auth->restrict($this->permissionView);
		Template::set('toolbar_title', "Kawasan");
		Template::set("collapse", true);
		Template::set_view("masters/kawasan");
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
			if ($insert_id = $this->save_kawasan()) {
				log_activity($this->auth->user_id(), 'Insert Kawasan : ' . $insert_id . ' : ' . $this->input->ip_address(), 'jabatan');
				Template::set_message("Data berhasil disimpan", 'success');
				redirect('/masters/kawasan');
			}

			// Not validation error
			if (!empty($this->kawasan_model->error)) {
				Template::set_message("Mohon lengkapi data" . $this->kawasan_model->error, 'error');
			}
		}

		Template::set('toolbar_title', "Tambah Kawasan");
		Template::set_view('masters/kawasan_crud');

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

			redirect('/masters/kawasan');
		}
		if (isset($_POST['save'])) {
			$this->auth->restrict($this->permissionEdit);

			if ($this->save_kawasan('update', $id)) {

				log_activity($this->auth->user_id(), "Update Kawasan " . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Disimpan", 'success');
				redirect('/masters/kawasan');
			} else {
				Template::set_message("Data gagal Disimpan " . $this->kawasan_model->error, 'error');
			}

			// Not validation error
			if (!empty($this->kawasan_model->error)) {
				Template::set_message("Data gagal Disimpan" . $this->kawasan_model->error, 'error');
			}
		} elseif (isset($_POST['delete'])) {
			$this->auth->restrict($this->permissionDelete);

			if ($this->kawasan_model->delete($id)) {
				log_activity($this->auth->user_id(), 'Hapus Kawasan: ' . $id . ' : ' . $this->input->ip_address(), 'masters');
				Template::set_message("Data Berhasil Dihapus", 'success');

				redirect('/masters/kawasan');
			}

			Template::set_message("Data Gagal Dihapus" . $this->kawasan_model->error, 'error');
		}

		Template::set('kawasan', $this->kawasan_model->find_by("id", $id));
		Template::set_view('masters/kawasan_crud');

		Template::set('toolbar_title', "Update Kawasan");
		Template::render();
	}

	public function delete()
	{
		$this->auth->restrict($this->permissionDelete);
		$id = $this->input->post('kode');
		//	die($id);
		if ($this->kawasan_model->delete($id)) {
			log_activity($this->auth->user_id(), "Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'masters');
			Template::set_message("Delete Kawasan sukses", 'success');
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
    private function save_kawasan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            //die("masuk");
            $_POST['id'] = $id;
        }

        // Validate the data
        // $this->form_validation->set_rules('KODE_JABATAN','KODE_JABATAN','required|max_length[20]|unique[jabatan.KODE_JABATAN' . $extra_unique_rule . ']');

        $this->form_validation->set_rules($this->kawasan_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->kawasan_model->prep_data($this->input->post());
        // if (empty($data['id']))    
			UNSET($data['id']);
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->kawasan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->kawasan_model->update_where("id",$id, $data);
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

		//$this->kawasan_model->where("deleted ",null);
		$total = $this->kawasan_model->count_all();;
		$output = array();
		$output['draw'] = $draw;


		$output['recordsTotal'] = $output['recordsFiltered'] = $total;
		$output['data'] = array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if ($search != "") {
			$this->kawasan_model->where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$this->kawasan_model->or_where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
		}
		$this->kawasan_model->limit($length, $start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "name";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->kawasan_model->order_by($iSortCol, $sSortCol);
		//$this->kawasan_model->where("deleted",null);
		$records = $this->kawasan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if ($search != "") {
			$this->kawasan_model->where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$this->kawasan_model->or_where('upper("name") LIKE \'%' . strtoupper($search) . '%\'');
			$jum	= $this->kawasan_model->count_all();
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
					$btn_actions[] = "<a href='" . base_url() . "masters/kawasan/edit/" . $record->id . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
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
		$records = $this->kawasan_model->order_by('id')->find_all();
		return download_excel($records, "Master Kawasan");
	}
}
