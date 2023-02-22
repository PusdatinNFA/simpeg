<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatpendidikan extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatPendidikan.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatPendidikan.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatPendidikan.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatPendidikan.Kepegawaian.View';
    protected $permissionUpdmandiri   = 'RiwayatPendidikan.Kepegawaian.Updmandiri';
    protected $permissionVerifikasiS   = 'RiwayatPendidikan.Kepegawaian.VerifikasiS';
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_pendidikan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/tingkatpendidikan_model');
        $this->load->model('pegawai/pendidikan_model');//rifkyz
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_pendidikan_model->where("PNS_ID",$PNS_ID);
		$total= $this->riwayat_pendidikan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->riwayat_pendidikan_model->where('upper("NAMA_DIKLAT") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_pendidikan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_pendidikan_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_pendidikan_model->order_by("TAHUN_LULUS","asc");
        $this->riwayat_pendidikan_model->where("PNS_ID",$PNS_ID);    
		$records=$this->riwayat_pendidikan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->riwayat_pendidikan_model->where('upper("NAMA_DIKLAT") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_pendidikan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $STATUS_SATKER  = $record->STATUS_SATKER;
                $STATUS_BIRO    = $record->STATUS_BIRO;
                $msgverifikasi = "";
                // if($STATUS_SATKER == "0")
                //     $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Satker)</b>";
                // if($STATUS_BIRO == "0")
                //     $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                // elseif($STATUS_BIRO == "1")
                //     $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->tk_pendidikan_text.$msgverifikasi;
                $row []  = $record->NAMA_SEKOLAH;
                $row []  = $record->NAMA_PENDIDIKAN;
                $row []  = $record->TAHUN_LULUS;
                $row []  = $record->NOMOR_IJASAH;
                
                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                /*
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."pegawai/riwayatpendidikan/detil/".$PNS_ID."/".$record->ID."'  data-toggle='tooltip' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                */
                // if($this->auth->has_permission("RiwayatPendidikan.Kepegawaian.VerifikasiS"))
                // {    
                //     $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpendidikan/verifikasi/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Verifikasi data' tooltip='Verifikasi data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-edit'></i> </a>";
                // }
                if($this->auth->has_permission($this->permissionUpdmandiri))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpendidikan/editmandiri/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data mandiri' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatpendidikan/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($PNS_ID,$record_id=''){
        $tingkatpendidikans = $this->tingkatpendidikan_model->find_all();
		Template::set('tk_pendidikans', $tingkatpendidikans);
        //rifkyz
        $pendidikans = $this->pendidikan_model->find_all();
        Template::set('pendidikans', $pendidikans);
        //
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_pendidikan_add");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pendidikan");

            Template::render();
        }
        else {
            //$this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_pendidikan_add");
            $detail_riwayat = $this->riwayat_pendidikan_model->find($record_id);
            $selectedPendidikanID = $this->pendidikan_model->find(trim($detail_riwayat->PENDIDIKAN_ID));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($detail_riwayat->PENDIDIKAN_ID)));

            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pendidikan");

            Template::render();
        }
    }
    public function show2($data,$read_only =  false){
        $tingkatpendidikans = $this->tingkatpendidikan_model->find_all();
		Template::set('tk_pendidikans', $tingkatpendidikans);        
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        $pendidikans = $this->pendidikan_model->find_all();
        Template::set('pendidikans', $pendidikans);
        $perubahan = json_decode($data->PERUBAHAN);
        Template::set('read_only', $read_only);
        //
        if(empty($data->ID_TABEL)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_pendidikan_add");
            $detail_riwayat = $perubahan;
            $selectedPendidikanID = $this->pendidikan_model->find(trim($detail_riwayat->PENDIDIKAN_ID));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('selectedPendidikanID',$selectedPendidikanID);
            Template::set('id_update_mandiri',$data->ID);
            Template::set('PNS_ID', $perubahan->PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pendidikan");

            Template::render();
        }
        else {
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_pendidikan_add");
            $detail_riwayat = $this->riwayat_pendidikan_model->find($data->ID_TABEL);
            if (!$detail_riwayat)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $detail_riwayat = array_replace((array) $detail_riwayat,(array) $perubahan);
            $detail_riwayat = (object) $detail_riwayat;
            $selectedPendidikanID = $this->pendidikan_model->find(trim($detail_riwayat->PENDIDIKAN_ID));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('selectedPendidikanID',$selectedPendidikanID);
            Template::set('id_update_mandiri',$data->ID);
            Template::set('PNS_ID', $detail_riwayat->PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pendidikan");

            Template::render();
        }
    }
    public function showmandiri($PNS_ID,$record_id=''){
        $tingkatpendidikans = $this->tingkatpendidikan_model->find_all();
        Template::set('tk_pendidikans', $tingkatpendidikans);
        //rifkyz
        $pendidikans = $this->pendidikan_model->find_all();
        Template::set('pendidikans', $pendidikans);
        //
        if(empty($record_id)){
            $this->auth->restrict($this->permissionUpdmandiri);
            Template::set_view("kepegawaian/riwayat_pendidikan_add_mandiri");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pendidikan");

            Template::render();
        }
        else {
            $this->auth->restrict($this->permissionUpdmandiri);
            Template::set_view("kepegawaian/riwayat_pendidikan_add_mandiri");
            $detail_riwayat = $this->riwayat_pendidikan_model->find($record_id);
            // $selectedPendidikanID = $this->pendidikan_model->find(trim($$detail_riwayat->PENDIDIKAN_ID));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($detail_riwayat->PENDIDIKAN_ID)));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pendidikan");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function addmandiri($PNS_ID){
        $this->auth->restrict($this->permissionUpdmandiri);
        $this->showmandiri($PNS_ID);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function verifikasi($PNS_ID,$record_id=''){
        $this->auth->restrict($this->VerifikasiS);
        $this->show($PNS_ID,$record_id);
    }
    public function editmandiri($PNS_ID,$record_id=''){
        $this->auth->restrict($this->permissionUpdmandiri);
        $this->showmandiri($PNS_ID,$record_id);
    }
    public function verifikasi2($ID,$read_only = false){
        // $this->auth->restrict($this->VerifikasiS);
        $data = $this->update_mandiri_model->find($ID);
        $this->show2($data,$read_only);
    }
    public function detil($PNS_ID,$record_id=''){
        $tingkatpendidikans = $this->tingkatpendidikan_model->find_all();
		Template::set('tk_pendidikans', $tingkatpendidikans);
		Template::set_view("kepegawaian/detilpendidikan");
		Template::set('detail_riwayat', $this->riwayat_pendidikan_model->find($record_id));    
		Template::set('PNS_ID', $PNS_ID);
		Template::set('toolbar_title', "Detil Riwayat Pendidikan");

		Template::render();
    }
    public function save(){
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_pendidikan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $pegawai_data = $this->pegawai_model->find_by("PNS_ID",$this->input->post("PNS_ID"));
        $NIP_BARU = $pegawai_data->NIP_BARU;
        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            // $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

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
            $content = file_get_contents($file_tmp);
            $keterangan = "TANGGAL LULUS ".$this->input->post('TANGGAL_LULUS');
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id_jenis_arsip = 0;
            if ($data['TINGKAT_PENDIDIKAN_ID'] == '05'){
                $id_jenis_arsip = 6;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '10'){
                $id_jenis_arsip = 7;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '12'){
                $id_jenis_arsip = 7;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '15'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '17'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '18'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '20'){
                $id_jenis_arsip = 9;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '25'){
                $id_jenis_arsip = 10;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '30'){
                $id_jenis_arsip = 11;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '35'){
                $id_jenis_arsip = 116;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '40'){
                $id_jenis_arsip = 12;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '45'){
                $id_jenis_arsip = 13;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '50'){
                $id_jenis_arsip = 14;
            } else {
                $id_jenis_arsip = 83;
            }

            $id = $aws->insertArsip($NIP_BARU,$data['NOMOR_IJASAH'],$id_jenis_arsip,$keterangan, $content,$file_name);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
                if (empty($data['id_arsip']))
                    unset($data['id_arsip']);
            }
        }
        // if($base64 == "")
        //     unset($data['FILE_BASE64']);
        // else{
        //     $data['FILE_BASE64']        = $base64;
        //     $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
        // }
        // end upload berkas
        if(empty($data['TANGGAL_LULUS'])){
            unset($data['TANGGAL_LULUS']);
        }
        if($this->input->post("STATUS_SATKER") == "1"){
            $data["STATUS_SATKER"]    = 1;
        }else{
            $data["STATUS_SATKER"]    = 0;
        }
        if($this->input->post("STATUS_BIRO") == "1"){
            $data["STATUS_BIRO"]    = 1;
        }else{
            $data["STATUS_BIRO"]    = 0;
        }
        if($this->input->post("PENDIDIKAN_TERAKHIR") == "1"){
            $dataupdate = array();
            $dataupdate["PENDIDIKAN_TERAKHIR"] = 0;
            $this->riwayat_pendidikan_model->update_where("PNS_ID",$this->input->post("PNS_ID"), $dataupdate);
            $data["PENDIDIKAN_TERAKHIR"]    = 1;

            $recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
            $dataupdate = array();
            $dataupdate['PENDIDIKAN_ID']    = $this->input->post('PENDIDIKAN_ID');
            $dataupdate['PENDIDIKAN']       = isset($recpendidikan->NAMA) ? $recpendidikan->NAMA : "";
            $dataupdate['TK_PENDIDIKAN']    = $this->input->post('TINGKAT_PENDIDIKAN_ID');
            $dataupdate['nama_sekolah_terakhir']    = $this->input->post('NAMA_SEKOLAH');
            $this->pegawai_model->skip_validation(true);
            $this->pegawai_model->update_where("PNS_ID",$this->input->post("PNS_ID"), $dataupdate);
        }
        $id_data = $this->input->post("id_data");
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        if(isset($id_data) && !empty($id_data)){
            $current = $this->riwayat_pendidikan_model->find($id_data);
            $perubahan = arrDiff($current,$data);
            $this->riwayat_pendidikan_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Update data Riwayat pendidikan: ' . $NIP_BARU . ' perubahan : '.json_encode($perubahan).' ' . $this->input->ip_address(), 'pegawai');  
        }
        else {
            $id = $this->riwayat_pendidikan_model->insert($data);
            log_activity($this->auth->user_id(), 'Insert data Riwayat pendidikan: ' .$NIP_BARU . ' data : '.json_encode($data). ' : ' . $this->input->ip_address(), 'pegawai');  
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
    public function savemandiri(){
        $this->auth->restrict($this->permissionUpdmandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_pendidikan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }

       $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
       $pegawai_data = $this->pegawai_model->find_by("PNS_ID",$this->input->post("PNS_ID"));
       // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

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
            $content = file_get_contents($file_tmp);

            $keterangan = "TANGGAL LULUS ".$this->input->post('TANGGAL_LULUS');
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id_jenis_arsip = 0;
            if ($data['TINGKAT_PENDIDIKAN_ID'] == '05'){
                $id_jenis_arsip = 6;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '10'){
                $id_jenis_arsip = 7;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '12'){
                $id_jenis_arsip = 7;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '15'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '17'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '18'){
                $id_jenis_arsip = 8;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '20'){
                $id_jenis_arsip = 9;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '25'){
                $id_jenis_arsip = 10;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '30'){
                $id_jenis_arsip = 11;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '35'){
                $id_jenis_arsip = 116;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '40'){
                $id_jenis_arsip = 12;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '45'){
                $id_jenis_arsip = 13;
            } else if ($data['TINGKAT_PENDIDIKAN_ID'] == '50'){
                $id_jenis_arsip = 14;
            } else {
                $id_jenis_arsip = 83;
            }
            
            $NIP_BARU = $pegawai_data->NIP_BARU;
            $id = $aws->insertArsip($NIP_BARU,$data['NOMOR_IJASAH'],$id_jenis_arsip,$keterangan, $content,$file_name,0);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
                if (empty($data['id_arsip']))
                    unset($data['id_arsip']);
            }
        } else {
            if (empty($data['id_arsip'])){
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
        $data['NIP'] = $NIP_BARU;
        
       
        $id_data = $this->input->post("id_data");
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
        if(isset($id_data) && !empty($id_data)){
            $current = $this->riwayat_pendidikan_model->find($id_data);
            $perubahan = arrDiff($current,$data, true);
            if ($perubahan[1] != '[]'){ 
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_PENDIDIKAN";
                $data_update['DARI']        = $perubahan[0];
                $data_update['PERUBAHAN']   = $perubahan[1];
                $data_update['NAMA_KOLOM']  = "RIWAYAT_PENDIDIKAN";
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['ID_TABEL']    = $id_data;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        else{
            $data_update = array();
            $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
            $data_update['KOLOM']       = "RIWAYAT_PENDIDIKAN";
            $data_update['DARI']        = null;
            $data_update['PERUBAHAN']   = json_encode($data);
            $data_update['NAMA_KOLOM']  = "RIWAYAT_PENDIDIKAN";
            $data_update['LEVEL_UPDATE']= 3;
            $data_update['UPDATE_TGL']  = date("Y-m-d");
            $data_update['ID_ARSIP']    = $data['id_arsip'];
            $data_update['UPDATED_BY']  = $this->auth->user_id();
            $id_update = $this->update_mandiri_model->insert($data_update);

        }
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    
    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
        $record = $this->riwayat_pendidikan_model->find($record_id);
        if ($record){
            if ($record->STATUS_BIRO == 0){
                $update = $this->update_mandiri_model->find_by(array ('PNS_ID' => $record->PNS_ID, 'NAMA_KOLOM' => 'RIWAYAT PENDIDIKAN', 'ID_TABEL' => $record_id));
                if ($update){
                    $this->update_mandiri_model->update($update->ID, array('STATUS' => 4));
                }
            }
        }
		if ($this->riwayat_pendidikan_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Diklat Struktural : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID=8){
        Template::set_view("kepegawaian/tab_pane_riwayat_diklat_struktural");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_pendidikan_model->find($id);
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
