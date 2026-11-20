<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatpidana extends Admin_Controller
{
    protected $permissionCreate = 'Riwayatpidana.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayatpidana.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayatpidana.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatpidana.Kepegawaian.View';
    protected $permissionUpdmandiri   = 'Riwayatpidana.Kepegawaian.UpdateMandiri';
    protected $permissionVerifikasi   = 'Riwayatpidana.Kepegawaian.Verifikasi';
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_pidana_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $pns_id = $this->input->post('pns_id');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_pidana_model->where("pns_id",$pns_id);
		$total= $this->riwayat_pidana_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_pidana_model->where('upper("kasus") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_pidana_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "kasus";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_pidana_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_pidana_model->order_by("tanggal_putusan","asc");
        $this->riwayat_pidana_model->where("pns_id",$pns_id);    
		$records=$this->riwayat_pidana_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_pidana_model->where('upper("kasus") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_pidana_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $status = "";
                if ($record->status == 1)
                    $status = "<label class='label label-sm label-success'>Sudah aktif kembali<label>";
                else if ($record->status == 2) 
                    $status = "<label class='label label-sm label-warning'>Sedang menjalani hukuman pidana<label>";
                else if ($record->status == 3) 
                    $status = "<label class='label label-sm label-danger'>Diberhentikan<label>";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nomor_putusan.'/<br>'.getIndonesiaFormat($record->tanggal_putusan);
                $row []  = $record->vonis;
                $row []  = getIndonesiaFormat($record->tanggal_awal).'<br>s.d. '.getIndonesiaFormat($record->tanggal_akhir);
                $row []  = $record->kasus;
                $row []  = $status;
                
                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUpdmandiri))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/Riwayatpidana/editmandiri/".$pns_id."/".$record->id."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data mandiri' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/Riwayatpidana/edit/".$pns_id."/".$record->id."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-pidana'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($pns_id,$record_id='',$mandiri = null){
        Template::set('mandiri', $mandiri);
        //
        if(empty($record_id)){
            if (!$mandiri)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_pidana_add");
            Template::set('pns_id', $pns_id);
            Template::set('toolbar_title', "Tambah Riwayat Pidana");

            Template::render();
        }
        else {
            if (!$mandiri)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_pidana_add");
            $detail_riwayat = $this->riwayat_pidana_model->find($record_id);
            Template::set('detail_riwayat', $detail_riwayat);
            Template::set('pns_id', $pns_id);
            Template::set('toolbar_title', "Ubah Riwayat Pidana");

            Template::render();
        }
    }
    public function add($pns_id){
        $this->show($pns_id);
    }
    public function addmandiri($pns_id){
        $this->auth->restrict($this->permissionUpdmandiri);
        $this->show($pns_id,'',1);
    }
    public function edit($pns_id,$record_id=''){
        $this->show($pns_id,$record_id);
    }
    public function editmandiri($pns_id,$record_id=''){
        $this->show($pns_id,$record_id,1);
    }
    public function verifikasi($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $pns_id = $data->pns_id;
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);
        
        Template::set('id_update_mandiri', $ID);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        //
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_pidana_add");
            
            Template::set('detail_riwayat', $perubahan);
            Template::set('pns_id', $pns_id);
            Template::set('toolbar_title', "Tambah Riwayat Pidana");

            Template::render();
        }
        else {
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_pidana_add");
            $detail_riwayat = $this->riwayat_pidana_model->find($data->ID_TABEL);
            if (!$detail_riwayat)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $detail_riwayat = array_replace((array) $detail_riwayat,(array) $perubahan);
            $detail_riwayat = (object) $detail_riwayat;
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('pns_id', $pns_id);
            Template::set('toolbar_title', "Ubah Riwayat Pidana");

            Template::render();
        }
    }
    public function save(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_pidana_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error validation
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $data = $this->riwayat_pidana_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        if (!$mandiri)
            $this->auth->restrict($this->permissionCreate);
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        $pegawai = $this->pegawai_model->find_by('PNS_ID',$this->input->post('pns_id'));
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];

            $content    = file_get_contents($file_tmp);
            $keterangan = $data['kasus'];
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai->NIP_BARU,$data['nomor_putusan'],167,$keterangan, $content,$file_name,!$mandiri);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
                if (empty($data['id_arsip']))
                    unset($data['id_arsip']);
            }

            if(in_array(strtolower(end($file_ext)),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        } else {
            if ($mandiri && empty($data['id_arsip'])){
                $response ['success']= false;
                $response ['msg']= "<div class='alert alert-block alert-error fade in note note-danger'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                Harap lampirkan bukti dukung
                </h4>
                </div>";
                echo json_encode($response);
                die;
            }
        }
        
        $id_data = $this->input->post("id");
        $data['nip'] = $pegawai->NIP_BARU;
        if(empty($id_data)){
            foreach ($data as $key => $value) {
                if (empty($data[$key]))
                    unset($data[$key]);
            }
        } else {
            foreach ($data as $key => $value) {
                if ($data[$key] == "")
                    $data[$key] = null;
            }
        }
        if (!$mandiri){
            if(isset($id_data) && !empty($id_data)){
                $this->auth->restrict($this->permissionEdit);
                $current = $this->riwayat_pidana_model->find($id_data);
                $perubahan = arrDiff($current,$data);
                $this->riwayat_pidana_model->update($id_data,$data);
                log_activity($this->auth->user_id(), 'Update data Riwayat Pidana: ' . $pegawai->NIP_BARU . ' perubahan : '.json_encode($perubahan). ' '. $this->input->ip_address(), 'pegawai');  
                
            }
            else {
                $id = $this->riwayat_pidana_model->insert($data);
                log_activity($this->auth->user_id(), 'Insert data Riwayat Pidana: ' . $pegawai->NIP_BARU . ' data : '.json_encode($data). '  ' . $this->input->ip_address(), 'pegawai');  
            }
            // update kedudukan hukum
            if ($data['status'] == 1){
                $kh = '01';
            } else if ($data['status'] == 2 && strtotime($data['tanggal_akhir']) > strtotime('now')){
                $kh = '04';
            } else if ($data['status'] == 3){
                $kh = '66';
            }
            if ($kh){
                $this->pegawai_model->update($pegawai->ID, array('KEDUDUKAN_HUKUM_ID' => $kh));
                log_activity($this->auth->user_id(), 'Update data Kedudukan Hukum Pegawai karena pidana : ' . $pegawai->NIP_BARU . ' perubahan : '.$kh. ' '. $this->input->ip_address(), 'pegawai');  
                
            }
        } else {
            if (isset($id_data) && !empty($id_data)) {
                $current = $this->riwayat_pidana_model->find($id_data);
                $perubahan = arrDiff($current,$data, true);
                if ($perubahan[1] != '[]'){
                    $data_update = array();
                    $data_update['pns_id']      = $pegawai->pns_id;
                    $data_update['KOLOM']       = "RIWAYAT_PIDANA";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_PIDANA";
                    $data_update['LEVEL_UPDATE']= 3;
                    $data_update['ID_TABEL']    = $id_data;
                    $data_update['ID_ARSIP']    = $data['id_arsip'];
                    $data_update['UPDATE_TGL']  = date("Y-m-d");
                    $data_update['UPDATED_BY']  = $this->auth->user_id();
                    $id_update = $this->update_mandiri_model->insert($data_update);
                }
            } else {
                $data_update = array();
                $data_update['pns_id']      = $pegawai->pns_id;
                $data_update['KOLOM']       = "RIWAYAT_PIDANA";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_PIDANA";
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        if ($id_update_mandiri){
            $data_update = array();
            $data_update['VERIFIKASI_BY']	= $this->auth->user_id();
            $data_update['VERIFIKASI_TGL']	= date("Y-m-d");
            $data_update['STATUS']			= 1;
            $this->update_mandiri_model->update($id_update_mandiri,$data_update);
            log_activity($this->auth->user_id(), 'Verifikasi update mandiri id : ' . $id_update_mandiri . ' STATUS : 1 ' . $this->input->ip_address(), 'pegawai');

            if ($data['id_arsip']){
                $this->load->model('arsip_digital/arsip_digital_model');
                $this->arsip_digital_model->update($data['id_arsip'], array('ISVALID'=>1));
            }
        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    
    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_pidana_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Pidana : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($pns_id=8){
        Template::set_view("kepegawaian/tab_pane_riwayat_pidana");
        Template::set('pns_id', $pns_id);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_pidana_model->find($id);
        $this->load->model('arsip_digital/arsip_digital_model');
        $arsip = $this->arsip_digital_model->find($datadetil->id_arsip);
        if ($arsip){
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $aws->viewdoc($arsip->location.$arsip->name);
        }
        die();
    }
}
