<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Masters controller
 */
class Sk_pengalihan extends Admin_Controller
{
    protected $permissionCreate = 'Tte.Tte.Create';
    protected $permissionDelete = 'Tte.Tte.Delete';
    protected $permissionEdit   = 'Tte.Tte.Edit';
    protected $permissionView   = 'Tte.Tte.View';
    protected $permissionViewSk_Pengalihan   = 'Tte.Sk_pengalihan.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
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
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        $id = $this->uri->segment(5);
        $tte = $this->tte_model->find($id);        
        $judul_ttd = $tte->nama_proses;
        $penandatangan_sk = $tte->penandatangan_sk;
        $apenandatangan_sk = $this->pegawai_model->find_by("PNS_ID",$penandatangan_sk);
        Template::set('apenandatangan_sk', $apenandatangan_sk);

        $this->proses_variable_model->where("id_proses",$id);
        $proses_variable = $this->proses_variable_model->find_detil();
        Template::set('proses_variable', $proses_variable);

        $this->tte_master_korektor_model->where("id_tte_master_proses",$id);
        $verifikators = $this->tte_master_korektor_model->find_all();
        Template::set('verifikators', $verifikators);
        Template::set('tte', $tte);
        Template::set('judul_ttd', $judul_ttd);
        Template::set('id_master_proses', $id);
        Template::set('toolbar_title', "Buat Dokumen ".$judul_ttd);
        Template::set_view("tte/tte/create");
        Template::render();
    }
    /**
     * Allows editing of tte data.
     *
     * @return void
     */
    public function generate_sk()
    {
        // generate all
        $records=$this->trx_pengalihan_model->limit(100)->find_all();
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $this->create_ba($record);
                $this->create_sk($record,"JF");
                $this->create_sk($record,"SPMJP");
                $this->create_sk($record,"SPMT");
                $this->create_sk($record,"SPPP");   
            }
        endif;
        
        Template::set('toolbar_title', "Generate SK ");
        Template::render();
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
                $this->trx_pengalihan_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
            }
            if($filters['nip_key']){
                $this->trx_pengalihan_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
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
                $row []  = $record->nama."<br>".$record->nip."<br><b>Asal : </b><i>".$record->asal_instansi."</i>";
                $btn_sk = array(); 
                $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_BAP.pdf' data-toggle='tooltip' title='SK BAP' class='btn btn-sm btn-success popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JF.pdf' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMJP.pdf' data-toggle='tooltip' title='SK SPMJ' class='btn btn-sm btn-danger popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMT.pdf' data-toggle='tooltip' title='SK SPMT ' class='btn btn-sm btn-info popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                $btn_sk  [] = "<a href='#' url='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPPP.pdf' data-toggle='tooltip' title='SK SPP ' class='btn btn-sm btn-success popupurl ".$class_delete."'><i class='fa fa-eye'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_sk)."</div>";

                $btn_actions = array(); 
                $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_BAP.docx' data-toggle='tooltip' title='SK BAP' class='btn btn-sm btn-success ".$class_delete."'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_JF.pdf' data-toggle='tooltip' title='SK Pengangkatan Jabatan Fungsional ' class='btn btn-sm btn-warning ".$class_delete."'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMJP.docx' data-toggle='tooltip' title='SK SPMJ' class='btn btn-sm btn-danger ".$class_delete."'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPMT.docx' data-toggle='tooltip' title='SK SPMT ' class='btn btn-sm btn-info ".$class_delete."'><i class='fa fa-download'></i> </a>";
                $btn_actions  [] = "<a href='".trim($this->settings_lib->item('site.urlsk'))."template/{$record->nip}_{$record->id}_SPPP.docx' data-toggle='tooltip' title='SK SPP ' class='btn btn-sm btn-success ".$class_delete."'><i class='fa fa-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $btn_aksi = array();
                $btn_aksi  [] = "<a href='".base_url()."tte/sk_pengalihan/upload_revisi' data-toggle='tooltip' title='Upload SK perubahan ' class='btn btn-sm btn-success show-modal'><i class='fa fa-upload'></i> </a>";
                
                $row[] = "<div class='btn-group'>".implode(" ",$btn_aksi)."</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    function act_savedraft(){
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $this->form_validation->set_rules($this->tte_trx_draft_sk_model->get_validation_rules());
        $this->form_validation->set_rules('penandatangan_sk','Penandatangan','required|max_length[32]');
        $this->form_validation->set_rules('nip_pemilik_sk','NIP Pemilik SK','required|max_length[32]');
        $this->form_validation->set_rules('nomor_sk','Nomor Dokumen/ Nomor SK','required|max_length[100]');
        
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
        //$this->load->library('upload');
        $this->load->helper('handle_upload');
        $status = false;
        $msg = "";  
        $post = $this->input->post();
        $penandatangan_sk = $this->input->post('penandatangan_sk');
        $id_master_proses   = $this->input->post('id_master_proses');
        $nip_sk             = $this->input->post('nip_pemilik_sk');
        $nama_pemilik_sk             = $this->input->post('nama_pemilik_sk');
        $averifikator = $this->input->post('verifikator');
        $kode = $this->input->post('id');    
        $direktori = trim($this->settings_lib->item('site.pathsk'));

        $halaman_ttd = $this->input->post('halaman_ttd');
        $show_qrcode = $this->input->post('show_qrcode');
        $letak_ttd = $this->input->post('letak_ttd');

        // File upload
        $base64data = "";
        $save_file_ds = false;
        $data_ds = array();
        if (isset($_FILES['nama_sk']) && $_FILES['nama_sk']['name']) {
            $errors=array();
            $allowed_ext= array('pdf');
            $file_name =$_FILES['nama_sk']['name'];
         //   $file_name =$_FILES['image']['tmp_name'];
            $file_ext = explode('.',$file_name);
            $file_size=$_FILES['nama_sk']['size'];
            $file_tmp= $_FILES['nama_sk']['tmp_name'];
            $type= $_FILES['nama_sk']['type'];
            //echo $file_ext[1];echo "<br>";
            $content_file = file_get_contents($file_tmp);
            $base64data = base64_encode($content_file);
            //die($base64data);
            if($base64data != ""){
                $data["base64pdf_hasil"]        = $base64data;    
                $data_ds["teks_base64"]         = $base64data;    
                $save_file_ds = true;
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
            if(count($averifikator) == 0){
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Error
                    </h4>
                    Silahkan tentukan minimal 1 Verifikator
                </div>
                ";
                echo json_encode($response);
                exit();
            }else{
                $jmlverifikator = 0;
                foreach ($averifikator as $pid) {
                    if($pid != ""){
                        $jmlverifikator++;
                    }
                }
                if($jmlverifikator == 0){
                     $response['msg'] = "
                        <div class='alert alert-block alert-error fade in'>
                            <a class='close' data-dismiss='alert'>&times;</a>
                            <h4 class='alert-heading'>
                                Error
                            </h4>
                            Silahkan tentukan minimal 1 Verifikator
                        </div>
                        ";
                        echo json_encode($response);
                        exit();
                }
            }
        }
         
        if($penandatangan_sk ==""){
            $status = FALSE;
            $msg = "silahkan isi penandatangan SK";
        }
        $id_file = "";
        $nomor_sk = $this->input->post('nomor_sk') ? trim($this->input->post('nomor_sk')) : null;
        $tgl_sk = $this->input->post('tgl_sk') ? trim($this->input->post('tgl_sk')) : null;
        $tmt_sk = $this->input->post('tmt_sk') ? trim($this->input->post('tmt_sk')) : null;
        if($nomor_sk != "" and $kode == ""){
            $id_file = md5($nomor_sk.date("ymdhis"));
            $data['id_file']            = $id_file;
        }
        $data['nip_sk']             = $nip_sk;
        $data['nama_pemilik_sk']             = $nama_pemilik_sk;
        
        $data['penandatangan_sk']             = $penandatangan_sk;
        $data['id_master_proses']             = $id_master_proses ? $id_master_proses : null;
        $data['tgl_sk']             = $tgl_sk;
        $data['tmt_sk']             = $tmt_sk;
        $data['nomor_sk']           = $nomor_sk;

        $data['show_qrcode']           = $show_qrcode;
        $data['letak_ttd']           = $letak_ttd;
        $data['halaman_ttd']           = $halaman_ttd;
        
        if($kode != ""){
            $data['updated_by']         = $this->auth->user_id();
            $data['updated_date']        = date("Y-m-d");
            $id = $this->tte_trx_draft_sk_model->update($kode,$data);
        }else{
            $data['created_by']         = $this->auth->user_id();
            $data['created_date']            = date("Y-m-d");

            $id = $this->tte_trx_draft_sk_model->insert($data);   
            $kode = $id; 
        }
        if($id){
            $status = true;
            $msg = "Berhasil";   
        }else{
            $status = false;
            $msg = "Gagal";   
        }
    // save variable
        if($id_master_proses != ""){
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->tte_trx_draft_sk_detil_model->delete_where($datadel);

            $this->proses_variable_model->where("id_proses",$id_master_proses);
            $proses_variable = $this->proses_variable_model->find_detil();
            if (isset($proses_variable) && is_array($proses_variable) && count($proses_variable)):
                foreach($proses_variable as $recordvar):
                    $datavar = array();
                    $datavar["id_tte_trx_draft_sk"] = $kode;
                    $datavar['id_variable']         = $recordvar->id_variable;
                    $datavar['isi']         = $this->input->post($recordvar->nama_variable);
                    $id_proses = $this->tte_trx_draft_sk_detil_model->insert($datavar);
                endforeach;
            endif;
            // korektor
            
            $datadel = array('id_tte_trx_draft_sk '=>$kode);
            $this->Tte_trx_korektor_draft_model->delete_where($datadel);
            $korektor_ke = 1;
            foreach ($averifikator as $pid) {
                if($pid != ""){
                    $datavar = array();
                    $datavar["id_pegawai_korektor"]      = $pid;
                    $datavar['id_tte_trx_draft_sk']        = $kode;
                    $datavar['korektor_ke']        = $korektor_ke;
                    $id_proses = $this->Tte_trx_korektor_draft_model->insert($datavar);
                    $korektor_ke++;
                }
            }
            if($save_file_ds){
                if($kode != ""){
                    $tte_draft = $this->tte_trx_draft_sk_model->find($kode);
                    $id_file = isset($tte_draft->id_file) ? $tte_draft->id_file : "";
                    
                }
                
                // save to sk_ds
                // create file
                $this->createfile_pdf($base64data,$id_file);
                $data_file_ds = $id_sk_ds = $this->sk_ds_model->find_by("id_file",$id_file);
                $id_sk_ds      = isset($data_file_ds->id) ? $data_file_ds->id : "";
                $is_signed = isset($data_file_ds->is_signed) ? $data_file_ds->is_signed : "";
                if($is_signed == "1"){
                    $data_json = array(
                        'status' => false,
                        'msg' => "File sudah di tandatangan, tidak bisa diubah"

                    );

                    $json_data = json_encode($data_json);
                    echo $json_data;
                    die();
                }
                $tte = $this->tte_model->find($id_master_proses);        
                $kategori = $tte->nama_proses;
                $data_ds['id_file']           = $id_file;
                $data_ds['waktu_buat']        = date("Y-m-d");
                $data_ds['id_pegawai_ttd']    = $penandatangan_sk;
                $data_ds['nip_sk']            = $nip_sk;
                $data_ds['nama_pemilik_sk']            = $nama_pemilik_sk;
                $data_ds['kategori']          = $kategori;
                $data_ds['nomor_sk']          = $nomor_sk;

                $data_ds['show_qrcode']         = $show_qrcode;
                $data_ds['letak_ttd']           = $letak_ttd;
                $data_ds['halaman_ttd']         = $halaman_ttd;

                if(count($averifikator) > 0){
                    $data_ds['is_signed']            = 0;
                    $data_ds['is_corrected']         = 0;
                }else{
                    $data_ds['is_corrected']         = 1;// dibuat langsung bisa tandatangan jika ga ada korektor        
                    $data_ds['is_signed']            = 0;
                }
                
                $data_ds['ds_ok']               = 1; // merupakan ttd digital
                if($id_sk_ds != ""){
                    $return_ds = $this->sk_ds_model->update($id_file,$data_ds);
                    log_activity($this->auth->user_id(),"Update File ds dari web " . ': ' . $id_sk_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }else{
                    $return_ds = $this->sk_ds_model->insert($data_ds);    
                    log_activity($this->auth->user_id(),"Save File ds dari web " . ': ' . $return_ds . ' : ' . $this->input->ip_address(), 'sk_ds');
                }
                if($return_ds){
                    // save korektor
                    $averifikator = $this->input->post('verifikator');
                    $datadel_korektor = array('id_file '=>$id_file);
                    $this->sk_ds_korektor_model->delete_where($datadel_korektor);
                    $korektor_ke = 1;
                    if(count($averifikator) > 0){
                        foreach ($averifikator as $pid) {
                            if($pid != ""){
                                $data_korektor = array();
                                $data_korektor['id_file']        = $id_file;
                                $data_korektor["id_pegawai_korektor"]      = $pid;
                                $data_korektor['korektor_ke']        = $korektor_ke;
                                $data_korektor['is_corrected']       = $korektor_ke == 1 ? 2 : null;
                                $id_ds_korektor = $this->sk_ds_korektor_model->insert($data_korektor);
                                if($id_ds_korektor){
                                    log_activity($this->auth->user_id(),"Save Korektor ds dari web " . ': ' . $id_ds_korektor . ' : ' . $this->input->ip_address(), 'sk_ds');
                                }
                                $korektor_ke++;
                            }
                        }
                    } 
                    
                    $status = true;
                    $msg = "Upload berhasil";   
                }else{
                    $status = false;
                    $msg = "Upload gagal";   
                }
            }
        }
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
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
        $this->auth->restrict($this->permissionCreate);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
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
        if (isset($_FILES['nama_sk']) && $_FILES['nama_sk']['name']) {
            $errors=array();
            $allowed_ext= array('docx');
            $file_name =$_FILES['nama_sk']['name'];
         //   $file_name =$_FILES['image']['tmp_name'];
            $file_ext  = explode('.',$file_name);
            $file_size =$_FILES['nama_sk']['size'];
            $file_tmp  = $_FILES['nama_sk']['tmp_name'];
            $type= $_FILES['nama_sk']['type'];
            //echo $file_ext[1];echo "<br>";
            if (isset($_FILES['nama_sk']) && is_array($_FILES['nama_sk']) && $_FILES['nama_sk']['error'] != 4)
             {
                
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
         
        if($penandatangan_sk ==""){
            $status = FALSE;
            $msg = "silahkan isi penandatangan SK";
        }
        $id_file = "";
        $nomor_sk = $this->input->post('nomor_sk') ? trim($this->input->post('nomor_sk')) : null;
        $tgl_sk = $this->input->post('tgl_sk') ? trim($this->input->post('tgl_sk')) : null;
        $tmt_sk = $this->input->post('tmt_sk') ? trim($this->input->post('tmt_sk')) : null;
        if($nomor_sk != "" and $kode == ""){
            $id_file = md5($nomor_sk.date("ymdhis"));
            $data['id_file']            = $id_file;
        }
        $data['nip_sk']             = $nip_sk;
        $data['nama_pemilik_sk']             = $nama_pemilik_sk;
        
        $data['penandatangan_sk']             = $penandatangan_sk;
        $data['id_master_proses']             = $id_master_proses ? $id_master_proses : null;
        $data['tgl_sk']             = $tgl_sk;
        $data['tmt_sk']             = $tmt_sk;
        $data['nomor_sk']           = $nomor_sk;

        $data['show_qrcode']           = $show_qrcode;
        $data['letak_ttd']           = $letak_ttd;
        $data['halaman_ttd']           = $halaman_ttd;
        
        if($kode != ""){
            $data['updated_by']         = $this->auth->user_id();
            $data['updated_date']        = date("Y-m-d");
            $id = $this->tte_trx_draft_sk_model->update($kode,$data);
        }else{
            $data['created_by']         = $this->auth->user_id();
            $data['created_date']            = date("Y-m-d");

            $id = $this->tte_trx_draft_sk_model->insert($data);   
            $kode = $id; 
        }
        if($id){
            $status = true;
            $msg = "Berhasil";   
        }else{
            $status = false;
            $msg = "Gagal";   
        }
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }
    private function generate_pdf_from_word($file_word = ""){
        $output = shell_exec('unoconv -f pdf '.$file_word.' 2>&1');
    }
    private function create_ba($draftsk){
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
            $aData['nama'] = $aData['nama_lengkap'] != "" ? $aData['nama_lengkap'] : $aData['nama'];
            $vardefault = array(
                'nomor'=>$this->create_nomor($tte)
            );
            $array_variable = array_merge($aData,$vardefault);
            $TBS->MergeField('o',$array_variable);
            $output_file_name = $draftsk->nip.'_'.$draftsk->id.'_BAP.docx';
            $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
            $docx_location = $direktori.$output_file_name;
            $TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
            $this->generate_pdf_from_word($docx_location);
        }
        
    }
    private function create_sk($draftsk,$kode){
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
            $vardefault = array(
                'nomor'=>$this->create_nomor($tte),
                'rpTerbilang'=>Terbilang($aData['tunjangan_rp']),
                'tunjanganFormat'=>rupiah($aData['tunjangan_rp']),
                'jabatanUwords'=>ucwords($aData['jenis_jabatan']),
                'tmt_pangkat'=>getIndonesiaFormat($aData['tmt_pangkat'])
            );
            $array_variable = array_merge($aData,$vardefault);
            $TBS->MergeField('o',$array_variable);
            $output_file_name = $draftsk->nip.'_'.$draftsk->id.'_'.$kode.'.docx';
            $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
            $docx_location = $direktori.$output_file_name;
            $TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
            $this->generate_pdf_from_word($docx_location);
        }
        
    }
    private function create_nomor($tte){
        $this->load->helper('aplikasi');
        $format_nomor   = $tte->format_nomor_sk;
        $nomor          = (int)$tte->nomor_terakhir+1;
        $tahun          = date("Y");
        $bulan          = (int)date("m");
        $param = array($nomor, $bulan,$tahun);
        $this->update_nomor($tte,$nomor);
        return sprintf($format_nomor,$nomor,$bulan,$tahun);
    }
    private function update_nomor($tte,$nomor_terakhir){
        $dataupdate = array();
        $dataupdate["nomor_terakhir"] = $nomor_terakhir;
        $this->tte_model->skip_validation(true);
        $this->tte_model->update($tte->id, $dataupdate);
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