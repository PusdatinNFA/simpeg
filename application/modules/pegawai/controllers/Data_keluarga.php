<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Data_keluarga extends Admin_Controller
{
    protected $permissionCreate = 'Data_Keluarga.Kepegawaian.Create';
    protected $permissionDelete = 'Data_Keluarga.Kepegawaian.Delete';
    protected $permissionEdit   = 'Data_Keluarga.Kepegawaian.Edit';
    protected $permissionView   = 'Data_Keluarga.Kepegawaian.View';
    protected $permissionCreateMandiri = 'Data_Keluarga.Kepegawaian.CreateMandiri';
    protected $permissionEditMandiri   = 'Data_Keluarga.Kepegawaian.EditMandiri';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/orang_tua_model');
        $this->load->model('pegawai/istri_model');
        $this->load->model('pegawai/anak_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->model('pegawai/jenis_dokumen_model');
        $this->load->helper('aplikasi');

        $dokumens = $this->load->jenis_dokumen_model->find_all();
		Template::set('JENIS_DOKUMEN_IDs',$dokumens);
    }
    public function verifikasi_pasangan($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $data_perubahan = json_decode($data->PERUBAHAN);
        Template::set('id_update_mandiri',$data->ID);
        if (!$read_only)
        $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/istri_crud");
            Template::set('detail_riwayat', $data_perubahan);
            Template::set('PNS_ID', $data->PNS_ID);
            Template::set('toolbar_title', "Tambah Data Pasangan");
            Template::render();
        }
        else {
            $datadetil = $this->istri_model->find($data->ID_TABEL); 
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/istri_crud");
            if (!$datadetil)
                    Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $datadetil = array_replace((array)$datadetil,(array)$data_perubahan);
            Template::set('detail_riwayat', (object) $datadetil);    
            Template::set('PNS_ID', $data->PNS_ID);
            Template::set('toolbar_title', "Ubah Data Pasangan");
            Template::render();
        }
    }
    public function verifikasi_ortu($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $data_perubahan = json_decode($data->PERUBAHAN);
        $this->load->model('pegawai/agama_model');
        $agamas = $this->agama_model->find_all();
        if (!$read_only){
            $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        }
        Template::set('read_only',$read_only);
        Template::set('agamas', $agamas);
        Template::set('id_update_mandiri',$data->ID);

        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/ortu_crud");
            Template::set('detail_riwayat', $data_perubahan);  
            Template::set('PNS_ID', $data->PNS_ID);
            Template::set('toolbar_title', "Tambah Data orang tua");
            Template::render();
        }
        else {
    		$datadetil = $this->orang_tua_model->find($data->ID_TABEL); 
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/ortu_crud");
            if (!$datadetil)
            Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $datadetil = array_replace((array)$datadetil, (array) $data_perubahan);
            Template::set('detail_riwayat', (object) $datadetil);    
            Template::set('PNS_ID', $data->PNS_ID);
            Template::set('toolbar_title', "Ubah Data Orang tua");
            Template::render();
        }
    }
    public function verifikasi_anak($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $data_perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    
        if (!$read_only){
        $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        }
        Template::set('read_only',$read_only);
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set('pegawai_data', $pegawai_data);
        Template::set('id_update_mandiri',$data->ID);   
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        } 
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/anak_crud");
            Template::set('detail_riwayat', $data_perubahan);   
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Anak");
            Template::render();
        }
        else {
            $datadetil = $this->anak_model->find($data->ID_TABEL);           
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/anak_crud");
            if (!$datadetil->id)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            
            $datadetil = array_replace((array) $datadetil, (array) $data_perubahan);
            Template::set('detail_riwayat', (object) $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Anak");
            Template::render();
        }
    }
    public function ajax_list_ortu(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$total= $this->orang_tua_model->count_all($pegawai_data->NIP_BARU);;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		 
		$this->orang_tua_model->limit($length,$start);
        $records=$this->orang_tua_model->find_all($pegawai_data->NIP_BARU);
            
		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		 
		// $this->load->helper('aplikasi');
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $hubungan = $record->HUBUNGAN == "1" ? "AYAH" : "IBU";
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
                $row []  = $hubungan.$msgverifikasi;
                $row []  = $record->GELAR_DEPAN." ".$record->NAMA." ".$record->GELAR_BELAKANG;
                $row []  = $convert->fmtDate($record->TANGGAL_LAHIR,"dd-mm-yyyy");
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEditMandiri)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addortumandiri/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data' tooltip='Edit data mandiri' class='btn btn-sm btn-info modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addortu/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data' tooltip='Edit data mandiri' class='btn btn-sm btn-warning modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapusortu'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function ajax_list_anak(){
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $total= $this->anak_model->count_all($pegawai_data->NIP_BARU);;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
         
        $this->anak_model->limit($length,$start);
        $records=$this->anak_model->find_all($pegawai_data->NIP_BARU);
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
         
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $jk = $record->JENIS_KELAMIN == "M" ? "LAK-LAKI" : "PEREMPUAN";
                $msgverifikasi = "";
                $status = "KANDUNG";
                if ($record->STATUS_ANAK == "2")
                    $status = "ANGKAT";
                else if ($record->STATUS_ANAK == "3")
                    $status = "TIRI";  

                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA.$msgverifikasi;
                $row []  = $jk;
                $row []  = $convert->fmtDate($record->TANGGAL_LAHIR,"dd-mm-yyyy");
                $row []  = $record->TEMPAT_LAHIR;
                $row []  = $status;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionView)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/detilanak/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Lihat data' tooltip='Lihat data' class='btn btn-sm btn-success show-modal'><i class='fa fa-eye'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEditMandiri)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addanakmandiri/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data mandiri' tooltip='Edit data mandiri' class='btn btn-sm btn-info modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addanak/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data' tooltip='Edit data' class='btn btn-sm btn-warning modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-anak'><i class='fa fa-trash-o'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function ajax_list_istri(){
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $total= $this->istri_model->count_all($pegawai_data->NIP_BARU);;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $this->istri_model->limit($length,$start);
        $records=$this->istri_model->find_all($pegawai_data->NIP_BARU);
        
        if($search != "")
        {
            $jum    = $this->istri_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $hubungan = $record->HUBUNGAN == "1" ? "ISTRI" : "SUAMI";
                $STATUS = $record->STATUS == "1" ? "MENIKAH" : ($record->STATUS == "2"? "CERAI":"JANDA/DUDA");
                $PNS = $record->PNS == "1" ? "YA" : "BUKAN";
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
                $row []  = $record->NAMA.$msgverifikasi;
                $row []  = $STATUS;
                $row []  = $PNS;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEditMandiri)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addistrimandiri/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data mandiri' tooltip='Edit data mandiri' class='btn btn-sm btn-info modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit)){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/data_keluarga/addistri/".$record->PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Edit data' tooltip='Edit data' class='btn btn-sm btn-warning modal-custom-global'><i class='fa fa-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-istri'><i class='fa fa-trash-o'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function showortu($PNS_ID,$record_id=''){
        $this->load->model('pegawai/agama_model');
        $agamas = $this->agama_model->find_all();
        Template::set('agamas', $agamas);
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/ortu_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data orang tua");
            Template::render();
        }
        else {
    		$datadetil = $this->orang_tua_model->find($record_id); 
            
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/ortu_crud");
            if (!$datadetil)
            Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
        
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Orang tua");
            Template::render();
        }
    }
    public function showortumandiri($PNS_ID,$record_id=''){
        $this->load->model('pegawai/agama_model');
        $agamas = $this->agama_model->find_all();
        Template::set('agamas', $agamas);
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/ortu_crud_mandiri");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data orang tua");
            Template::render();
        }
        else {
    		$datadetil = $this->orang_tua_model->find($record_id); 
            
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/ortu_crud_mandiri");
            if (!$datadetil)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Orang tua");

            Template::render();
            
        }
    }
    public function showistri($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/istri_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Pasangan");
            Template::render();
        }
        else {
            $datadetil = $this->istri_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/istri_crud");
            if (!$datadetil)
                    Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Pasangan");
            Template::render();
        }
    }
    public function showistrimandiri($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/istri_crud_mandiri");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Pasangan");
            Template::render();
        }
        else {
            $datadetil = $this->istri_model->find($record_id); 
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/istri_crud_mandiri");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Pasangan");

            Template::render();
        }
    }
    public function showanak($PNS_ID,$record_id=''){
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set('pegawai_data', $pegawai_data);    
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("data_keluarga/anak_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Anak");
            Template::render();
        }
        else {
            $datadetil = $this->anak_model->find($record_id);           
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("data_keluarga/anak_crud");
            if (!$datadetil->id)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Anak");
            Template::render();
        }
    }
    public function showanakmandiri($PNS_ID,$record_id=''){
        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set('pegawai_data', $pegawai_data);    
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/anak_crud_mandiri");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Data Anak");
            Template::render();
        }
        else {
            $datadetil = $this->anak_model->find($record_id); 
            $this->auth->restrict($this->permissionCreateMandiri);
            Template::set_view("data_keluarga/anak_crud_mandiri");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Data Anak");

            Template::render();
        }
    }
    public function list_satker(){
        $this->db->order_by('NAMA_UNOR',"asc");
        $satkers = $this->unitkerja_model->find_satker();
        $data = array();
        foreach($satkers as $satker){
            //echo json_encode($satker);
            $data[] = array(
                'id'=>$satker->ID,
                'text'=>$satker->NAMA_UNOR,
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
    public function addortu($PNS_ID,$ID = ""){
        $this->showortu($PNS_ID,$ID);
    }
    public function addortumandiri($PNS_ID,$ID = ""){
        $this->showortumandiri($PNS_ID,$ID);
    }
    public function addistri($PNS_ID,$ID = ""){
        $this->showistri($PNS_ID,$ID);
    }
    public function detilistri($ID){
        $datadetil = $this->istri_model->find($ID); 
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("data_keluarga/istri_crud");
       
        Template::set('detail_riwayat', $datadetil);    
        Template::set('ID', $ID);
        Template::set('view', false);
        Template::set('toolbar_title', "View Data Pasangan");

        Template::render();
    }
    public function addistrimandiri($PNS_ID,$ID = ""){
        $this->showistrimandiri($PNS_ID,$ID);
    }
    
    public function addanak($PNS_ID,$ID = ""){
        $this->showanak($PNS_ID,$ID);
    }
    public function addanakmandiri($PNS_ID,$ID = ""){
        $this->showanakmandiri($PNS_ID,$ID);
    }
    public function detilanak($PNS_ID,$ID){

        $this->istri_model->where("PNS_ID",$PNS_ID);
        $data_pasangans = $this->istri_model->find_all();
        Template::set('data_pasangans', $data_pasangans);    
        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        Template::set('pegawai_data', $pegawai_data);   
        
        $datadetil = $this->anak_model->find($ID); 
        $this->auth->restrict($this->permissionView);
        Template::set_view("data_keluarga/anak_crud");
       
        Template::set('detail_riwayat', $datadetil);    
        Template::set('PNS_ID', $PNS_ID);
        Template::set('toolbar_title', "View Data Anak");
        Template::set('view', false);
        Template::render();
    }
    public function edit($ref_id=''){
        $this->riwayat_kgb_model->where("ref",$ref_id);
        $kgb_data = $this->riwayat_kgb_model->first_row();  
        $this->pegawai_model->where("ID",$kgb_data->pegawai_id);
        $pegawai_data = $this->pegawai_model->find_first_row();  

        $list_golongan = $this->golongan_model->find_all();
       
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        Template::set_view("kepegawaian/riwayat_kgb_crud");
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('list_golongan',$list_golongan);
        Template::set('toolbar_title', "Ubah Riwayat KGB");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
    }
    public function detil($ref_id=''){
        $list_golongan = $this->golongan_model->find_all();
        $this->riwayat_kgb_model->where("ref",$ref_id);
        $kgb_data = $this->riwayat_kgb_model->first_row();  
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        $this->pegawai_model->where("ID",$kgb_data->pegawai_id);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("kepegawaian/riwayat_kgb_detil");
        Template::set('list_golongan',$list_golongan);
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('toolbar_title', "Riwayat Pekerjaan");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
		Template::render();
    }
    public function contentorangtua(){
        $PNS_ID = $this->input->post('PNS_ID');
        $output = "";
        // AYAH
        $this->orang_tua_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $this->orang_tua_model->where("HUBUNGAN",1);
        $orangtua_data = $this->orang_tua_model->find_all();
        // IBU
        $this->orang_tua_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $this->orang_tua_model->where("HUBUNGAN",2);
        $dataibus = $this->orang_tua_model->find_all();
        // ISTRI
        $this->istri_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $dataistri = $this->istri_model->find_all();

        // anak
        $this->anak_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $dataanak = $this->anak_model->find_all();

        $output .= $this->load->view('data_keluarga/contentorangtua',array('orangtua_data'=>$orangtua_data,'dataibus'=>$dataibus,'dataistri'=>$dataistri,'dataanak'=>$dataanak),true);   
         
        echo $output;
        die();
    }
    public function saveortu(){
         // Validate the data
        $this->form_validation->set_rules($this->orang_tua_model->get_validation_rules());
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
		
		
        $data = $this->orang_tua_model->prep_data($this->input->post());
       	$this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
        
        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;

        
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
            $current = $this->orang_tua_model->find($id_data);
            $perubahan = arrDiff($current,$data);
            $this->orang_tua_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Update Data Orang Tua : ' .$pegawai_data->NIP_BARU  . ' perubahan '. json_encode($perubahan).' ' . $this->input->ip_address(), 'pegawai');
        }
        else {
            $this->orang_tua_model->insert($data);
            log_activity($this->auth->user_id(), 'Insert Data Orang Tua : ' .$pegawai_data->NIP_BARU  . ' data '.json_encode($data).' ' . $this->input->ip_address(), 'pegawai');
        }
        $id_update_mandiri = $this->input->post("id_update_mandiri");
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
    public function saveortumandiri(){
        // Validate the data
       $this->form_validation->set_rules($this->orang_tua_model->get_validation_rules());
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
       
       
       $data = $this->orang_tua_model->prep_data($this->input->post());
          $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
       $pegawai_data = $this->pegawai_model->find_first_row(); 
       
       $data["NIP"] = $pegawai_data->NIP_BARU;
       
       $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
       $data['TGL_MENINGGAL']  = $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
    //    $data['STATUS_SATKER'] = 0;
    //    $data['STATUS_BIRO'] = 0;
       
       $id_data = $this->input->post("id_data");
       if (isset($id_data) && !empty($id_data)) {
        $current = $this->orang_tua_model->find($id_data);
        $perubahan = arrDiff($current,$data, true);
        if ($perubahan[1] != '[]'){
            $data_update = array();
            $data_update['PNS_ID']      = $this->input->post("PNS_ID");
            $data_update['KOLOM']       = "DATA_ORANG_TUA";
            $data_update['DARI']        = $perubahan[0];
            $data_update['PERUBAHAN']   = $perubahan[1];
            $data_update['NAMA_KOLOM']  = "DATA_ORANG_TUA";
            $data_update['LEVEL_UPDATE'] = 3;
            $data_update['ID_ARSIP']    = $data['id_arsip'];
            $data_update['UPDATE_TGL']  = date("Y-m-d");
            $data_update['ID_TABEL']    = $id_data;
            $data_update['UPDATED_BY']  = $this->auth->user_id();
            $id_update = $this->update_mandiri_model->insert($data_update);
            log_activity($this->auth->user_id(), 'Update : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
        }
    } else {
            $data_update = array();
            $data_update['PNS_ID']      = $this->input->post("PNS_ID");
            $data_update['KOLOM']       = 'DATA_ORANG_TUA';
            $data_update['DARI']        = null;
            $data_update['PERUBAHAN']   = json_encode($data);
            $data_update['NAMA_KOLOM']  = "DATA_ORANG_TUA";
            $data_update['LEVEL_UPDATE'] = 3;
            $data_update['ID_ARSIP']    = $data['id_arsip'];
            $data_update['UPDATE_TGL']  = date("Y-m-d");
            $data_update['UPDATED_BY']  = $this->auth->user_id();
            $id_update = $this->update_mandiri_model->insert($data_update);
            log_activity($this->auth->user_id(), 'Insert : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
    
    }
       $response ['success']= true;
       $response ['msg']= "Berhasil";
       echo json_encode($response);    

   }
    public function saveistri(){
         // Validate the data
        $this->form_validation->set_rules($this->istri_model->get_validation_rules());
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
        $data = $this->istri_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
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

            if ($data['STATUS'] == 1){
                $keterangan = "Tanggal Menikah ".getIndonesiaFormat($data['TANGGAL_MENIKAH']);
                $jenis_arsip = 15;
                $nomor_dokumen = $data['AKTE_NIKAH'];
            }
            else if ($data['STATUS'] == 3){
                $keterangan = "Tanggal Meninggal ".getIndonesiaFormat($data['TANGGAL_MENINGGAL']);
                $jenis_arsip = 146;
                $nomor_dokumen = $data['AKTE_MENINGGAL'];
            }
            else if ($data['STATUS'] == 2){
                $keterangan = "Tanggal Cerai ".getIndonesiaFormat($data['TANGGAL_CERAI']);
                $jenis_arsip = 52;
                $nomor_dokumen = $data['AKTE_CERAI'];
            }
            
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai_data->NIP_BARU,$nomor_dokumen,$jenis_arsip,$keterangan, $content,$file_name);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
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
        }
        $jk = trim($pegawai_data->JENIS_KELAMIN);
        if($jk == "M")
            $data["HUBUNGAN"] = "1";    
        else
            $data["HUBUNGAN"] = "2";    

        $data["NIP"] = $pegawai_data->NIP_BARU;
        
        $data['TANGGAL_MENINGGAL']  = $this->input->post('TANGGAL_MENINGGAL') ? $this->input->post('TANGGAL_MENINGGAL') : null;
        $data['TANGGAL_MENIKAH']  = $this->input->post('TANGGAL_MENIKAH') ? $this->input->post('TANGGAL_MENIKAH') : null;
        $data['TANGGAL_CERAI']  = $this->input->post('TANGGAL_CERAI') ? $this->input->post('TANGGAL_CERAI') : null;
        
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
            $current = $this->istri_model->find($id_data);
            $perubahan = arrDiff($current,$data);
            $this->istri_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Update Data Pasangan : ' .$pegawai_data->NIP_BARU  . ' perubahan '. json_encode($perubahan).' ' . $this->input->ip_address(), 'pegawai');
        }
        else {
            $this->istri_model->insert($data);
            log_activity($this->auth->user_id(), 'Input Data Pasangan : ' . $pegawai_data->NIP_BARU . ' data '. json_encode($data).' ' . $this->input->ip_address(), 'pegawai');
        }
        $id_update_mandiri = $this->input->post("id_update_mandiri");
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

        $this->pegawai_model->update($pegawai_data->ID,array('JENIS_KAWIN_ID' => strval($data['STATUS'])));
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function saveistrimandiri(){
        // Validate the data
       $this->form_validation->set_rules($this->istri_model->get_validation_rules());
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
       $data = $this->istri_model->prep_data($this->input->post());
       $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
       $pegawai_data = $this->pegawai_model->find_first_row(); 
       $jk = trim($pegawai_data->JENIS_KELAMIN);
       if($jk == "M")
           $data["HUBUNGAN"] = "1";    
       else
           $data["HUBUNGAN"] = "2";    

       $data["NIP"] = $pegawai_data->NIP_BARU;
       
       $data['TANGGAL_MENINGGAL']  = $this->input->post('TANGGAL_MENINGGAL') ? $this->input->post('TANGGAL_MENINGGAL') : null;
       $data['TANGGAL_MENIKAH']  = $this->input->post('TANGGAL_MENIKAH') ? $this->input->post('TANGGAL_MENIKAH') : null;
       $data['TANGGAL_CERAI']  = $this->input->post('TANGGAL_CERAI') ? $this->input->post('TANGGAL_CERAI') : null;
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

            if ($data['STATUS'] == 1){
                $keterangan = "Tanggal Menikah ".getIndonesiaFormat($data['TANGGAL_MENIKAH']);
                $jenis_arsip = 15;
                $nomor_dokumen = $data['AKTE_NIKAH'];
            }
            else if ($data['STATUS'] == 3){
                $keterangan = "Tanggal Meninggal ".getIndonesiaFormat($data['TANGGAL_MENINGGAL']);
                $jenis_arsip = 146;
                $nomor_dokumen = $data['AKTE_MENINGGAL'];
            }
            else if ($data['STATUS'] == 2){
                $keterangan = "Tanggal Cerai ".getIndonesiaFormat($data['TANGGAL_CERAI']);
                $jenis_arsip = 52;
                $nomor_dokumen = $data['AKTE_CERAI'];
            }
            
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai_data->NIP_BARU,$nomor_dokumen,$jenis_arsip,$keterangan, $content,$file_name,0);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
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
                $response ['msg']= "Gagal, harap lampirkan berkas";
                echo json_encode($response);
                die;
            }
        }
        // $data['STATUS_SATKER'] = 0;
        // $data['STATUS_BIRO'] = 0;
        $id_data = $this->input->post("id_data");
        if (isset($id_data) && !empty($id_data)) {
            $current = $this->istri_model->find($id_data);
            $perubahan = arrDiff($current,$data, true);
            if ($perubahan[1] != '[]'){
                $data_update = array();
                $data_update['PNS_ID']      = $this->input->post("PNS_ID");
                $data_update['KOLOM']       = "DATA_PASANGAN";
                $data_update['DARI']        = $perubahan[0];
                $data_update['PERUBAHAN']   = $perubahan[1];
                $data_update['NAMA_KOLOM']  = "DATA_PASANGAN";
                $data_update['LEVEL_UPDATE'] = 3;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['ID_TABEL']    = $id_data;
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
                log_activity($this->auth->user_id(), 'Update : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
            }
        } else {
                $data_update = array();
                $data_update['PNS_ID']      = $this->input->post("PNS_ID");
                $data_update['KOLOM']       = "DATA_PASANGAN";
                $data_update['DARI']        = null;
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "DATA_PASANGAN";
                $data_update['LEVEL_UPDATE'] = 3;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
                log_activity($this->auth->user_id(), 'Insert : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
        }
       $this->updateStatusKawin($pegawai_data,$data['STATUS']);
       $response ['success']= true;
       $response ['msg']= "Berhasil";
       echo json_encode($response);    

   }
    private function updateStatusKawin($pegawai, $status)
    {
        if ($pegawai && $status){
            $update = array('JENIS_KAWIN_ID' => $status);
            $perubahan = arrDiff($pegawai,$update, true);
            // $this->pegawai_model->update($pegawai->ID,array('JENIS_KAWIN_ID' => strval($status)));
            if ($perubahan[1] != '[]'){
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai->PNS_ID;
                $data_update['KOLOM']       = "DATA_PERSONAL";
                $data_update['DARI']        = $perubahan[0];
                $data_update['PERUBAHAN']   = $perubahan[1];
                $data_update['NAMA_KOLOM']  = "DATA_PERSONAL";
                $data_update['LEVEL_UPDATE'] = 3;
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
                log_activity($this->auth->user_id(), 'Update : ' . $perubahan[1] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
            }
        }
    }
    public function saveanak(){
         // Validate the data)
        $this->form_validation->set_rules($this->anak_model->get_validation_rules());
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
        $data = $this->anak_model->prep_data($this->input->post());
        $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $pegawai_data = $this->pegawai_model->find_first_row(); 
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

            if (!empty($data['TANGGAL_LAHIR'])){
                $keterangan = "Tanggal Lahir ".getIndonesiaFormat($data['TANGGAL_LAHIR']);
            } else $keterangan = "Akta kelahiran" ;
            $jenis_arsip = 16;
            $nomor_dokumen = $data['AKTE_KELAHIRAN']?$data['AKTE_KELAHIRAN']:$data['NAMA'];
            
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai_data->NIP_BARU,$nomor_dokumen,$jenis_arsip,$keterangan, $content,$file_name);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
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
        }
        $data["NIP"] = $pegawai_data->NIP_BARU;
        $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
        

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
            $current = $this->anak_model->find($id_data);
            $perubahan = arrDiff($current,$data);
            $this->anak_model->update($id_data,$data);
            log_activity($this->auth->user_id(), 'Update Data Anak : ' .$pegawai_data->NIP_BARU  . ' perubahan '. json_encode($perubahan).' ' . $this->input->ip_address(), 'pegawai');
        }
        else {
            $this->anak_model->insert($data);
            log_activity($this->auth->user_id(), 'Insert Data Anak : ' .$pegawai_data->NIP_BARU  . ' data '.json_encode($data).' ' . $this->input->ip_address(), 'pegawai');
        }

        $id_update_mandiri = $this->input->post("id_update_mandiri");
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
    public function saveanakmandiri(){
        // Validate the data
       $this->form_validation->set_rules($this->anak_model->get_validation_rules());
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
       $data = $this->anak_model->prep_data($this->input->post());
       $this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
       $pegawai_data = $this->pegawai_model->find_first_row(); 
       
       $data["NIP"] = $pegawai_data->NIP_BARU;
       $data['TANGGAL_LAHIR']  = $this->input->post('TANGGAL_LAHIR') ? $this->input->post('TANGGAL_LAHIR') : null;
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

            if (!empty($data['TANGGAL_LAHIR'])){
                $keterangan = "Tanggal Lahir ".getIndonesiaFormat($data['TANGGAL_LAHIR']);
            } else $keterangan = "Akta kelahiran" ;
            $jenis_arsip = 16;
            $nomor_dokumen = $data['AKTE_KELAHIRAN']?$data['AKTE_KELAHIRAN']:$data['NAMA'];
            
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai_data->NIP_BARU,$nomor_dokumen,$jenis_arsip,$keterangan, $content,$file_name,0);
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
            if (empty($data['id_arsip'])){
                $response ['success']= false;
                $response ['msg']= "Gagal, harap lampirkan berkas";
                echo json_encode($response);
                die();
            }
        }
        
        // $data['STATUS_SATKER'] = 0;
        // $data['STATUS_BIRO'] = 0;
       $id_data = $this->input->post("id_data");
       if (isset($id_data) && !empty($id_data)) {
            $current = $this->anak_model->find($id_data);
            $perubahan = arrDiff($current,$data, true);
            if ($perubahan[1] != '[]'){
                $data_update = array();
                $data_update['PNS_ID']      = $this->input->post("PNS_ID");
                $data_update['KOLOM']       = "DATA_ANAK";
                $data_update['DARI']        = $perubahan[0];
                $data_update['PERUBAHAN']   = $perubahan[1] ;
                $data_update['NAMA_KOLOM']  = "DATA_ANAK";
                $data_update['LEVEL_UPDATE'] = 3;
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['ID_TABEL']    = $id_data;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
                log_activity($this->auth->user_id(), 'Update : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
            }
    } else {
            $data_update = array();
            $data_update['PNS_ID']      = $this->input->post("PNS_ID");
            $data_update['KOLOM']       = "DATA_ANAK";
            $data_update['DARI']        = null;
            $data_update['PERUBAHAN']   = json_encode($data);
            $data_update['NAMA_KOLOM']  = "DATA_ANAK";
            $data_update['LEVEL_UPDATE'] = 3;
            $data_update['UPDATE_TGL']  = date("Y-m-d");
            $data_update['ID_ARSIP']    = $data['id_arsip'];
            $data_update['UPDATED_BY']  = $this->auth->user_id();
            $id_update = $this->update_mandiri_model->insert($data_update);
            log_activity($this->auth->user_id(), 'Insert : ' . $data_update['NAMA_KOLOM'] . ' mandiri, ID UPDATE : ' . $id_update . ' ' . $this->input->ip_address(), 'pegawai');
    }
       $response ['success']= true;
       $response ['msg']= "Berhasil";
       echo json_encode($response);    

   }
    public function deleteanak($record_id){
        $this->auth->restrict($this->permissionDelete);
        $anak = $this->anak_model->find($record_id);
        if ($anak){
            if ($anak->STATUS_BIRO == 0){
                $update = $this->update_mandiri_model->find_by(array ('PNS_ID' => $anak->PNS_ID, 'NAMA_KOLOM' => 'DATA ANAK', 'ID_TABEL' => $record_id));
                if ($update){
                    $this->update_mandiri_model->update($update->ID, array('STATUS' => 4));
                }
            }
        }
		if ($this->anak_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Anak : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}
		exit();
    }
    public function deleteistri($record_id){
        $this->auth->restrict($this->permissionDelete);
        $istri = $this->istri_model->find($record_id);
        if ($istri){
            if ($istri->STATUS_BIRO == 0){
                $update = $this->update_mandiri_model->find_by(array ('PNS_ID' => $istri->PNS_ID, 'NAMA_KOLOM' => 'DATA PASANGAN', 'ID_TABEL' => $record_id));
                if ($update){
                    $this->update_mandiri_model->update($update->ID, array('STATUS' => 4));
                }
            }
        }
        if ($this->istri_model->delete($record_id)) {
             log_activity($this->auth->user_id(), 'delete data pasangan (istri/suami) : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function deleteortu($record_id){
        $this->auth->restrict($this->permissionDelete);
        $ortu = $this->orang_tua_model->find($record_id);
        if ($ortu){
            if ($ortu->STATUS_BIRO == 0){
                $update = $this->update_mandiri_model->find_by(array ('PNS_ID' => $ortu->PNS_ID, 'NAMA_KOLOM' => 'DATA ORANG TUA', 'ID_TABEL' => $record_id));
                if ($update){
                    $this->update_mandiri_model->update($update->ID, array('STATUS' => 4));
                }
            }
        }
        if ($this->orang_tua_model->delete($record_id)) {
             log_activity($this->auth->user_id(), 'delete data orangtua : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_kgb");
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
