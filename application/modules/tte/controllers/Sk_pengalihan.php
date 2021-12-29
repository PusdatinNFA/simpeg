<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Sk_pengalihan extends Admin_Controller
{
    protected $permissionViewSk_Pengalihan   = 'Tte.Sk_pengalihan.View';
    protected $permissionGenerate   = 'Sk_pengalihan.Admin.Generate';
    protected $permissionUploadRevisi   = 'Sk_pengalihan.Admin.RevisiSk';
    protected $permissionUploadDs   = 'Sk_pengalihan.Admin.KirimDs';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->auth->restrict($this->permissionView);
        $this->load->model('tte/tte_model');
        $this->load->model('tte/variable_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('tte/proses_variable_model');
        $this->load->model('tte/tte_master_korektor_model');
        $this->load->model('tte/tte_trx_draft_sk_model');
        $this->load->model('tte/tte_trx_draft_sk_detil_model');
        $this->load->model('tte/Tte_trx_korektor_draft_model');
        $this->load->model('sk_ds/kategori_ds_model');
        $this->load->model('sk_ds/sk_ds_model');
        $this->load->model('sk_ds/sk_ds_korektor_model');
        $this->load->model('tte/trx_pengalihan_model');
        $this->load->model('tte/Nomor_surat_model');
        $this->lang->load('tte');
        $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
    }

    /**
     * Display a list of tte data.
     *
     * @return void
     */
    public function index()
    {
        
        Template::set('toolbar_title', "SK pengalihan");
        Template::set_view("tte/pengalihan/index");
        Template::render();
    }
    public function upload_revisi()
    {
        Template::set('toolbar_title', "Upload Revisi SK");
        Template::set_view("tte/pengalihan/upload_revisi");
        Template::render();
    }
    
    /**
     * Create a tte object.
     *
     * @return void
     */
    public function kirimds()
    {
        $id = $this->input->get('kode');
        $this->auth->restrict($this->permissionUploadDs);
        $this->trx_pengalihan_model->where("id",$id);
        $records = $this->trx_pengalihan_model->find_all();
        $number = 0;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($record->flag_jf == "1"){
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_BAP.pdf")){
                        $id_file                        = md5($record->nomor_ba);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"BAP",$record->nomor_ba,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_BAP.pdf");
                        }
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JF.pdf")){
                        $id_file                        = md5($record->nomor_sk_jf);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"JF",$record->nomor_sk_jf,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JF.pdf");
                        }
                        
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JFU.pdf")){
                        $id_file                        = md5($record->nomor_sk_jf);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"JFU",$record->nomor_sk_jf,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JFU.pdf");
                        }
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMJP.pdf")){
                        $id_file                        = md5($record->nomor_spmj);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"SPMJP",$record->nomor_spmj,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMJP.pdf");
                        }
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMT.pdf")){
                        $id_file                        = md5($record->nomor_spmt);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"SPMT",$record->nomor_spmt,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMT.pdf");
                        }
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPPP.pdf")){
                        $id_file                        = md5($record->nomor_spp);
                        $is_exist  = $this->sk_ds_model->is_exist($id_file);
                        if(!$is_exist){
                           $this->saveDs($record,"SPPp",$record->nomor_spp,trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPPP.pdf");
                        }
                    } 
                }
                if($record->flag_jf == "2"){
                    $nomor_jf = $this->create_sk($record,"JFU",$record->nomor_sk_jf,$record->nomor_sk_jf);
                }
                $number++;
                // update flag
                $this->updateStatusGenerate($record->id);
            }
        endif;
        $output = array();
        $output['success'] = true;
        $output['msg'] = "Kirim ke DS selesai";
        $output['jumlah'] = $number;
        echo json_encode($output);
        die();
    }
    private function saveDs($record_pengalihan,$kategori,$nomor_sk,$file_pdf){
        $id_file                        = md5($nomor_sk);
        $tte                            = $this->tte_model->find_by("kode_proses",$kategori);        
        $judul_ttd                      = $tte->nama_proses;
        $averifikator                   = $this->getKorektors($tte->id);
        $data_ds = array();
        $data_ds['id_file']             = $id_file;
        $data_ds['waktu_buat']          = date("Y-m-d H:i:s");
        $data_ds['id_pegawai_ttd']      = $tte->penandatangan_sk;
        $data_ds['nip_sk']              = $record_pengalihan->nip;
        $data_ds['nama_pemilik_sk']     = $record_pengalihan->nama_lengkap;
        $data_ds['kategori']            = $kategori;
        $data_ds['nomor_sk']            = $nomor_sk;
        $data_ds['show_qrcode']         = 1;
        $data_ds['letak_ttd']           = 1;
        $data_ds['halaman_ttd']         = 1;
        if(count($averifikator) > 0){
            $data_ds['is_signed']            = 0;
            $data_ds['is_corrected']         = 0;
        }else{
            $data_ds['is_corrected']         = 1;// dibuat langsung bisa tandatangan jika ga ada korektor        
            $data_ds['is_signed']            = 0;
        }    
        $data_ds['ds_ok']               = 1; // merupakan ttd digital
        $data_ds['teks_base64']         = $this->conversiFileToBase64($file_pdf);
        $this->sk_ds_model->skip_validation(true);
        $return_ds = $this->sk_ds_model->insert($data_ds);    
        log_activity($this->auth->user_id(),"Kirim dokumen {$kategori} ke DS " . ': ' . $return_ds . ' : ' . $this->input->ip_address(), 'sk_pengalihan');

        $datadel_korektor   = array('id_file '=>$id_file);
        $this->sk_ds_korektor_model->delete_where($datadel_korektor);
        $korektor_ke = 1;
        if(isset($averifikator) && is_array($averifikator) && count($averifikator))
        {
            foreach ($averifikator as $record) {
                $data_korektor = array();
                $data_korektor['id_file']               = $id_file;
                $data_korektor["id_pegawai_korektor"]   = $record->id_pegawai_korektor;
                $data_korektor['korektor_ke']           = $korektor_ke;
                $data_korektor['is_corrected']          = $korektor_ke == 1 ? 2 : null;
                $this->sk_ds_korektor_model->skip_validation(true);
                $id_ds_korektor = $this->sk_ds_korektor_model->insert($data_korektor);
                if($id_ds_korektor){
                    log_activity($this->auth->user_id(),"Save Korektor ds dari " . ': ' . $id_ds_korektor . ' : ' . $this->input->ip_address(), 'pengalihan');
                }
                $korektor_ke++;
            }
        }
    }
    private function getKorektors($id){
        $this->tte_master_korektor_model->where("id_tte_master_proses",$id);
        $verifikators = $this->tte_master_korektor_model->find_all();
        return $verifikators;
    }
    private function conversiFileToBase64($fileFullDirektory){
        $data_file = file_get_contents($fileFullDirektory);
        $FILE_BASE64 = base64_encode($data_file);
        return $FILE_BASE64;
    }
    /**
     * Allows editing of tte data.
     *
     * @return void
     */
    public function generate_sk()
    {
        // generate all
        $id = $this->input->get('kode');
        if($id != ""){
            $this->trx_pengalihan_model->where("id",$id);
        }else{
            $this->trx_pengalihan_model->where("flag_generate is null");
        }
        $this->trx_pengalihan_model->where("flag_jf",1);
        $records = $this->trx_pengalihan_model->limit(50)->find_all();
        $number = 0;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                if($record->flag_jf == "1"){
                    $nomor_jf = $this->create_sk($record,"JF",$record->nomor_sk_jf,$record->nomor_sk_jf);
                    $this->create_ba($record,$nomor_jf,$record->nomor_ba);
                    $this->create_sk($record,"SPPP",$nomor_jf,$record->nomor_spp);
                    $this->create_sk($record,"SPMJP",$nomor_jf,$record->nomor_spmj);
                    $this->create_sk($record,"SPMT",$nomor_jf,$record->nomor_spmt);   
                }
                if($record->flag_jf == "2"){
                    $nomor_jf = $this->create_sk($record,"JFU",$record->nomor_sk_jf,$record->nomor_sk_jf);
                }
                $number++;
                // update flag
                $this->updateStatusGenerate($record->id);
            }
        endif;
        $output = array();
        $output['success'] = true;
        $output['msg'] = "Generate Selesai";
        $output['jumlah'] = $number;
        echo json_encode($output);
        die();
    }
    private function updateStatusGenerate($id){
        $dataupdate = array();
        $dataupdate['flag_generate'] = 1;
        $this->trx_pengalihan_model->skip_validation(true);
        $this->trx_pengalihan_model->update($id, $dataupdate);
    }
    public function ajax_data(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";
        $NIP = trim($this->auth->username());
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             
            if($filters['nama_key']){
                $this->trx_pengalihan_model->like('upper("nama")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_key']){
                $this->trx_pengalihan_model->like('upper("nip")',strtoupper($filters['nip_key']),"BOTH"); 
            }
            if($filters['is_jf_key']){
                $this->trx_pengalihan_model->where('flag_jf',$filters['is_jf_key']);    
            }
            if($filters['agama_key']){
                $this->trx_pengalihan_model->where('agama',$filters['agama_key']);    
            }
            if($filters['instansi_key']){
                $this->trx_pengalihan_model->like('upper("asal_instansi")',strtoupper($filters['instansi_key']),"BOTH");       
            }
    
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->trx_pengalihan_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->trx_pengalihan_model->order_by("nip",$order['dir']);
            }
            if($order['column']==2){
                $this->trx_pengalihan_model->order_by("nama",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->trx_pengalihan_model->limit($length,$start);
        $records=$this->trx_pengalihan_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $class_delete = "";
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama."<br>".$record->nip."<br><b>Asal : </b><i>".$record->asal_instansi."</i>"."<br><b>Agama : </b><i>".$record->agama."</i>"."<br><b>NO SK : </b><i>".$record->nomor_sk_jf."</i>";
                $btn_sk = array(); 
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_BAP.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_BAP.pdf' data-toggle='tooltip' title='BAP' class='btn btn-sm btn-success popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                        }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JF.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JF.pdf' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning popupurl'><i class='fa fa-eye'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JFU.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JFU.pdf' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning popupurl'><i class='fa fa-eye'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMJP.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMJP.pdf' data-toggle='tooltip' title='SPMJ' class='btn btn-sm btn-danger popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMT.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMT.pdf' data-toggle='tooltip' title='SPMT ' class='btn btn-sm btn-info popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPPP.pdf")){
                        $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPPP.pdf' data-toggle='tooltip' title='SPP ' class='btn btn-sm btn-success popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                    }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_sk)."</div>";

                $btn_actions = array(); 
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_BAP.docx")){
                        $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_BAP.docx' data-toggle='tooltip' title='SK BAP' class='btn btn-sm btn-success'><i class='fa fa-download'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JF.docx")){
                        $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JF.docx' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning'><i class='fa fa-download'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_JFU.docx")){
                        $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JFU.docx' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning'><i class='fa fa-download'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMJP.docx")){
                        $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMJP.docx' data-toggle='tooltip' title='SK SPMJ' class='btn btn-sm btn-danger ".$class_delete."'><i class='fa fa-download'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPMT.docx")){
                    $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMT.docx' data-toggle='tooltip' title='SK SPMT ' class='btn btn-sm btn-info ".$class_delete."'><i class='fa fa-download'></i> </a>";
                    }
                    if(file_exists(trim($this->settings_lib->item('site.pathsk'))."template/{$record->nip}_{$record->id}_SPPP.docx")){
                        $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPPP.docx' data-toggle='tooltip' title='SK SPP ' class='btn btn-sm btn-success ".$class_delete."'><i class='fa fa-download'></i> </a>";
                    }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $btn_aksi = array();
                if($this->auth->has_permission($this->permissionUploadRevisi)){
                    $btn_aksi  [] = "<a href='".base_url()."tte/sk_pengalihan/upload_revisi/{$record->id}' data-toggle='tooltip' title='Upload SK perubahan ' class='btn btn-sm btn-success show-modal'><i class='fa fa-upload'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUploadDs)){
                    $btn_aksi  [] = "<button  data-toggle='tooltip' title='Kirim Ke DS' class='btn btn-sm btn-warning sendDs' kode='{$record->id}'><i class='fa fa-key'></i> </button>";
                }
                if($this->auth->has_permission($this->permissionGenerate)){
                    $btn_aksi  [] = "<button  data-toggle='tooltip' title='Generate SK' class='btn btn-sm btn-danger generateSk' kode='{$record->id}'><i class='fa fa-forward'></i> </button>";
                }
                
                $row[] = "<div class='btn-group'>".implode(" ",$btn_aksi)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    private function createfile_pdf($b64 = "",$file_id = ""){
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $file_id.".pdf";
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        
        $bin = base64_decode($b64, true);
        file_put_contents($direktori.$file_name, $bin);
    }
    function save_draft(){
        // $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $this->load->helper('handle_upload');
        $status = false;
        $msg    = "";  
        $post   = $this->input->post();
        $penandatangan_sk = $this->input->post('penandatangan_sk');
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        // File upload
        $base64data = "";
        $save_file_ds = false;
        $data_ds = array();
        if (isset($_FILES['userfile']) && $_FILES['userfile']['name']) {
            $errors=array();
            $allowed_ext= array('docx');
            $file_name =$_FILES['userfile']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext  = explode('.',$file_name);
            $file_size =$_FILES['userfile']['size'];
            $file_tmp  = $_FILES['userfile']['tmp_name'];
            $type= $_FILES['userfile']['type'];
            //echo $file_ext[1];echo "<br>";
            if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
             {
                unlink(trim($this->settings_lib->item('site.pathsk'))."template/".$file_name);
                $tmp_name   = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
                $uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathsk'))."template/");
                 if (isset($uploadData['error']) && !empty($uploadData['error']))
                 {
                    $tipefile=$_FILES['userfile']['type'];
                    //$tipefile = $_FILES['userfile']['name'];
                     $upload = false;
                     log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].trim($this->settings_lib->item('site.pathsk')).$tipefile.$this->input->ip_address(), 'pegawai');
                 }else{
                    $status = true;
                    $docx_location = $uploadData['data']['full_path'];
                    $this->generate_pdf_from_word($docx_location);
                    log_activity($this->auth->user_id(), 'Update draft SK : ' . $file_name . ' : ' . $this->input->ip_address(), 'tte');
                 }
             }
            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension BA pelantikan tidak diizinkan, silahkan pilih file pdf</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 5097152)
            {
                $errors[]= 'File size must be under 5mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 5mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();

            }
        }
        $data_json = array(
            'success' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }
    private function generate_pdf_from_word($file_word = ""){
        $output = shell_exec('unoconv -f pdf '.$file_word.' 2>&1');
    }
    private function create_ba($draftsk,$nomor_jf,$nomor_sk = ""){
        $this->load->helper('aplikasi');
        // berita acara
        $kode           = 'BAP';
        $tte            = $this->tte_model->find_by("kode_proses",$kode);        
        $judul_ttd      = $tte->nama_proses;
        $template_sk    = $tte->template_sk;
        if($template_sk != ""){
            $aData = $this->initial_data($draftsk);
            $this->load->library('LibOpenTbs');
            if($draftsk->agama != "")
                $template_name = trim($this->settings_lib->item('site.pathsk'))."template/".$template_sk.$draftsk->agama.".docx";
            else
                $template_name = trim($this->settings_lib->item('site.pathsk'))."template/".$template_sk."Islam.docx";
            $TBS = $this->libopentbs->TBS;
            $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
            $nomor_sk = $nomor_sk!= "" ? $nomor_sk : $this->create_nomor($tte);
            $aData['nama'] = $aData['nama_lengkap'] != "" ? $aData['nama_lengkap'] : $aData['nama'];
            $vardefault = array(
                'nomor'=>$nomor_sk,
                'nomor_bap'=>$nomor_sk,
                'nomor_sk_jf'=>$nomor_jf,
            );
            $array_variable = array_merge($aData,$vardefault);
            $TBS->MergeField('o',$array_variable);
            $output_file_name = $draftsk->nip.'_'.$draftsk->id.'_BAP.docx';
            $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
            $docx_location = $direktori.$output_file_name;
            $TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
            $this->generate_pdf_from_word($docx_location);
            $this->updateNomorSk($draftsk->id,$nomor_sk,$kode);
        }
        
    }
    private function create_sk($draftsk,$kode,$nomor_sk_jf = "",$nomor_sk = ""){
        $this->load->helper('aplikasi');
        $tte            = $this->tte_model->find_by("kode_proses",$kode);        
        $judul_ttd      = $tte->nama_proses;
        $template_sk    = $tte->template_sk;
        if($template_sk != ""){
            $aData = $this->initial_data($draftsk);
            $this->load->library('LibOpenTbs');
            $template_name = trim($this->settings_lib->item('site.pathsk'))."template/".$template_sk;
            $TBS = $this->libopentbs->TBS;
            $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
            $aData['nama'] = $aData['nama_lengkap'] != "" ? $aData['nama_lengkap'] : $aData['nama'];
            $nomor_sk = $nomor_sk ? $nomor_sk : $this->create_nomor($tte);
            $vardefault = array(
                'nomor'=>$nomor_sk,
                'rpTerbilang'=>Terbilang($aData['tunjangan_rp']),
                'tunjanganFormat'=>rupiah($aData['tunjangan_rp']),
                'jabatanUwords'=>ucwords(strtolower($aData['jenis_jabatan'])),
                'jabatan_baruUpper'=>strtoupper($aData['jenis_jabatan']),
                'tmt_pangkat'=>getIndonesiaFormat($aData['tmt_pangkat']),
                'tanggal_lahirFormat'=>getIndonesiaFormat($aData['tanggal_lahir']),
                'tmt_jabatan'=>isset($aData['tanggal_lahir']) ? $aData['tmt_jabatan'] : '3 Januari 2022',
                'nomor_sk_jf'=>$nomor_sk_jf
            );
            $array_variable = array_merge($aData,$vardefault);
            $TBS->MergeField('o',$array_variable);
            $output_file_name = $draftsk->nip.'_'.$draftsk->id.'_'.$kode.'.docx';
            $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
            $docx_location = $direktori.$output_file_name;
            $TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
            $this->generate_pdf_from_word($docx_location);
            $this->updateNomorSk($draftsk->id,$nomor_sk,$kode);
        }
        return $nomor_sk;
        
    }
    private function create_nomor($tte){
        $this->load->helper('aplikasi');
        $nomor    = $this->Nomor_surat_model->getNomor($tte->format_nomor_sk);
        $format_nomor   = $tte->format_nomor_sk;
        $tahun          = date("Y");
        $bulan          = (int)date("m");
        $param          = array($nomor, $bulan,$tahun);
        $this->updateNomorTerakhir($tte->format_nomor_sk,$nomor);
        return sprintf($format_nomor,$nomor,$bulan,$tahun);
    }
    private function updateNomorTerakhir($format,$nomor_terakhir){
        $dataupdate = array();
        $dataupdate["nomor_terakhir"] = $nomor_terakhir;
        $this->Nomor_surat_model->skip_validation(true);
        $this->Nomor_surat_model->update_where("format_nomor",$format, $dataupdate);
    }
    private function updateNomorSk($id,$nomor_sk,$kolom){
        switch ($kolom) {
          case "BAP":
            $kolom = "nomor_ba";
            break;
          case "JF":
            $kolom = "nomor_sk_jf";
            break;
          case "SPMJP":
            $kolom = "nomor_spmj";
            break;
          case "SPPP":
            $kolom = "nomor_spp";
            break;
        case "SPMT":
            $kolom = "nomor_spmt";
            break;
            
          default:
            $kolom = "";
        }
        $dataupdate = array();
        $dataupdate[$kolom] = $nomor_sk;
        $this->trx_pengalihan_model->skip_validation(true);
        if($kolom != "")
            $this->trx_pengalihan_model->update($id, $dataupdate);
    }
    private function initial_data($record_data){
        $a_data = array();
        $itemfield = $this->db->list_fields('trx_pengalihan');
        foreach($itemfield as $field)
        {
            $a_data[$field] = isset($record_data->$field) ? $record_data->$field : "";  
        }
        return $a_data;
    }
    private function initial_pegawai($nip_sk = ""){
        $apegawai = $this->pegawai_model->find_by("NIP_BARU",$nip_sk);
        $var_pegawai = array();
        $itemfield = $this->db->list_fields('pegawai');
        foreach($itemfield as $field)
        {
            $var_pegawai[$field] = isset($apegawai->$field) ? $apegawai->$field : "";  
        }
        if(isset($apegawai->NIP_BARU)){
            $var_pegawai["nama"] = $apegawai->GELAR_DEPAN."".$apegawai->NAMA.", ".$apegawai->GELAR_BELAKANG;
            $var_pegawai["nip"] = $NIP_BARU;
        }
        return $var_pegawai;
    }
}