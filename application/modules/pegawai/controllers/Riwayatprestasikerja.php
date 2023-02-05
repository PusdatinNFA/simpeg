<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatprestasikerja extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatPrestasiKerja.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatPrestasiKerja.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatPrestasiKerja.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatPrestasiKerja.Kepegawaian.View';
    protected $permissionUpload   = 'RiwayatPrestasiKerja.Kepegawaian.Upload';
    protected $permissionUpdateMandiri   = 'RiwayatPrestasiKerja.Kepegawaian.UpdateMandiri';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_prestasi_kerja_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');	
    }
    public function get_atasan_langsung_ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $this->db->start_cache();
            $this->db->like('lower(pegawai."NAMA")', strtolower($q));
            $this->db->from("hris.pegawai");
            $this->db->join("hris.golongan g",'g.ID = pegawai.GOL_ID','LEFT');
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('PNS_ID as id,pegawai.NAMA as text,pegawai.NAMA,NIP_BARU,JABATAN_NAMA, concat(g."NAMA_PANGKAT",\' - \',g."NAMA") as "GOLONGAN"');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                    "totalx"=>$total,
                    "startx"=>$start,
                    "limitx"=>$limit
                )
            );   
            $this->db->flush_cache();
            $json = $o;
        }
            echo json_encode($json);
    }
    
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        if(empty($PNS_ID)){
            ECHO "die";
            die();
        }
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_prestasi_kerja_model->where("PNS_NIP",$pegawai_data->NIP_BARU);
		$total= $this->riwayat_prestasi_kerja_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		if($search!=""){
			$this->riwayat_prestasi_kerja_model->where('upper("NAMA_UNOR_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_prestasi_kerja_model->limit($length,$start);
        
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->riwayat_prestasi_kerja_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_prestasi_kerja_model->order_by("TAHUN","ASC");
        $this->riwayat_prestasi_kerja_model->where("PNS_NIP",$pegawai_data->NIP_BARU);  
		
        $records=$this->riwayat_prestasi_kerja_model->find_all();
		if($search != "")
		{
			$jum	= $this->riwayat_prestasi_kerja_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->TAHUN;
                $row []  = round($record->NILAI_PPK,2);
                $row []  = round($record->NILAI_SKP,2);
                $row []  = round($record->NILAI_PERILAKU,2);
                $row []  = $record->JABATAN_NAMA;

                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if ($record->TAHUN < 2021){
                    if($this->auth->has_permission($this->permissionUpdateMandiri))
                    {    
                        $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatprestasikerja/editmandiri/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                    }
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatprestasikerja/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus'><i class='fa fa-trash-o'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUpload))
                { 
                    $btn_actions  [] = "<a href='#' kode='$record->TAHUN' data-toggle='tooltip' title='Sync Data' class='btn btn-sm btn-warning btn-upload'><i class='fa fa-upload'></i> </a>";
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
        Template::set('jenis_jabatans',$jenis_jabatans );  
        Template::set('NILAI_PROSENTASE_SKP',60);
        Template::set('NILAI_PROSENTASE_PERILAKU',40);        
        Template::set('mandiri',$mandiri);
        if(empty($record_id)){
            if (!$mandiri)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Pindah Unit Kerja");

            Template::render();
        }
        else {
            if (!$mandiri)
                $this->auth->restrict($this->permissionEdit);           
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            $detailRiwayat = $this->riwayat_prestasi_kerja_model->find($record_id);
            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanLangsung',$selectedAtasanLangsung );
            
            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanAtasanLangsung',$selectedAtasanAtasanLangsung );
            
            

            Template::set('detail_riwayat',$detailRiwayat );    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Pindah Unit Kerja");

            Template::render();
        }
    }
    public function verifikasi($ID,$read_only = false){
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
        Template::set('jenis_jabatans',$jenis_jabatans );  
        Template::set('NILAI_PROSENTASE_SKP',60);
        Template::set('NILAI_PROSENTASE_PERILAKU',40);  
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);   
        Template::set('id_update_mandiri',$ID);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            $this->pegawai_model->where("PNS_ID",$perubahan->ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanLangsung',$selectedAtasanLangsung );
            
            $this->pegawai_model->where("PNS_ID",$perubahan->ATASAN_ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanAtasanLangsung',$selectedAtasanAtasanLangsung );
            Template::set('detail_riwayat',$perubahan ); 
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Prestasi Kerja");

            Template::render();
        }
        else {
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
           
            Template::set_view("kepegawaian/riwayat_prestasi_kerja_crud");
            $detailRiwayat = $this->riwayat_prestasi_kerja_model->find($data->ID_TABEL);
            if (!$detailRiwayat)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $detailRiwayat = array_replace((array) $detailRiwayat,(array) $perubahan);
            $detailRiwayat = (object) $detailRiwayat;

            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanLangsung',$selectedAtasanLangsung );
            
            $this->pegawai_model->where("PNS_ID",$detailRiwayat->ATASAN_ATASAN_LANGSUNG_PNS_ID);
            $selectedAtasanAtasanLangsung = $this->pegawai_model->find_first_row();
            Template::set('selectedAtasanAtasanLangsung',$selectedAtasanAtasanLangsung );
            
            Template::set('detail_riwayat',$detailRiwayat );    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Prestasi Kerja");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function addmandiri($PNS_ID = ""){
        $this->show($PNS_ID,'',1);
    }
    public function editmandiri($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id,1);
    }
    function maximumCheck($num)
    {
        if ($num > 100)
        {
            $this->form_validation->set_message(
                            'your_number_field',
                            'The %s field must be less than 24'
                        );
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_prestasi_kerja_model->get_validation_rules());
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
        
        $data = $this->riwayat_prestasi_kerja_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        $max_nilai = $this->input->post("max_nilai")?$this->input->post("max_nilai"):100;

        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row();
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
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            $keterangan = 'Tahun '.$data['TAHUN'];
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai_data->NIP_BARU,$file_name,85,$keterangan, $content,$file_name,!$mandiri);
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
        $data["PNS_ID"] = $pegawai_data->PNS_ID;
        $data["PNS_NAMA"] = $pegawai_data->NAMA;
        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
        
        
        $jenis_jabatan_data = $this->jenis_jabatan_model->find($this->input->post("JABATAN_TIPE"));
        $data["JABATAN_TIPE_TEXT"] = $jenis_jabatan_data->NAMA;

       
        if(empty($data["SK_TANGGAL"])){
            unset($data["SK_TANGGAL"]);
        }

       
        $data['NILAI_PROSENTASE_SKP'] = $data['NILAI_PROSENTASE_SKP']?$data['NILAI_PROSENTASE_SKP']:60;
        $data['NILAI_SKP_AKHIR'] = $data['NILAI_SKP_AKHIR']?$data['NILAI_SKP_AKHIR']:$data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

        $data['NILAI_PROSENTASE_PERILAKU'] = $data['NILAI_PROSENTASE_PERILAKU']?$data['NILAI_PROSENTASE_PERILAKU']:40;
        if (empty($data['NILAI_PERILAKU'])){ 
            $data['NILAI_PERILAKU'] = $data['PERILAKU_KOMITMEN'] 
                                    +$data['PERILAKU_INTEGRITAS'] 
                                    +$data['PERILAKU_DISIPLIN'] 
                                    +$data['PERILAKU_KERJASAMA'] 
                                    +$data['PERILAKU_ORIENTASI_PELAYANAN'] ;
            if($data['JABATAN_TIPE']==1){
                $data['NILAI_PERILAKU'] += $data['PERILAKU_KEPEMIMPINAN'] ;
                $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/6;
            }
            else {
                $data['NILAI_PERILAKU'] = $data['NILAI_PERILAKU']/5;
            }
        }
        $data['NILAI_PERILAKU_AKHIR'] = $data['NILAI_PERILAKU_AKHIR']?$data['NILAI_PERILAKU_AKHIR']:
                                        $data['NILAI_PROSENTASE_PERILAKU']/100*$data['NILAI_PERILAKU'];

        $data['NILAI_PPK'] = $data['NILAI_PPK']?$data['NILAI_PPK']:
                            round($data['NILAI_SKP_AKHIR']  + $data['NILAI_PERILAKU_AKHIR'],2);

        $id_data = $this->input->post("ID");
        if (!$mandiri){
            if(isset($id_data) && !empty($id_data)){
                $current = $this->riwayat_prestasi_kerja_model->find($id_data);
                $perubahan = arrDiff($current,$data);
                $this->riwayat_prestasi_kerja_model->update($id_data,$data);
                log_activity($this->auth->user_id(), 'Update data Riwayat prestasi kerja: ' . $pegawai_data->NIP_BARU . ' perubahan : '.json_encode($perubahan). ' ' . $this->input->ip_address(), 'pegawai');  
            }
            else {
                $id = $this->riwayat_prestasi_kerja_model->insert($data);
                log_activity($this->auth->user_id(), 'Insert data Riwayat prestasi kerja: ' . $pegawai_data->NIP_BARU . ' data : '.json_encode($data). ' '  . $this->input->ip_address(), 'pegawai');  
            }
        } else {
            if(isset($id_data) && !empty($id_data)){
                $current = $this->riwayat_prestasi_kerja_model->find($id_data);
                $perubahan = arrDiff($current,$data, true);
                if ($perubahan[1] != '[]'){
                    $data_update = array();
                    $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                    $data_update['KOLOM']       = "RIWAYAT_PPK";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_PPK";
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
                $data_update['KOLOM']       = "RIWAYAT_PPK";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_PPK";
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
		if ($this->riwayat_prestasi_kerja_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Prestasi Kerja : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_prestasi_kerja");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $this->load->model('arsip_digital/arsip_digital_model');
        $arsip = $this->arsip_digital_model->find($id);
        if ($arsip){
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $aws->viewdoc($arsip->location.$arsip->name);
        }
        die();
    }
}
