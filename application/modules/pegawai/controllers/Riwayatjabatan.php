<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatjabatan extends Admin_Controller
{
    protected $permissionCreate = 'riwayatjabatan.Kepegawaian.Create';
    protected $permissionDelete = 'riwayatjabatan.Kepegawaian.Delete';
    protected $permissionEdit   = 'riwayatjabatan.Kepegawaian.Edit';
    protected $permissionVerifikasiS   = 'riwayatjabatan.Kepegawaian.VerifikasiS';
    protected $permissionUpdateMandiri   = 'riwayatjabatan.Kepegawaian.Updmandiri';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_jabatan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $this->load->model('pegawai/update_mandiri_model');
		$this->load->model('pegawai/kawasan_model');
        $this->load->model('pegawai/tusi_model');
		$this->load->model('pegawai/unitkerja_model');
		$this->load->model('pegawai/eselon_model');
		$this->load->helper('aplikasi');
		$eselons = $this->eselon_model->find_all();
        Template::set('eselons', $eselons);
    }
	
    public function ajax_list(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $NIP_BARU = $this->input->post('NIP_BARU');
        $PNS_ID = $this->input->post('PNS_ID');
        if(empty($NIP_BARU)){
            die("NIP KOSONG");
        }
        // $this->pegawai_model->where("PNS_ID",$PNS_ID);
        // $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_jabatan_model->where("PNS_NIP",$NIP_BARU);
		$total= $this->riwayat_jabatan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		
		if($search!=""){
			$this->riwayat_jabatan_model->where('upper("NAMA_JABATAN") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_jabatan_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
        
		$kolom = $iSortCol != "" ? $iSortCol : "TMT_JABATAN";
        $sSortCol == "desc" ? "desc" : "asc";

        //$this->riwayat_jabatan_model->where("PNS_ID",$pegawai_data->PNS_ID); 
        $this->riwayat_jabatan_model->where("PNS_NIP",$NIP_BARU); 
        $this->riwayat_jabatan_model->order_by($kolom,$sSortCol);
        $records=$this->riwayat_jabatan_model->find_all();
		if($search != "")
		{
			$this->riwayat_jabatan_model->where('upper("NAMA_JABATAN") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->riwayat_jabatan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                // $STATUS_SATKER  = $record->STATUS_SATKER;
                // $STATUS_BIRO    = $record->STATUS_BIRO;
                // $msgverifikasi = "";
                // if($STATUS_SATKER == "0")
                //     $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Satker)</b>";
                // if($STATUS_BIRO == "0")
                //     $msgverifikasi = "<br><b class='text-red'>(Perlu Verifikasi Biro)</b>";
                // elseif($STATUS_BIRO == "1")
                //     $msgverifikasi = "<br><b class='text-blue'>(Sudah diverifikasi Biro)</b>";
                $row = array();
                $row []  = $nomor_urut;
                if ($record->ID_JENIS_JABATAN == '2'){
                    $row []  = strtoupper($record->NAMA_JABATAN)."<br><i><b>NO SK :</b>".$record->NOMOR_SK."</i><br><b><i>NO PAK</b> : ".$record->nomor_pak."</i><br><b><i>AK</b> :".$record->ANGKA_KREDIT."</i>";
                } else {
                    $row []  = strtoupper($record->NAMA_JABATAN)."<br><i><b>NO SK :</b>".$record->NOMOR_SK."</i>";
                }
                if($record->ID_UNOR)
                    $row []  = ($record->UNOR != $record->NAMA_SATKER) ? $record->UNOR."<br>".$record->NAMA_SATKER : $record->NAMA_SATKER;
                else
                    $row[] = implode("<br>",array($record->ESELON2,$record->ESELON1,$record->INSTANSI));
                $row []  = $convert->fmtDate($record->TMT_JABATAN,"dd-mm-yyyy");
                $row []  = $convert->fmtDate($record->TERMINATED_DATE,"dd-mm-yyyy");
                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUpdateMandiri))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/editmandiri/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data mandiri' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission("riwayatjabatan.Kepegawaian.VerifikasiS")){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatjabatan/verifikasi/".$NIP_BARU."/".$record->ID."' data-toggle='tooltip' title='Verifikasi data' tooltip='Verifikasi data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-key'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-jabatan'><i class='fa fa-trash-o'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($PNS_ID,$record_id='',$mandiri = null){
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
        Template::set('jenis_jabatans', $jenis_jabatans);
    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        $kawasans = $this->kawasan_model->find_all();
        Template::set('kawasans', $kawasans);
        $this->load->model('pegawai/tusi_model');
        $tusis = $this->tusi_model->find_all();
        Template::set('tusis', $tusis);
        Template::set('mandiri', $mandiri);

        if(empty($record_id)){
            if (!$mandiri)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Jabatan");
            Template::render();
        }
        else {
    		if (!$mandiri)
                $this->auth->restrict($this->permissionEdit);
            $datadetil = $this->riwayat_jabatan_model->find($record_id); 
        	$recordunors = $this->unitkerja_model->find_all(trim($datadetil->ID_SATUAN_KERJA));
        	Template::set('recunor', $recordunors);
        	
            
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
           
            $jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
			if($datadetil->ID_JENIS_JABATAN == ""){
				$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
				$jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
			}
	           $record_jabatan = $this->jabatan_model->find_select(trim($jenis_jabatan));
			Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		    Template::set('jenis_jabatan', $jenis_jabatan);    
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Jabatan");

            Template::render();
        }
    }
    public function showmandiri($PNS_ID,$record_id=''){
        Template::set('recsatker', $this->unitkerja_model->find_satker());
        if(empty($record_id)){
            $this->auth->restrict($this->permissionUpdateMandiri);
            Template::set_view("kepegawaian/riwayat_jabatan_crud_mandiri");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Jabatan");
            Template::render();
        }
        else {
            $datadetil = $this->riwayat_jabatan_model->find($record_id); 
            $recordunors = $this->unitkerja_model->find_all($datadetil->ID_SATUAN_KERJA);
            Template::set('recunor', $recordunors);
            
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
           
            $jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
          
            if($datadetil->ID_JENIS_JABATAN == ""){
                $recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
                $jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
            }
      
            Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
            Template::set('jenis_jabatan', $jenis_jabatan);    
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Jabatan");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function addmandiri($PNS_ID){
        $this->show($PNS_ID,'',1);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function editmandiri($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id,1);
    }
    public function verifikasi($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
        Template::set('jenis_jabatans', $jenis_jabatans);
    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        $kawasans = $this->kawasan_model->find_all();
        Template::set('kawasans', $kawasans);
        $this->load->model('pegawai/tusi_model');
        $tusis = $this->tusi_model->find_all();
        Template::set('tusis', $tusis);
        Template::set('id_update_mandiri', $ID);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }

        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
            $jenis_jabatan = $perubahan->ID_JENIS_JABATAN;
            $record_jabatan = $this->jabatan_model->find_select(trim($jenis_jabatan));
            Template::set('detail_riwayat', $perubahan);    
			Template::set('jabatans', $record_jabatan);    
		    Template::set('jenis_jabatan', $jenis_jabatan); 
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Jabatan");
            Template::render();
        }
        else {
    		if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            $datadetil = $this->riwayat_jabatan_model->find($data->ID_TABEL); 
            if (!$datadetil)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
        	$recordunors = $this->unitkerja_model->find_all(trim($datadetil->ID_SATUAN_KERJA));
        	Template::set('recunor', $recordunors); 
            Template::set('detail_riwayat', $datadetil);          	
            
            Template::set_view("kepegawaian/riwayat_jabatan_crud");
            $datadetil = array_replace((array) $datadetil,(array) $perubahan);
            $datadetil = (object) $datadetil;

            $jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
			if($datadetil->ID_JENIS_JABATAN == ""){
				$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
				$jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
			}
            

	        $record_jabatan = $this->jabatan_model->find_select(trim($jenis_jabatan));
			Template::set('jabatans', $record_jabatan);    
		    Template::set('jenis_jabatan', $jenis_jabatan);    
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Jabatan");

            Template::render();
        }
    }
    public function detil($PNS_ID,$record_id=''){
    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        $datadetil = $this->riwayat_jabatan_model->find($record_id); 
		$recordunors = $this->unitkerja_model->find_all($datadetil->ID_SATUAN_KERJA);
		$jenis_jabatan = $datadetil->ID_JENIS_JABATAN;
		  
		if($datadetil->ID_JENIS_JABATAN == ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($datadetil->ID_JABATAN));
			$jenis_jabatan = ISSET($recjabatan->JENIS_JABATAN) ? TRIM($recjabatan->JENIS_JABATAN) : "";
		}
	  
		Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		Template::set('jenis_jabatan', $jenis_jabatan);    
		
		Template::set('recunor', $recordunors);
	   
		//$this->auth->restrict($this->permissionEdit);
		Template::set_view("kepegawaian/detiljabatan");
	  
		Template::set('jabatans', $this->jabatan_model->find_select($jenis_jabatan));    
		Template::set('detail_riwayat', $datadetil);    
		Template::set('PNS_ID', $PNS_ID);
		Template::set('toolbar_title', "Riwayat Jabatan");

		Template::render();
    }
    
    public function save(){
         // Validate the data
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        if (!$mandiri)
            $this->auth->restrict($this->permissionCreate);
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        $pegawai_data = $this->pegawai_model->find_by("PNS_ID",$this->input->post("PNS_ID"));
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if ((isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) ||  (isset($_FILES['file_dokumen2']) && $_FILES['file_dokumen2']['name']) ) 
        {
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']){
                $errors=array();
                $allowed_ext = array('pdf');
                $file_name  = $_FILES['file_dokumen']['name'];
                $file_ext   = explode('.',$file_name);
                $jmltitik   = count($file_ext);
                $file_size  = $_FILES['file_dokumen']['size'];
                $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
                $type       = $_FILES['file_dokumen']['type'];
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
                $content    = file_get_contents($file_tmp);
                $keterangan = "TMT ".$data['TMT_JABATAN'];
                $id_jenis_arsip = 23;
                $result_s3 = $aws->insertArsip($pegawai_data->NIP_BARU,$data['NOMOR_SK'],$id_jenis_arsip,$keterangan,$content,$file_name);
                if ($result_s3){
                    $data['id_arsip'] = $result_s3;
                } else {
                    if (empty($data['id_arsip']))
                        unset($data['id_arsip']);
                }
                
                $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
            }
            // sk pemberhentian 
            if (isset($_FILES['file_dokumen2']) && $_FILES['file_dokumen2']['name']){
                $errors=array();
                $allowed_ext = array('pdf');
                $file_name  = $_FILES['file_dokumen2']['name'];
                $file_ext   = explode('.',$file_name);
                $jmltitik   = count($file_ext);
                $file_size  = $_FILES['file_dokumen2']['size'];
                $file_tmp   = $_FILES['file_dokumen2']['tmp_name'];
                $type       = $_FILES['file_dokumen2']['type'];
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

                $content    = file_get_contents($file_tmp);            
                $keterangan = "TMT ".$data['TERMINATED_DATE'];
                $id_jenis_arsip = 24;
                $result_s3 = $aws->insertArsip($pegawai_data->NIP_BARU,$data['SK_PEMBERHENTIAN_NOMOR'],$id_jenis_arsip,$keterangan,$content,$file_name);
                if ($result_s3){
                    $data['id_arsip_pemberhentian'] = $result_s3;
                }
            }

        } else {
            if ($mandiri && (empty($data['id_arsip']) && empty($data['id_arsip_pemberhentian']))){
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
         
        // end upload berkas
        if ($pegawai_data->NIP_BARU == "") {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                Pegawai tidak ditemukan
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $NIP_BARU = $pegawai_data->NIP_BARU;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        
        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";
        $data["ID_JABATAN"]     = $this->input->post("ID_JABATAN");
        $data["NAMA_JABATAN"]   = isset($rec_jabatan->NAMA_JABATAN) ? $rec_jabatan->NAMA_JABATAN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("ID",$this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if($this->input->post("ID_JENIS_JABATAN") == "1"){
            if($this->input->post("ID_UNOR") != ""){
                // $data["NAMA_JABATAN"] = $rec_jabatan_struktural->NAMA_JABATAN != "" ? $rec_jabatan_struktural->NAMA_JABATAN : "";
            }
        }else{
            if($this->input->post("ID_JABATAN") != ""){
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;

            }

        }
        $data["ID_SATUAN_KERJA"]        = trim($this->input->post("ID_SATUAN_KERJA"));
        $data["ID_SATKER_PENUGASAN"]    = trim($this->input->post("ID_SATKER_PENUGASAN"));
        // if(empty($data["ID_SATUAN_KERJA"])){
        //     unset($data["ID_SATUAN_KERJA"]);
        // }
        // if(empty($data["ID_SATKER_PENUGASAN"])){
        //     unset($data["ID_SATKER_PENUGASAN"]);
        // }
        // if(empty($data["TMT_JABATAN"])){
        //     unset($data["TMT_JABATAN"]);
        // }
        // if(empty($data["TANGGAL_SK"])){
        //     unset($data["TANGGAL_SK"]);
        // }
        // if(empty($data["TMT_PELANTIKAN"])){
        //     unset($data["TMT_PELANTIKAN"]);
        // }
        
        $data['SK_PEMBERHENTIAN_TANGGAL']            = $this->input->post('TERMINATED_DATE') ? $this->input->post('TERMINATED_DATE') : null;
        $data['TERMINATED_DATE']                     = $this->input->post('TERMINATED_DATE') ? $this->input->post('TERMINATED_DATE') : null;
        $data['kawasan_id']                          = $this->input->post('kawasan_id') ? $this->input->post('kawasan_id') : null;
        $data['tusi_id']                             = $this->input->post('tusi_id') ? $this->input->post('tusi_id') : null;
        $data['status_jabatan']                      = $this->input->post('status_jabatan');
        $data["LAST_UPDATED"] 	                     = date("Y-m-d");
        if($this->input->post("ID_JENIS_JABATAN")==2){
            $data["ANGKA_KREDIT"]                        = $this->input->post('ANGKA_KREDIT') ? $this->input->post('ANGKA_KREDIT') : null;
            $data["nomor_pak"]                           = $this->input->post('nomor_pak') ? $this->input->post('nomor_pak') : null;
            $data["tanggal_pak"]                         = $this->input->post('tanggal_pak') ? $this->input->post('tanggal_pak') : null;
            $data["kepakaran"]                           = $this->input->post('kepakaran') ? $this->input->post('kepakaran') : null;
        }

        if ($data['TERMINATED_DATE']){
            if($data["ID_JABATAN"] == 124 || $data["ID_JABATAN"] == 1475 || $data["ID_JABATAN"] == 5594){ //prod 
            // if($data["ID_JABATAN"] == 930 || $data["ID_JABATAN"] == 931){ //prod 
                $this->roleSekretaris($pegawai_data->username_intra, $data["ID_SATKER_PENUGASAN"]);
            }
        }
        
        $id_data = $this->input->post("ID");
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
                $current = $this->riwayat_jabatan_model->find($id_data);
                $perubahan = arrDiff($current,$data);
                $this->riwayat_jabatan_model->update($id_data,$data,$this->input->post());
                // add log
                log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Update data Riwayat Jabatan", 'rwt_jabatan');
                log_activity($this->auth->user_id(),"Update data Riwayat Jabatan ".$pegawai_data->NIP_BARU.' perubahan '.json_encode($perubahan), 'rwt_jabatan');
            }
            else{
                $this->riwayat_jabatan_model->insert($data,$this->input->post());
                // add log
                log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data Riwayat Jabatan", 'rwt_jabatan');
                log_activity($this->auth->user_id(),"Tambah data Riwayat Jabatan".$pegawai_data->NIP_BARU.' data '.json_encode($data), 'rwt_jabatan');
            }
            // update profile menjadi jabawan aktif dan penempatan terbaru
            $this->set_jabatan_aktif($NIP_BARU);
            $this->savePindahUnitkerja($pegawai_data,$data);
            $this->set_unor_active($NIP_BARU);
            // $this->setPengelolaSDMKawasan($NIP_BARU);
        } else {
            if(isset($id_data) && !empty($id_data)){
                $current = $this->riwayat_jabatan_model->find($id_data);
                $perubahan = arrDiff($current,$data, true);
                if ($perubahan[1] != '[]'){
                    $data_update = array();
                    $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                    $data_update['KOLOM']       = "RIWAYAT_JABATAN";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_JABATAN";
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
                $data_update['KOLOM']       = "RIWAYAT_JABATAN";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_JABATAN";
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
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function updateJabatanAktifAll()
    {
        $this->pegawai_model->where_not_in('KEDUDUKAN_HUKUM_ID', STATUS_NON_ACTIVE);
        $pegawais = $this->pegawai_model->find_all();
        foreach ($pegawais as $pegawai) {
            $this->set_jabatan_aktif($pegawai->NIP_BARU);            
            log_activity($this->auth->user_id(), 'Update semua Jabatan Aktif Pegawai : ' . $pegawai->NIP_BARU . '  ' . $this->input->ip_address(), 'pegawai');
            $this->set_unor_active($pegawai->NIP_BARU);
            log_activity($this->auth->user_id(), 'Update semua Unor Aktif Pegawai : ' . $pegawai->NIP_BARU . '  ' . $this->input->ip_address(), 'pegawai');
            sleep(10);
        }
    }
    private function set_jabatan_aktif($nip){
        $jabatan_aktifs = $this->riwayat_jabatan_model->limit(1)->find_aktif($nip);
        if($jabatan_aktifs[0]->ID_JABATAN != ""){
            $jabatan_aktifs = $jabatan_aktifs[0];
            // tambah/hapus role sekretaris
            // if($jabatan_aktifs->ID_JABATAN == 930 || $jabatan_aktifs->ID_JABATAN == 931){ //dev
            if($jabatan_aktifs->ID_JABATAN == 124 || $jabatan_aktifs->ID_JABATAN == 1475 || $jabatan_aktifs->ID_JABATAN == 5594){ //prod 
                // tambah
                $data_pegawai   = $this->pegawai_model->find_by("NIP_BARU",$nip);
                if ($data_pegawai)
                    $this->roleSekretaris($data_pegawai->username_intra,$jabatan_aktifs->ID_SATKER_PENUGASAN,false);
            }
            $rec_jabatan    = $this->jabatan_model->find_by("KODE_JABATAN",$jabatan_aktifs->ID_JABATAN);
            $rec_unitkerja  = $this->unitkerja_model->find_by("KODE_INTERNAL",$jabatan_aktifs->ID_UNOR); // POST
            $dataupdate = array();
            if($jabatan_aktifs->ID_JABATAN != ""){
                $jabatan_apply = $jabatan_aktifs;
                $jabatan_real = $this->riwayat_jabatan_model->limit(1)->find_aktif($nip,true);
                if ($jabatan_real){
                    $jabatan_apply = $jabatan_real[0];
                    $rec_jabatan    = $this->jabatan_model->find_by("KODE_JABATAN",$jabatan_apply->ID_JABATAN);
                }
                $dataupdate['JABATAN_INSTANSI_REAL_ID']     = $jabatan_apply->ID_JABATAN;
                $dataupdate['TMT_JABATAN']                  = $jabatan_apply->TMT_JABATAN;
                $dataupdate['JENIS_JABATAN_ID']             = $jabatan_apply->ID_JENIS_JABATAN ? $jabatan_apply->ID_JENIS_JABATAN : null;
                $dataupdate['JABATAN_NAMA']                 = $rec_jabatan->NAMA_JABATAN ? $rec_jabatan->NAMA_JABATAN : null;
                $dataupdate['JABATAN_INSTANSI_ID']          = $jabatan_apply->ID_JABATAN;
            }
            $dataupdate['kawasan_id']                   = $jabatan_aktifs->kawasan_id;
            $dataupdate['tusi_id']                      = $jabatan_aktifs->tusi_id;
            // update unor dan satker jika ada isinya
            // if($jabatan_aktifs->ID_UNOR != ""){
            //     $dataupdate['UNOR_ID']                      = $jabatan_aktifs->ID_UNOR ? $jabatan_aktifs->ID_UNOR : null;  
            // }
            // if($rec_unitkerja->UNOR_INDUK != ""){
            //     $dataupdate['UNOR_INDUK_ID']                = $rec_unitkerja->UNOR_INDUK ? $rec_unitkerja->UNOR_INDUK : null;    
            // }
            // if($jabatan_aktifs->ID_SATKER_PENUGASAN != ""){
            //     $dataupdate['satker_penempatan_id']         = $jabatan_aktifs->ID_SATKER_PENUGASAN;
            // }
            $this->pegawai_model->skip_validation(true);
            $this->pegawai_model->update_where("NIP_BARU",$nip, $dataupdate);
            // jika jabatan strktural, ubah juga ke tabel unitkerja di kolom pejabat
            if($jabatan_aktifs->ID_JENIS_JABATAN == '1' or $jabatan_aktifs->ID_JENIS_JABATAN == '7'){
                $data_pegawai   = $this->pegawai_model->find_by("NIP_BARU",$nip);
                $data_unit      = array();
                $data_unit['NAMA_PEJABAT']      = $data_pegawai->NAMA;
                $data_unit['PEMIMPIN_PNS_ID']   = $data_pegawai->PNS_ID;
                $data_unit['JABATAN_ID']        = $jabatan_aktifs->ID_JABATAN;
                $data_unit['status_jabatan']    = $jabatan_aktifs->status_jabatan;
                
                $this->unitkerja_model->skip_validation(true);
                $this->unitkerja_model->update_where("ID",TRIM($jabatan_aktifs->ID_UNOR), $data_unit);
                $this->cache->delete("aunor");
            }
        }
    }

    public function roleSekretaris($username_intra,$unor_id, $hapus = true)
    {
        $data = array (
            'usernameintra' => $username_intra,
            'satuan_kerja_id' => $unor_id
        );
        $path = 'intra/user/add_role_sekretaris';
        $method = 'POST';
        if ($hapus){ 
            $path = 'intra/user/delete_role_modul_sekretaris';
            $method = "DELETE";
        }
        $this->load->library('Api_brin');
        $api_brin = new Api_brin;
        $token = $api_brin->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => END_POINT_DS.$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => 'usernameintra='.$username_intra.'&satuan_kerja_id='.$unor_id,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token,
                'Cookie: ci_session=cn8caik1mvmsmv2rp1gd5pafeuk26f24'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $respon = json_decode($response);
        $log = $hapus? "Hapus":"Tambah" ;
        $log .=" role sekretaris ".json_encode($data)." response ".json_encode($respon);
        log_activity($this->auth->user_id(),$log, 'rwt_jabatan');
        return $respon->success;
    }
    public function savemandiri()
    {
        $this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->riwayat_jabatan_model->get_validation_rules());
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
        
        
        $data = $this->riwayat_jabatan_model->prep_data($this->input->post());
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
            $file_name  = $_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            
            $keterangan = "TMT ".$data['TMT_JABATAN'];
            $id_jenis_arsip = 23;
            $result_s3 = $aws->insertArsip($this->input->post('PNS_ID'),$data['NOMOR_SK'],$id_jenis_arsip,$keterangan,$content,$file_name,0);
            if ($result_s3){
                $data['id_arsip'] = $result_s3;
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
        // end upload berkas
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        
        $rec_jenis = $this->jenis_jabatan_model->find($this->input->post("ID_JENIS_JABATAN"));
        $data["ID_JENIS_JABATAN"]   = $rec_jenis->ID;
        $data["JENIS_JABATAN"]      = $rec_jenis->NAMA;

        $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
        $data["ID_JABATAN_BKN"] = isset($rec_jabatan->KODE_BKN) ? $rec_jabatan->KODE_BKN : "";

        // struktur
        $rec_jabatan_struktural = $this->unitkerja_model->find_by("KODE_INTERNAL",$this->input->post("ID_UNOR")); // POST
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["ID_UNOR_BKN"]    = isset($rec_jabatan_struktural->ID) ? $rec_jabatan_struktural->ID : "";
        $data["UNOR"]           = isset($rec_jabatan_struktural->NAMA_UNOR) ? $rec_jabatan_struktural->NAMA_UNOR : "";
        // jika jabatannya struktural
        if($this->input->post("ID_JENIS_JABATAN") == "1"){
            if($this->input->post("ID_UNOR") != ""){
            $NAMA_JABATAN  = isset($rec_jabatan_struktural->NAMA_JABATAN) ? $rec_jabatan_struktural->NAMA_JABATAN : "";
            $data["NAMA_JABATAN"] = $NAMA_JABATAN;
            
            }
        }else{
            if($this->input->post("ID_JABATAN") != ""){
                $rec_jabatan = $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post("ID_JABATAN"));
                $data["NAMA_JABATAN"] = $rec_jabatan->NAMA_JABATAN;

            }
        }
        $data["ID_SATUAN_KERJA"]    = trim($this->input->post("ID_SATUAN_KERJA"));
        if(empty($data["TMT_JABATAN"])){
            unset($data["TMT_JABATAN"]);
        }
        if(empty($data["TANGGAL_SK"])){
            unset($data["SK_TANGTANGGAL_SKGAL"]);
        }
        if(empty($data["TMT_PELANTIKAN"])){
            unset($data["TMT_PELANTIKAN"]);
        }
        // khusus jft
        if($this->input->post("ID_JENIS_JABATAN")==2){
            $data["ANGKA_KREDIT"]                        = $this->input->post('ANGKA_KREDIT') ? $this->input->post('ANGKA_KREDIT') : null;
            $data["nomor_pak"]                           = $this->input->post('nomor_pak') ? $this->input->post('nomor_pak') : null;
            $data["tanggal_pak"]                         = $this->input->post('tanggal_pak') ? $this->input->post('tanggal_pak') : null;
            $data["kepakaran"]                           = $this->input->post('kepakaran') ? $this->input->post('kepakaran') : null;
        }
        $data["LAST_UPDATED"]   = date("Y-m-d");

        $id_data = $this->input->post("ID");
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
            $current = $this->jabatan_model->find($id_data);
            $perubahan = arrDiff($current,$data, true);
            if ($perubahan[1] != '[]'){
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_JABATAN";
                $data_update['DARI']        = $perubahan[0];
                $data_update['PERUBAHAN']   = $perubahan[1];
                $data_update['NAMA_KOLOM']  = "RIWAYAT_JABATAN";
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['ID_TABEL']    = $id_data;
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        else{
            $data_update = array();
            $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
            $data_update['KOLOM']       = "RIWAYAT_JABATAN";
            $data_update['PERUBAHAN']   = json_encode($data);
            $data_update['NAMA_KOLOM']  = "RIWAYAT_JABATAN";
            $data_update['LEVEL_UPDATE']= 3;
            $data_update['UPDATE_TGL']  = date("Y-m-d");
            $data_update['UPDATED_BY']  = $this->auth->user_id();
            $id_update = $this->update_mandiri_model->insert($data_update);
        }
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function savePindahUnitkerja($pegawai_data,$data){
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        if ($data['ID_UNOR']){
            if($this->riwayat_pindah_unit_kerja_model->isUniq($pegawai_data->NIP_BARU,$data['ID_UNOR'],$data['TANGGAL_SK'])){
                $NIP_BARU = $pegawai_data->NIP_BARU;
                $datapindah = array();
                $datapindah["PNS_ID"] = $pegawai_data->PNS_ID;
                $datapindah["PNS_NAMA"] = $pegawai_data->NAMA;
                $datapindah["PNS_NIP"] = $pegawai_data->NIP_BARU;
                $unor_baru_data = $this->unitkerja_model->find($data['ID_UNOR']);
                $datapindah["ID_UNOR_BARU"] = $data['ID_UNOR'];
                $datapindah["NAMA_UNOR_BARU"] = $unor_baru_data->NAMA_UNOR;
                $recordunors = $this->unitkerja_model->find($data['ID_SATUAN_KERJA']);
                $NAMA_SATKER = ISSET($recordunors->NAMA_UNOR) ? $recordunors->NAMA_UNOR : "";
        //       die($NAMA_SATKER."ini");
                $datapindah["ID_SATUAN_KERJA"] = $data['ID_SATUAN_KERJA'];
                $datapindah["NAMA_SATUAN_KERJA"] = $NAMA_SATKER;
                $datapindah["ID_SATUAN_KERJA_PANUGASAN"] = $data['ID_SATKER_PENUGASAN'];
                $datapindah["SK_TANGGAL"] = $data['TANGGAL_SK'];
                $datapindah["TMT_SK"] = $data['TMT_JABATAN'];

                if(empty($datapindah["SK_TANGGAL"])){
                    unset($datapindah["SK_TANGGAL"]);
                }
                if(empty($datapindah["TMT_SK"])){
                    unset($datapindah["TMT_SK"]);
                }
                $id = $this->riwayat_pindah_unit_kerja_model->insert($datapindah);
                if($id){
                    log_activity($this->auth->user_id(),"Tambah data Riwayat Pindah Unit dari jabatan".json_encode($datapindah), 'rwt_pindahunitkerja');
                }
            }
        } else {
            $rw = $this->riwayat_pindah_unit_kerja_model->find_by(array('PNS_NIP' => $pegawai_data->NIP_BARU,'SK_TANGGAL' => $data['TANGGAL_SK']));
            $datapindah = array();
            $datapindah["PNS_ID"] = $pegawai_data->PNS_ID;
            $datapindah["PNS_NAMA"] = $pegawai_data->NAMA;
            $datapindah["PNS_NIP"] = $pegawai_data->NIP_BARU;
            $datapindah["NAMA_UNOR_BARU"] = $data['ESELON2'];
            $datapindah["NAMA_SATUAN_KERJA"] = $data['ESELON1'];
            $datapindah["NAMA_INSTANSI"] = $data['INSTANSI'];
            $datapindah["ID_SATUAN_KERJA_PANUGASAN"] = $data['ID_SATKER_PENUGASAN'];
            $datapindah["SK_TANGGAL"] = $data['TANGGAL_SK'];
            $datapindah["TMT_SK"] = $data['TMT_JABATAN'];

            if(empty($datapindah["SK_TANGGAL"])){
                unset($datapindah["SK_TANGGAL"]);
            }
            if(empty($datapindah["TMT_SK"])){
                unset($datapindah["TMT_SK"]);
            }
            if(empty($datapindah["pengelola_sdm_kawasan_id"])){
                unset($datapindah["pengelola_sdm_kawasan_id"]);
            }
            if($rw){
                $id = $this->riwayat_pindah_unit_kerja_model->update($rw->ID,$datapindah);
                log_activity($this->auth->user_id(),"Update data Riwayat Pindah Unit dari jabatan".json_encode($datapindah), 'rwt_pindahunitkerja');
            } else {
                $id = $this->riwayat_pindah_unit_kerja_model->insert($datapindah);
                log_activity($this->auth->user_id(),"Tambah data Riwayat Pindah Unit dari jabatan".json_encode($datapindah), 'rwt_pindahunitkerja');
            }
        }
        
        
    }
    private function setPengelolaSDMKawasan($nip)
    {
        $pegawai = $this->pegawai_model->find_by('NIP_BARU',$nip);
        if ($pegawai->kawasan_id && $pegawai->UNOR_INDUK_ID){
            $this->load->model('masters/pengelola_sdm_kawasan_detail_model');
            $pengelola = $this->pengelola_sdm_kawasan_detail_model->find_by(array('kawasan_id' => $pegawai->kawasan_id, 'unit_kerja_id' => $pegawai->UNOR_INDUK_ID));
            if ($pengelola){
                $this->pegawai_model->update($pegawai->id,array('pengelola_sdm_kawasan_id' => $pengelola->pengelola_sdm_kawasan_id));
                log_activity($this->auth->user_id(),"Update data Pengelola Kawasan SDM NIP :".$nip.' Pengelola SDM kawasan '.$pengelola->pengelola_sdm_kawasan_id, 'rwt_pindahunitkerja');
            }
        }
    }
    private function set_unor_active($nip){
        $this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
        $unor_aktif = $this->riwayat_pindah_unit_kerja_model->limit(1)->find_aktif($nip);
        if($unor_aktif[0]->ID_UNOR_BARU != ""){
            $unor_aktif = $unor_aktif[0];
            // update unor dan satker jika ada isinya
            if($unor_aktif->ID_UNOR_BARU != ""){
                $dataupdate['UNOR_ID']                      = $unor_aktif->ID_UNOR_BARU ? $unor_aktif->ID_UNOR_BARU : null;  
            }
            if($unor_aktif->ID_SATUAN_KERJA != ""){
                $dataupdate['UNOR_INDUK_ID']                = $unor_aktif->ID_SATUAN_KERJA ? $unor_aktif->ID_SATUAN_KERJA : null;    
            }
            $dataupdate['satker_penempatan_id']         = $unor_aktif->ID_SATUAN_KERJA_PANUGASAN;
            if(empty($dataupdate["satker_penempatan_id"])){
                unset($dataupdate["satker_penempatan_id"]);
            }
            $this->pegawai_model->skip_validation(true);
            $this->pegawai_model->update_where("NIP_BARU",$nip, $dataupdate);
            log_activity($this->auth->user_id(),"Update unor pegawai aktif dari jabatan".json_encode($dataupdate), 'rwt_pindahunitkerja');
        }
    }
    public function delete($record_id){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->auth->restrict($this->permissionDelete);
        $riwayat_jabatan = $this->riwayat_jabatan_model->find($record_id);
		if ($this->riwayat_jabatan_model->delete($record_id)) {
            $this->set_jabatan_aktif($riwayat_jabatan->PNS_NIP);
			 log_activity($this->auth->user_id(), 'delete data Riwayat Jabatan : ' . $record_id . ' : '.json_encode($riwayat_jabatan) .' '. $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_jabatan");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_jabatan_model->find($id);
        $this->load->model('arsip_digital/arsip_digital_model');
        $arsip = $this->arsip_digital_model->find($datadetil->id_arsip);
        if ($arsip){
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $aws->viewdoc($arsip->location.$arsip->name);
        }
        die();
    }
    public function get_riwayat_sync()
    {
        $nip = $this->input->post('NIP_BARU');
        $data = array();
        if (isset($nip)) {
            $this->riwayat_jabatan_model->where("PNS_NIP", $nip);
            $this->riwayat_jabatan_model->where("rwt_jabatan.ID_BKN is not null");
            $records = $this->riwayat_jabatan_model->find_all();
            foreach ($records as $record) {
                $data[] = array(
                    'id' => $record->ID_BKN,
                    'text' => $record->NAMA_JABATAN." TMT ".$record->TMT_JABATAN
                );
            }
        }
        $o = array(
            "results" => $data,
            );
        echo json_encode($o);
    }
}
