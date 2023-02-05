<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Sk controller
 */
class Sk extends Admin_Controller
{
    protected $permissionCreate = 'Sk_ds.Sk.Create';
    protected $permissionDelete = 'Sk_ds.Sk.Delete';
    protected $permissionEdit   = 'Sk_ds.Sk.Edit';
    protected $permissionView   = 'Sk_ds.Sk.View';
    protected $permissionValidasi   = 'Sk_ds.Sk.Validasi';
    protected $permissionttd   = 'Sk_ds.Sk.Tandatangan';

    protected $permissionBarier   = 'Sk_ds.Sk.ReqBarier';
    protected $permissionToken   = 'Sk_ds.Sk.ReqToken';
    protected $permissionViewall   = 'Sk_ds.Sk.Viewall';
    protected $permissionViewlog   = 'Sk_ds.Sk.Viewlog';
    protected $permissionTtdDupakPtp   = 'Sk_ds.DupakPtp.Tandatangan';
    protected $permissionDashboard   = 'Sk_ds.DashboardDs.View';
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('sk_ds/sk_ds_model');
        $this->load->model('sk_ds/sk_ds_korektor_model');
        $this->load->model('sk_ds/kategori_ds_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $this->load->model('sk_ds/vw_ds_antrian_ttd_model');
        $this->load->model('sk_ds/vw_ds_antrian_korektor_model');
        $this->load->model('sk_ds/vw_ds_resume_ttd_model');
        $this->load->model('tte/tte_model');
        $this->lang->load('sk_ds');
        
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'sk/_sub_nav');

        Assets::add_module_js('sk_ds', 'sk_ds.js');
    }

    /**
     * Display a list of sk ds data.
     *
     * @return void
     */
    public function index()
    {
        
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Manage Tanda Tangan SK");

        $NIP = trim($this->auth->username());
        $this->pegawai_model->select("NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);

        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);

        // DUPAK
        $EncriptsessionData = "";
        $passencript = "";
        if($this->auth->has_permission($this->permissionTtdDupakPtp)){
            $passencript = "a9b4776c533fdb2f56ce1b4818220c0c";
            $EncriptsessionData = trim($NIP);
        }
        Template::set("EncriptsessionData",$EncriptsessionData);
        Template::set("passencript",$passencript);
        // end dupak
        Template::render();
    }
    public function validasi()
    {
        Template::set('toolbar_title', "Koreksi SK");
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);
        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
    public function dashboard()
    {
        
        $this->auth->restrict($this->permissionDashboard);
        Template::set('toolbar_title', "Dashboard DS");

        Template::render();
    }
    public function listantrianttd($PNS_ID)
    {
        
        $this->auth->restrict($this->permissionDashboard);
        Template::set('toolbar_title', "Detil Antrian DS");
        Template::set("PNS_ID",$PNS_ID);
        Template::render();
    }
    public function listkoreksi($PNS_ID)
    {
        
        $this->auth->restrict($this->permissionDashboard);
        Template::set('toolbar_title', "Detil Koreksi");
        Template::set("PNS_ID",$PNS_ID);
        Template::render();
    }
    public function listttd($PNS_ID)
    {
        
        $this->auth->restrict($this->permissionDashboard);
        Template::set('toolbar_title', "Detil ttd");
        Template::set("PNS_ID",$PNS_ID);
        Template::render();
    }
    public function viewall()
    {
            
        Template::set('toolbar_title', "Daftar Semua SK");  

        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
    public function viewallsatker()
    {
            
        Template::set('toolbar_title', "Daftar Semua SK Unit Kerja");  
        $reckategori_ds = $this->kategori_ds_model->find_all();
        Template::set("reckategori_ds",$reckategori_ds);
        Template::render();
    }
    public function viewlog()
    {
        $this->auth->restrict($this->permissionViewlog);       
        Template::set('toolbar_title', "Log Transaksi Tandatangan Digital");

        Template::render();
    }
    public function skkoreksi()
    {
            
        Template::set('toolbar_title', "Daftar SK Dikoreksi");

        Template::render();
    }
    public function getbarier()
    {
        $this->auth->restrict($this->permissionBarier);
        $this->load->model('settings/settings_model');
        Template::set('toolbar_title', "Generete Token Barier Aplikasi DS");

        $TokenBarier = trim($this->settings_lib->item('site.barierds'));
        Template::set('TokenBarier', $TokenBarier);
        Template::render();
    }
    public function gettoken()
    {
        $this->auth->restrict($this->permissionToken);
        Template::set('toolbar_title', "Permintaan Token Digital Signature");
        Template::render();
    }
    public function uploadsk()
    {
        $this->auth->restrict($this->permissionEdit);
        
        Template::set('toolbar_title', "Upload SK Baru");
        Template::render();
    }
    function act_uploadsk(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }   
        $this->form_validation->set_rules($this->sk_ds_model->get_validation_rules());
        $this->form_validation->set_rules('nip_sk','Pemilik SK','required|max_length[18]');
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
        $msg = "";  
        $post = $this->input->post();
        $id_pegawai_ttd = $this->input->post('id_pegawai_ttd');
        $nip_sk = $this->input->post('nip_sk');
        $nomor_sk = $this->input->post('nomor_sk');
        $kategori = $this->input->post('kategori');
        
        $direktori = trim($this->settings_lib->item('site.pathsk'));
            // file SK
            $nama_sk = "";
            if (isset($_FILES['nama_sk']) && $_FILES['nama_sk']['name']) {
                $uploadData = handle_upload('nama_sk',$direktori);
                if (isset($uploadData['error']) && !empty($uploadData['error']))
                 {
                    $tipefile = $_FILES['nama_sk']['type'];
                    log_activity($this->input->post('nip'), 'Gagal error nama_sk: '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'upload SK');
                 }else{
                    $nama_sk = $uploadData['data']['file_name'];
                 }
            }
             
            if($nama_sk ==""){
                $status = FALSE;
                $msg = "upload gagal, silahkan pilih file";
                $response['msg'] = "
                    <div class='alert alert-block alert-error fade in'>
                        <a class='close' data-dismiss='alert'>&times;</a>
                        <h4 class='alert-heading'>
                            Error
                        </h4>
                        ".msg."
                    </div>
                    ";
                    echo json_encode($response);
                    exit();
            }
            if($nip_sk ==""){
                $status = FALSE;
                $msg = "upload gagal, silahkan isi pemilik SK";
            }
            if($id_pegawai_ttd ==""){
                $status = FALSE;
                $msg = "upload gagal, silahkan isi penandatangan SK";
            }
            if($nama_sk != ""){
                
                //$data = $this->registrasi_model->prep_data($this->input->post());
                $fileupload = $direktori.$nama_sk;
                $base64data = "";
                if (file_exists($fileupload)) {
                    $base64data = chunk_split(base64_encode(file_get_contents($fileupload)));
                }
                if($base64data != ""){
                    $data["teks_base64"]      = $base64data;    
                }
                $data['id_file']              = md5($nama_sk.date("his"));
                $data['waktu_buat']           = date("Y-m-d");
                $data['id_pegawai_ttd']       = $id_pegawai_ttd;
                $data['nip_sk']               = $nip_sk;
                $data['kategori']             = $kategori;
                $data['nomor_sk']             = $nomor_sk;
                $data['ds_ok']                = 1; // jenis SK digital
                $data['is_signed']            = 0; // siap tandatangan
                $data['is_corrected']         = 1; // anggap sudah dikoreksi tinggal tanda tangan
                
                $id = $this->sk_ds_model->insert($data);
                if($id){
                    $status = true;
                    $msg = "Upload berhasil";   
                }else{
                    $status = false;
                    $msg = "Upload gagal";   
                }
            } 
        $data_json = array(
            'status' => $status,
            'msg' => $msg

        );

        $json_data = json_encode($data_json);
        echo $json_data;
    }
    /**
     * Allows editing of sk ds data.
     *
     * @return void
     */
    public function edit($id_file)
    {
        if (empty($id_file)) {
            Template::set_message(lang('sk_ds_invalid_id'), 'error');

            redirect(SITE_AREA . '/sk/sk_ds');
        }
         
        
        
        Template::set('sk_ds', $this->sk_ds_model->find($id));

        Template::set('toolbar_title', "Koreksi SK");
        Template::render();
    }
    
    public function readsk($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        $ses_nip = trim($this->auth->username());
        $file_name = $id.".pdf";
        // $b64 = $data->teks_base64;
        $b64 = $this->getBase64File($id);
        $id_penandatangan = trim($data->id_pegawai_ttd);
        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);
        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($ses_nip));
        Template::set("pegawai_login",$pegawai_login);
        
        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
        Template::set('foto_pegawai', $foto_pegawai);
        // add log riwayat
        $this->save_riwayat_ds($id,$ses_nip,"Membuka file oleh Penandatanan","");
        // end log riwayat
        // korektor
        $akorektor = $this->sk_ds_korektor_model->find_all_korektor($id);
        Template::set("korektor",$akorektor);
        $url = $url_sk.$file_name;
        if ($data->is_signed){
            $bucket = "simpeg";
            $key = $data->lokasi_file;
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $url = $aws->preSignUrl($bucket,$key);
        }

        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64);
        Template::set("url_sk",$url);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function generatespesimen()
    {
        $this->load->model('sk_ds/file_ttd_model');
        $id = $this->uri->segment(5);
        $records = $this->file_ttd_model->find_all($id);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $file_name = $record->nip.".png";
                $b64 = trim($record->base64ttd);
                $bin = base64_decode($b64, true);
                $direktorispesimen = trim($this->settings_lib->item('site.pathspesimen'));
                if (file_exists($direktorispesimen.$file_name)) {
                   unlink($direktorispesimen.$file_name);
                }
                echo $direktorispesimen.$file_name;
                if(file_put_contents(trim($direktorispesimen).$file_name, $bin)){
                    echo "berhasil<br>";
                }else{
                    echo "Gagal<br>";
                }

            }
        endif;
        die("generate selesai");
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function lihatsk($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $file_name = $id.".pdf";
        $b64 = $data->teks_base64;
        $b64_sign = $data->teks_base64_sign;
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);

        # Decode the Base64 string, mang sure that it contains only valid characters
        $bin = base64_decode($b64, true);
        # Perform a basic validation to make sure that the result is a valid PDF file
        # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
        # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //die(trim($direktori).$file_name);
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);    
        
        
        // create file pdf sign
        /*
        $bin = base64_decode($b64_sign, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        if (file_exists($direktori.$id."_signed.pdf")) {
           unlink($direktori.$direktori.$id."_signed.pdf");
        } 
        file_put_contents($direktori.$id."_signed.pdf", $bin);  
        // end file pdf sign
        */

        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);

        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
        Template::set('foto_pegawai', $foto_pegawai);

        // korektor
        $akorektor = $this->sk_ds_korektor_model->find_all_korektor($id);
        Template::set("korektor",$akorektor);

        
        Template::set("collapse",true);
        Template::set("data",$data);
        if($b64_sign != "")
            Template::set("b64",$b64_sign);
        else
            Template::set("b64",$b64);

        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function readsksign($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $file_name = $id."_signed.pdf";
        $b64 = $data->teks_base64_sign;
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);

        
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        //die(trim($direktori).$file_name);
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        
          
        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
        Template::set('foto_pegawai', $foto_pegawai);


        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64);
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Lihat SK");
        Template::render();
    }
    public function downloadsk($id)
    {
        $id     = $this->uri->segment(5);
        $data   = $this->sk_ds_model->find($id);
        $this->load->helper('download');
        $direktori  = trim($this->settings_lib->item('site.pathsk'));
        $file_name  = $id.".pdf";
        $base_64    = $data->teks_base64;
        if($data->is_signed == 1){
            $file_name = $id."_signed.pdf";
            $base_64 = $data->teks_base64_sign;
        }
        if (file_exists($direktori.$file_name)) {
            force_download($direktori.$file_name, NULL);
        }else{
            $this->download_aws($id);
        }
    }
   
    public function downloadsksigned($id)
    {
        $id = $this->uri->segment(5);
        $data = $this->sk_ds_model->find($id);
        $this->load->helper('download');
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $id."_signed.pdf";

        if (file_exists($direktori.$file_name)) {
            force_download($direktori.$file_name, NULL);
        }else{
            $this->download_aws($id);
            
        }
    }

    public function download_aws($id)
    {
        $file_ds = $this->sk_ds_model->find_by("id_file",$id);
        $this->load->library('Api_s3_aws');
        $aws = new Api_s3_aws;
        $key = $file_ds->lokasi_file;
        $file_name = $file_ds->id_file."_signed.pdf";
        return $aws->downloadFile("simpeg",$key,$file_name);
    }
    
    public function validasisk($id)
    {
        $this->auth->restrict($this->permissionValidasi);
        $id = $this->uri->segment(5);
        $NIP = trim($this->auth->username());
        $data = $this->sk_ds_korektor_model->find_all_file_nip($NIP,$id);
        $file_name = $id.".pdf";
        $b64 = $data[0]->teks_base64;
        $korektor_ke = isset($data[0]->korektor_ke) ? $data[0]->korektor_ke : "";
        $id_penandatangan = trim($data[0]->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);

        
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $url_sk = trim($this->settings_lib->item('site.urlsk'));
        

        if (file_exists($direktori.$file_name)) {
               unlink($direktori.$file_name);
        }
        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
        Template::set('foto_pegawai', $foto_pegawai);
        # Decode the Base64 string, making sure that it contains only valid characters
        $bin = base64_decode($b64, true);
        # Perform a basic validation to make sure that the result is a valid PDF file
        # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
        # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }
        file_put_contents(trim($direktori).$file_name, $bin);        
        
        
        if (!file_exists($direktori.$file_name)) {
            file_put_contents(trim($direktori).$file_name, $bin);    
           //unlink($direktori.$file_name);
        }
        // add log riwayat
        $this->save_riwayat_ds($id,$NIP,"Membuka File SK untuk Koreksi","");
        // end log riwayat
        $data_file = file_get_contents($direktori.$file_name);
        $b64 = base64_encode($data_file);
        // view riwayat catatan
        $catatans = $this->view_riwayat_ds($id);
        Template::set("catatans",$catatans);

        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("b64",$b64);
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Validasi SK");
        Template::render();
    }
    public function vskreview()
    {
        $this->auth->restrict($this->permissionValidasi);
        $aid_file = $this->input->get("id_file");
        if(count($aid_file)<=0){
            die("file blm dipilih");
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $this->auth->restrict($this->permissionValidasi);
        $NIP    = trim($this->auth->username());
        $data   = $this->sk_ds_korektor_model->find_all_file_nip_id($NIP,$aid_file[0]);
        $file_name          = $data[0]->id_file.".pdf";
        $b64                = $data[0]->teks_base64;
        $korektor_ke        = isset($data[0]->korektor_ke) ? $data[0]->korektor_ke : "";
        $id_penandatangan   = trim($data[0]->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);

        $direktori  = trim($this->settings_lib->item('site.pathsk'));
        $url_sk     = trim($this->settings_lib->item('site.urlsk'));
        // add log riwayat
        $this->save_riwayat_ds($id,$NIP,"Membuka File SK untuk praf","");
        // end log riwayat
        $data_file = file_get_contents($direktori.$file_name);
        $b64 = base64_encode($data_file);
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);
        // view riwayat catatan
        $catatans = $this->view_riwayat_ds($id);
        Template::set("catatans",$catatans);
        Template::set("collapse",true);
        Template::set("data",$data);
        Template::set("aid_file",json_encode($aid_file));
        Template::set("jml_dokumen",count($aid_file));
        Template::set("b64",$b64);
        Template::set("url_sk",$url_sk.$file_name);
        Template::set('toolbar_title', "Paraf SK");
        Template::render();
    }
    private function checkFile($b64,$file_full_dir){
        # Decode the Base64 string, making sure that it contains only valid characters
        $bin = base64_decode($b64, true);
        # Perform a basic validation to make sure that the result is a valid PDF file
        # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
        # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }
        if (!file_exists($file_full_dir)) {
            file_put_contents(trim($file_full_dir, $bin));
           //unlink($direktori.$file_name);
        }
    }
    private function getFile($id_file){
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $url = trim($this->settings_lib->item('site.urlsk'));
        $file_name = $id_file.".pdf";
        $filesigned = $url."_signed.pdf";
        if (file_exists($direktori.$id_file."_signed.pdf")) {
           $file_name = $id_file."_signed.pdf";
        }
        return $url.$file_name;
    }
    private function getBase64File($id_file){
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $direktori.$id_file.".pdf";
        $filesigned = $direktori.$id_file."_signed.pdf";
        if (file_exists($filesigned)) {
           $file_name = $filesigned;
        }
        $data = file_get_contents($file_name);
        return base64_encode($data);
    }
    public function getdata(){
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
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nomor_sk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['kategori_jabatan']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
            if($filters['kategori_sk']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_blm_new($this->UNOR_ID,false,$PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_blm_ttd_new($this->UNOR_ID,false,$PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = "<input name='id_file[]' class='mycxk' type='checkbox' value='".$record->id_file."'>";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                //$row []  = $record->KATEGORI_JABATAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='#' url='".$this->getFile($record->id_file)."' data-toggle='tooltip' title='lihat dokumen' class='btn btn-sm btn-info popup'><i class='fa fa-eye'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' url='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='Tanda Tangan Dokumen' class='btn btn-sm btn-success'><i class='fa fa-key'></i> </a>";
                
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdatadash($PNS_ID){
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
        
        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_blm_new($this->UNOR_ID,false,$PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_blm_ttd_new($this->UNOR_ID,false,$PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                //$row []  = $record->KATEGORI_JABATAN;
                 

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdataall(){
        $this->auth->restrict($this->permissionViewall);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/sk_ds/viewall');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
                
            }
            if($filters['nomor_sk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
             
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            // if($filters['textqrcode']){
            //     $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            // }
            if($filters['id_pegawai_ttd']){
                $this->sk_ds_model->where('id_pegawai_ttd',$filters['id_pegawai_ttd']); 
            }
            if($filters['id_file']){
                $this->sk_ds_model->where('tbl_file_ds.id_file',$filters['id_file']); 
            }
            if($filters['is_signed'] != ""){
                //if($filters['is_signed'] == "1" or $filters['is_signed'] == "3"){
                $this->sk_ds_model->where('tbl_file_ds.is_signed',$filters['is_signed']);     
                
            }
            if($filters['is_corrected'] != ""){
                $this->sk_ds_model->where('tbl_file_ds.is_corrected',$filters['is_corrected']);     
            }
            if($filters['ds_ok']){
                if($filters['ds_ok'] != ""){
                    if($filters['ds_ok'] == "1"){
                    $this->sk_ds_model->where('tbl_file_ds.ds_ok',$filters['ds_ok']);     
                    }
                    if($filters['ds_ok'] == "-"){
                        $this->sk_ds_model->where('tbl_file_ds.ds_ok != 1 or tbl_file_ds.ds_ok is null');     
                    }
                }
            }else{
                $this->sk_ds_model->where('tbl_file_ds.ds_ok',1);
            }
            if($filters['kategori_sk']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_admin($this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_admin($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $tgl_tandatangan = "";
                if($record->tgl_tandatangan){
                    $tgl_tandatangan = "<b>Ttd: </b>".$record->tgl_tandatangan;
                }
                $row = array();
                $row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                // $row []  = $this->tte_model->getKategory($record->kategori)->nama_proses;
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk.$tgl_tandatangan;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                if($record->ds_ok == "1"){
                    $row []  = "DS";    
                }else{
                    $row []  = "Manual";
                }
                
                $btn_actions = array();
                if($record->is_signed == "1"){
                    $btn_actions  [] = "<a href='#' url1='".base_url()."admin/sk/sk_ds/lihatsksign/".$record->id_file."' urls='".base_url()."admin/sk/sk_ds/readdraftsk/".$record->id_file."' data-toggle='tooltip' title='Bandingkan dokumen draft dan Tandatangan' class='btn btn-sm btn-info popupurl'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                $btn_actions [] = "<a href='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' url='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='Lihat detil' class='btn btn-sm btn-success'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                
                if($record->is_signed == "1"){
                    $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/lihatsksign/".$record->id_file."' url='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='Lihat SK sudah tandatangan' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                if($record->is_corrected == "3"){
                    $btn_actions [] = "<a href='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='SK Dikoreksi' class='btn btn-sm btn-danger'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
     
    public function getdataskdikoreksi(){
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
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
             
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['kategori_jabatan']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_dikoreksi($this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_dikoreksi($this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                $row []  = $this->tte_model->getKategory($record->kategori)->nama_proses;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL;
                //$row []  = $record->KATEGORI_JABATAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='#' url='".$this->getFile($record->id_file)."' data-toggle='tooltip' title='lihat dokumen' class='btn btn-sm btn-info popup'><i class='fa fa-eye'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' url='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='Lihat Detil' class='btn btn-sm btn-success'><i class='fa fa-key'></i> </a>";
                
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    
    public function getdatasudahttd(){
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
        $NIP = trim($this->auth->username());
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

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
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
             
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            if($filters['ch_nosk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
            if($filters['kategori_jabatan']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
            if($filters['kategori_sk']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_sudah_ttd($this->UNOR_ID,false,$PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_sudah_ttd($this->UNOR_ID,false,$PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                $btn_actions = array();
                $btn_actions  [] = "<a href='#' url='".$this->getFile($record->id_file)."' data-toggle='tooltip' title='lihat dokumen' class='btn btn-sm btn-info popup'><i class='fa fa-eye'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' url='".base_url()."admin/sk/sk_ds/readsk/".$record->id_file."' data-toggle='tooltip' title='Lihat Detil' class='btn btn-sm btn-success'><i class='fa fa-key'></i> </a>";
                
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";                
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdatavalidasi_dash($PNS_ID){
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
             
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $record->kategori;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                //$row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                //$row []  = $record->KATEGORI_JABATAN;
                
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdatavalidasi(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nomor_sk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            
            if($filters['kategori_jabatan']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
            // if($filters['chkqr']){
            //     $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            // }
            if($filters['kategori_sk']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = "<input name='id_file[]' class='mycxk' type='checkbox' value='".$record->id_table."'>";
                //$row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $this->tte_model->getKategory($record->kategori)->nama_proses;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                //$row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                //$row []  = $record->KATEGORI_JABATAN;
                $btn_actions = array();
                $btn_actions  [] = "<a href='#' url='".base_url()."admin/sk/sk_ds/viewdoc/".$record->id_file."' data-toggle='tooltip' title='Lihat File' class='btn btn-sm btn-info popup'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                // $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/validasisk/".$record->id_file."' data-toggle='tooltip' title='Lihat dan Paraf dokumen ini' class='btn btn-sm btn-success'><i class='glyphicon glyphicon-edit'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";   
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_preview(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $aid_file = $this->input->post('aid_file');
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $aid_file  = $this->input->post("search");
        if($aid_file){
            $filters = array();
            foreach($aid_file as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }
        $a_id_file  = $this->input->post("search");
        $this->db->start_cache();
            $this->sk_ds_model->where_in('t.id',json_decode($a_id_file));     
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $this->tte_model->getKategory($record->kategori)->nama_proses;
                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                $btn_actions = array();
                $btn_actions  [] = "<a href='#' url='".$this->getFile($record->id_file)."' data-toggle='tooltip' title='lihat dokumen' class='btn btn-sm btn-success preview_file'><i class='fa fa-eye'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";   
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_sudah_validasi(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

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
            if($filters['unit_id_key']){
                $this->db->group_start();
                $this->db->where('vw."ID"',$filters['unit_id_key']);    
                $this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);   
                $this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);   
                $this->db->group_end();
            }
            if($filters['nama_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");    
                $this->sk_ds_model->or_like('upper("nama_pemilik_sk")',strtoupper($filters['nama_key']),"BOTH");    
                $this->db->group_end();
            }
            if($filters['nip_key']){
                $this->db->group_start();
                $this->sk_ds_model->like('upper(pegawai."NIP_BARU")',strtoupper($filters['nip_key']),"BOTH"); 
                $this->sk_ds_model->or_like('upper("nip_sk")',strtoupper($filters['nip_key']),"BOTH");    
                $this->db->group_end();
            }
             
            if($filters['golongan_key']){
                $this->sk_ds_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));   
            }
            if($filters['nomor_sk']){
                $this->sk_ds_model->like('upper(tbl_file_ds."nomor_sk")',strtoupper($filters['nomor_sk']),"BOTH"); 
            }
            if($filters['kategori_jabatan']){
                $this->sk_ds_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']); 
            }
            // if($filters['chkqr']){
            //     $this->sk_ds_model->where('tbl_file_ds.lokasi_file',$filters['textqrcode']); 
            // }
            if($filters['kategori_sk']){
                $this->sk_ds_model->where('tbl_file_ds.kategori',$filters['kategori_sk']); 
            }
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->sk_ds_model->count_all_sudah_validasi_with_pns_id($PNS_ID,$this->UNOR_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->sk_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->sk_ds_model->order_by("kategori",$order['dir']);
            }
            if($order['column']==3){
                $this->sk_ds_model->order_by("nomor_sk",$order['dir']);
            }
            if($order['column']==4){
                $this->sk_ds_model->order_by("NAMA_UNOR",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->sk_ds_model->limit($length,$start);
        $records=$this->sk_ds_model->find_all_sudah_validasi_with_idpegawai($PNS_ID,$this->UNOR_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                if($record->NAMA != ""){
                    $row []  = "<b>".$record->NAMA."</b><br>".$record->NIP_BARU;
                }else{
                    $row []  = "<b>".$record->nama_pemilik_sk."</b><br>".$record->nip_sk;
                }
                $row []  = $this->tte_model->getKategory($record->kategori)->nama_proses;

                $row []  = $record->nomor_sk."<br>".$record->tgl_sk;
                //$row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_UNOR_FULL != "" ? $record->NAMA_UNOR_FULL : $record->unit_kerja_pemilik_sk;
                $btn_actions = array();
                $btn_actions  [] = "<a url='".$this->getFile($record->id_file)."' data-toggle='tooltip' title='lihat dokumen' class='btn btn-sm btn-info popup'><i class='fa fa-eye'></i> </a>";
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/validasisk/".$record->id_file."' data-toggle='tooltip' title='Lihat SK' class='btn btn-sm btn-success'><i class='glyphicon glyphicon-edit'></i> </a>";
                
                $btn_actions  [] = "<a href='".base_url()."admin/sk/sk_ds/downloadsk/".$record->id_file."' data-toggle='tooltip' title='Download SK' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-download'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";   
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_antrian_validasi(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
            // redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = $this->vw_ds_antrian_korektor_model->count_all($PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->vw_ds_antrian_korektor_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->vw_ds_antrian_korektor_model->order_by("jumlah",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->vw_ds_antrian_korektor_model->limit($length,$start);
        $records=$this->vw_ds_antrian_korektor_model->find_all($PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->kategori;
                $row []  = $record->jumlah;
                //$row []  = $record->NAMA_UNOR_FULL;
                
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_antrian_ttd(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
        }
        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        
        
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = $this->vw_ds_antrian_ttd_model->count_all($PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            
            if($order['column']==2){
                $this->vw_ds_antrian_ttd_model->order_by("jumlah",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->vw_ds_antrian_ttd_model->limit($length,$start);
        $records=$this->vw_ds_antrian_ttd_model->find_all($PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->kategori;
                $row []  = $record->jumlah;
                //$row []  = $record->NAMA_UNOR_FULL;
                
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_antrian_detil(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
        }
        
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $PNS_ID  = $this->input->post("penandatangan");
        $this->db->start_cache();
        
        
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total = $this->vw_ds_antrian_ttd_model->count_all($PNS_ID);
        $orders = $this->input->post('order');
        foreach($orders as $order){
            
            if($order['column']==2){
                $this->vw_ds_antrian_ttd_model->order_by("jumlah",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->vw_ds_antrian_ttd_model->limit($length,$start);
        $records=$this->vw_ds_antrian_ttd_model->find_all($PNS_ID);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->kategori;
                $row []  = $record->jumlah;
                //$row []  = $record->NAMA_UNOR_FULL;
                
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function getdata_resume(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            // Template::set_message("Hanya request ajax", 'error');
        }

        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
             $this->db->start_cache();
                if($filters['nama_key']){
                    $this->db->group_start();
                    $this->vw_ds_resume_ttd_model->like('upper(nama_penandatangan)',strtoupper($filters['nama_key']),"BOTH");    
                    $this->db->group_end();
                }
            $this->db->stop_cache();
        }

       
        $output=array();
        $output['draw']=$draw;
        $total = $this->vw_ds_resume_ttd_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==2){
                $this->vw_ds_resume_ttd_model->order_by("jml_siap_ttd",$order['dir']);
            }
            if($order['column']==3){
                $this->vw_ds_resume_ttd_model->order_by("jumlah",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->vw_ds_resume_ttd_model->limit($length,$start);
        $records=$this->vw_ds_resume_ttd_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->nama_penandatangan;
                $row []  = "<a class='show-modal' href='".base_url()."admin/sk/sk_ds/listttd/".$record->id_pegawai_ttd."'>".$record->jml_siap_ttd."</a>";
                $row []  = "<a class='show-modal' href='".base_url()."admin/sk/sk_ds/listkoreksi/".$record->id_pegawai_ttd."'>".$record->jml_siap_koreksi."</a>";
                $row []  = "<a class='show-modal' href='".base_url()."admin/sk/sk_ds/listantrianttd/".$record->id_pegawai_ttd."'>".$record->jumlah."</a>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    public function tandatangan($status)
    {
        $this->auth->restrict($this->permissionttd);
        $this->load->library('signature/Dsignclient');
        $esign = new Dsignclient();
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $id_file = $this->input->post('id_file');
        $usercert = trim($this->input->post('username'));
        $passphrase = $this->input->post('passphrase');
        $catatan = $this->input->post('catatan');
        $ses_nip = trim($this->auth->username());
        $status  = $this->uri->segment(5);
        $success = false;
        $jmldok = 0;
        // status ok
        if($status=="1"){
            // kirim file DS untuk paraf
            $data = $this->sk_ds_model->find_by("id_file",$id_file);
            $halaman_ttd    = $data->halaman_ttd;
            $show_qrcode    = $data->show_qrcode;
            $letak_ttd      = $data->letak_ttd;
            // create pdf jika belum ada
            $file_name  = $direktori.$id_file.".pdf";
            $filesigned = $direktori.$id_file."_signed.pdf";
            if (file_exists($filesigned)) {
               $file_name = $filesigned;
            }
            // end create pdf
            $return = $esign->sign($usercert,$passphrase,$file_name,$filesigned,'visible',$halaman_ttd);
            if($return['id_dokumen'] != ""){
                $jmldok++;
                $msg = "Berhasil Tandatangan";
                // add log riwayat
                $this->updaetStatusTandatangan($id_file);
                $this->save_log($id_file,$usercert,"Berhasil Tandatangan",2);
                $this->save_riwayat_ds($id_file,$ses_nip,"Berhasil Tandatangan dari read SK","");
                $success = true;
            }else{
                // add log riwayat
                $this->save_log($id_file,$usercert,$return['error'],1);
                $this->save_riwayat_ds($id_file,$ses_nip,"Gagal tandatangan dokumen dari readsk","");
                $msg = $return['error'];
            }
        }
        // jika statusnya dikembalikan
        if($status=="3"){
            // jika tidak ada korektor selanjutnya
            $data_updates = array(
                'is_signed'     => 3,
                'is_corrected'  => 3,
                'is_returned'   => 1,
                'catatan'       => $catatan
            );
            $this->sk_ds_model->update($file_id,$data_updates);
            log_activity($this->auth->user_id(),"Sk dikembalikan karena ada koreksi " . ': ' . $file_id . ' Catatan : '.$catatan. $this->input->ip_address(), 'sk_ds');
            // add log riwayat
            $this->save_riwayat_ds($file_id,$ses_nip,"Tandatangan SK (SK dikembalikan)",$catatan);
            // end log riwayat
            $msg = "Dokumen dikembalikan";
        }
        $response ['success']= $success;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    public function tandatangan_all()
    {
        $this->auth->restrict($this->permissionttd);
        $this->load->library('signature/Dsignclient');
        $esign = new Dsignclient();
        $direktori  = trim($this->settings_lib->item('site.pathsk'));
        $aid_file   = $this->input->post('id_file');
        $ses_nip    = trim($this->auth->username());
        $usercert   = $this->pegawai_model->find_by("NIP_BARU",trim($ses_nip))->NIK;
        $passphrase = $this->input->post('passphrase');
        $status = $this->uri->segment(5);
        $msg = "";
        $success = false;
        $jmldok = 0;
        foreach($aid_file as $id_file){
           if($status=="1"){
                // kirim file DS untuk paraf
                $data = $this->sk_ds_model->find_by("id_file",$id_file);
                $halaman_ttd    = $data->halaman_ttd;
                $show_qrcode    = $data->show_qrcode;
                $letak_ttd      = $data->letak_ttd;
                // create pdf jika belum ada
                $file_name  = $direktori.$id_file.".pdf";
                $filesigned = $direktori.$id_file."_signed.pdf";
                if (file_exists($filesigned)) {
                   $file_name = $filesigned;
                }
                // end create pdf
                $return = $esign->sign($usercert,$passphrase,$file_name,$filesigned,'visible',$halaman_ttd);
                if($return['id_dokumen'] != ""){
                    $jmldok++;
                    $msg = $jmldok." Dokumen Berhasil ditandatangan";
                    // add log riwayat
                    $this->updaetStatusTandatangan($id_file);
                    $this->save_log($id_file,$usercert,"Berhasil Tandatangan",2);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Berhasil Tandatangan dari read SK","");
                    $success = true;
                }else{
                    // add log riwayat
                    $this->save_log($id_file,$usercert,$return['error'],1);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Gagal tandatangan dokumen dari readsk","");
                    $msg = $return['error'];
                }
            }

        }
        $response ['success']= $success;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    // paraf dari view
    public function prosesvalidasi($status)
    {
        $this->auth->restrict($this->permissionValidasi);
        $this->load->library('signature/Dsignclient');
        $esign = new Dsignclient();
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $NIP = $this->input->post('NIP');
        $aid_file = json_decode($this->input->post('aid_file'));
        $usercert = trim($this->input->post('username'));
        $passphrase = $this->input->post('passphrase');
        $catatan    = $this->input->post('catatan');
        $ses_nip    = trim($this->auth->username());
        $success = false;
        $jmldok = 0;
        foreach($aid_file as $id_korektor){
            // get data detil
            $datadetil = $this->sk_ds_korektor_model->find($id_korektor);
            $id_file = $datadetil->id_file;
            // status ok
            if($status=="1"){
                // kirim file DS untuk paraf
                $data = $this->sk_ds_model->find_by("id_file",$id_file);
                $halaman_ttd = $data->halaman_ttd;
                $show_qrcode = $data->show_qrcode;
                $letak_ttd = $data->letak_ttd;
                // create pdf jika belum ada
                $file_name = $direktori.$id_file.".pdf";
                $filesigned = $direktori.$id_file."_signed.pdf";
                if (file_exists($filesigned)) {
                   $file_name = $filesigned;
                }
                // end create pdf
                $return = $esign->sign($usercert,$passphrase,$file_name,$filesigned,'invisible',$halaman_ttd);
                if($return['id_dokumen'] != ""){
                    // update status paraf menjadi sudah di paraf
                    $this->updateStatusVerifikator($id_korektor);
                    $jmldok++;
                    $msg = $jmldok." Dokumen sudah diparaf";
                    // add log riwayat
                    $this->save_log($id_file,$usercert,"Berhasil Tandatangan",2);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Berhasil Tandatangan dari ttd all","");
                    // end log riwayat
                    $jmlSiapKoreksi = $this->sk_ds_korektor_model->findCountStatusSiap($id_file);
                    // end cek file
                    if($jmlSiapKoreksi <= 0){
                        // jika sudah ada pengoreksi/paraf lagi, maka dokumen siapa ditandatangan oleh penandatangan terakhir
                        $this->updateStatusKoreksi($id_file);
                    }
                    $success = true;
                }else{
                    // add log riwayat
                    $this->save_log($id_file,$usercert,$return['error'],1);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Gagal paraf SK dari ttd all","");
                    $msg = $return['error'];
                }
            }
            // jika statusnya dikembalikan
            if($status=="3"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                $korektor_selanjutnya = $korektor_ke + 1;
                // jika tidak ada korektor selanjutnya
                $data_updates = array(
                    'is_signed'     => 3,
                    'is_corrected'  => 3,
                    'is_returned'   => 1,
                    'catatan'       => $catatan
                );
                $this->sk_ds_model->update($file_id,$data_updates);

                $data_updates = array(
                    'is_corrected'     => 3
                );
                $this->sk_ds_korektor_model->update($id_korektor,$data_updates);

                $data["is_returned"]     = 1;
                log_activity($this->auth->user_id(),"Sk dikembalikan karena ada koreksi " . ': ' . $file_id . ' Catatan : '.$catatan. $this->input->ip_address(), 'sk_ds');
                // add log riwayat
                $this->save_riwayat_ds($file_id,$ses_nip,"Paraf SK (SK dikembalikan)",$catatan);
                // end log riwayat
                $success = true;
                $msg = "Dokumen dikembalikan";
            }

        }
        $response ['success']= $success;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    // Paraf langsung dari table
    public function prosesvalidasi_all()
    {
        $this->auth->restrict($this->permissionValidasi);
        $this->load->library('signature/Dsignclient');
        $esign = new Dsignclient();
        $direktori  = trim($this->settings_lib->item('site.pathsk'));
        $aid_file   = $this->input->post('id_file');
        $ses_nip    = trim($this->auth->username());
        $usercert   = $this->pegawai_model->find_by("NIP_BARU",trim($ses_nip))->NIK;
        $passphrase = $this->input->post('passphrase');
        $status = $this->uri->segment(5);
        $msg = "";
        $success = false;
        $jmldok = 0;
        foreach($aid_file as $id_korektor){
            // get data detil
            $datadetil = $this->sk_ds_korektor_model->find($id_korektor);
            $id_file = $datadetil->id_file;
            // status ok
            if($status=="1"){
                // kirim file DS untuk paraf
                $data = $this->sk_ds_model->find_by("id_file",$id_file);
                $halaman_ttd = $data->halaman_ttd;
                $show_qrcode = $data->show_qrcode;
                $letak_ttd = $data->letak_ttd;
                // create pdf jika belum ada
                $file_name = $direktori.$id_file.".pdf";
                $filesigned = $direktori.$id_file."_signed.pdf";
                if (file_exists($filesigned)) {
                   $file_name = $filesigned;
                }
                // end create pdf
                $return = $esign->sign($usercert,$passphrase,$file_name,$filesigned,'invisible',$halaman_ttd);
                if($return['id_dokumen'] != ""){
                    // update status paraf menjadi sudah di paraf
                    $this->updateStatusVerifikator($id_korektor);
                    $jmldok++;
                    $msg = $jmldok." Dokumen sudah diparaf";
                    // add log riwayat
                    $this->save_log($id_file,$usercert,"Berhasil Tandatangan",2);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Berhasil Tandatangan dari ttd all","");
                    // end log riwayat
                    $jmlSiapKoreksi = $this->sk_ds_korektor_model->findCountStatusSiap($id_file);
                    // end cek file
                    if($jmlSiapKoreksi <= 0){
                        // jika sudah ada pengoreksi/paraf lagi, maka dokumen siapa ditandatangan oleh penandatangan terakhir
                        $this->updateStatusKoreksi($id_file);
                    }
                    $success = true;
                }else{
                    // add log riwayat
                    $this->save_log($id_file,$usercert,$return['error'],1);
                    $this->save_riwayat_ds($id_file,$ses_nip,"Gagal paraf SK dari ttd all","");
                    $msg = $return['error'];
                }
            }
            // jika statusnya dikembalikan
            if($status=="3"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                $korektor_selanjutnya = $korektor_ke + 1;
                // jika tidak ada korektor selanjutnya
                $data_updates = array(
                    'is_signed'     => 3,
                    'is_corrected'     => 3,
                    'is_returned'     => 1,
                    'catatan'     => $catatan
                );
                $this->sk_ds_model->update($file_id,$data_updates);

                $data_updates = array(
                    'is_corrected'     => 3
                );
                $this->sk_ds_korektor_model->update($id_korektor,$data_updates);

                $data["is_returned"]     = 1;
                log_activity($this->auth->user_id(),"Paraf SK " . ': ' . $file_id . ' Catatan : '.$catatan. $this->input->ip_address(), 'sk_ds');
                // add log riwayat
                $this->save_riwayat_ds($file_id,$ses_nip,"Paraf SK (SK dikembalikan)",$catatan);
                // end log riwayat
                $msg = "Berkas dikembalikan";
            }

        }
        $response ['success']= $success;
        $response ['msg']= $msg;
        echo json_encode($response);   
        exit();
    }
    private function updaetStatusTandatangan($id_file){
        $data = array();
        $data["is_signed"]          = "1"; // sudah tandatangan
        $data["tgl_tandatangan"]    = date("Y-m-d H:i:s");
        $this->sk_ds_model->update($id_file,$data);
    }
    private function updateStatusVerifikator($id){
         $data_updates = array(
            'is_corrected'     => 1
        );
        $this->sk_ds_korektor_model->update($id, $data_updates);
    }
    private function updateStatusKoreksi($file_id){
        $data_updates = array(
            'is_corrected'     => 1
        );
        $this->sk_ds_model->update($file_id,$data_updates);
        log_activity($this->auth->user_id(),"Paraf OK langsung ke penandatangan " . ': ' . $file_id . ' : ' . $this->input->ip_address(), 'sk_ds');
    }
    private function parafDs($usercert,$passphrase,$value){
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $this->load->library('signature/Dsignclient');
        $esign = new Dsignclient();
        $data = $this->sk_ds_model->find($value);
        $halaman_ttd = $data->halaman_ttd;
        $show_qrcode = $data->show_qrcode;
        $letak_ttd = $data->letak_ttd;

        $status = false;
        $msg = "";
        // create pdf jika belum ada
        $file_name = $direktori.$value.".pdf";
        $filesigned = $direktori.$value."_signed.pdf";
        if (file_exists($filesigned)) {
           $file_name = $filesigned;
        }
        // end create pdf
        $return = $esign->sign($usercert,$passphrase,$file_name,'','visible',$halaman_ttd);
        if($return == 1){
            // cek file signed
            $base64signed = "";
            if (file_exists($filesigned)) {
                $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
            }
            $status = true;
            $data = array();
            if($base64signed != ""){
                $data["teks_base64"]   = $base64signed;    
            }
            // $data["is_signed"]              = "1";
            // $data["tgl_tandatangan"]        = date("Y-m-d H:i:s");
            $this->sk_ds_model->update($value,$data);
            $jmldok++;
            $msg = $jmldok." Dokumen sudah diparaf";
            $this->save_log($value,$usercert,"Berhasil Tandatangan",2);
            // add log riwayat
            $ses_nip = trim($this->auth->username());
            $this->save_riwayat_ds($value,$ses_nip,"Berhasil Tandatangan dari ttd all","");
            // end log riwayat
            $response ['success']= true;
            $response ['msg']= "Berhasil tandatangan";
            return json_encode($response);
        }else{
            $this->save_log($value,$usercert,$return['error'],1);
            // add log riwayat
            $ses_nip = trim($this->auth->username());
            $this->save_riwayat_ds($value,$ses_nip,"Gagal paraf SK dari ttd all","");
            // end log riwayat
            $response ['success']= false;
            $response ['msg']= $return['error'];
            return json_encode($response);
        }
    }
    public function kembalikan_kepemroses()
    {
        // dari penandatangan
        $this->auth->restrict($this->permissionttd);
        $id_file = $this->input->post('id_file'); // id file DD
        $catatan = $this->input->post('catatan');
        $status = 3;
        $is_returned = 1;
        $ses_nip = trim($this->auth->username());
        // get data detil
        $datadetil = $this->sk_ds_model->find($id_file);
        $data = array();
        $return = false;
        // jika statusnya lanjutkan, maka ubah status koreksi pengoreksi selanjutnya jadi 2 (siap koreksi)
        if($datadetil->is_signed != "1"){
            
            if($status=="3"){
                $data["is_returned"]     = $is_returned;
            }

            // update data
            $data["is_corrected"]     = $status;
            $data["is_signed"]        = $status;
            $data["catatan"]          = $catatan;
            $msg = "";
            if($this->sk_ds_model->update($id_file,$data)){
                $return = true;
                $msg = "Koreksi Selesai";
                log_activity($this->auth->user_id(),"Koreksi Dokumen" . ': ' . $id_file . ' Catatan : '.$catatan . $this->input->ip_address(), 'sk_ds');
                // add log riwayat
                $this->save_riwayat_ds($id_file,$ses_nip,"Dokumen dikembalikan oleh penandatangan",$catatan);
                // end log riwayat
            }else{
                $msg = "Koreksi error ".$this->sk_ds_model->error;
            } 
        }else if($datadetil->is_signed == "1"){
            $msg = "Status Dokumen sudah tandatangan, tidak bisa dikembalikan";
            log_activity($this->auth->user_id(),"Dokumen tidak bisa dikembalikan karena sudah ttd" . ': ' . $id_file  . $this->input->ip_address(), 'sk_ds');
        }
        
        $response ['success']= $return;
        $response ['msg']= $msg;
        echo json_encode($response);    

        exit();
    }
    private function save_log($ID_FILE = "",$NIK = "",$KETERANGAN = "",$STATUS = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIK != ""){
            $data["ID_FILE"]    = $ID_FILE;
            $data["NIK"]        = $NIK;
            $data["KETERANGAN"]          = $KETERANGAN;
            $data["CREATED_DATE"]        = date("Y-m-d h:i:s");
            $data["CREATED_BY"]          = $this->auth->user_id();
            $data["STATUS"]          = $STATUS;
            
            $this->log_ds_model->insert($data);
        }
    }
    private function save_riwayat_ds($ID_FILE = "",$NIP = "",$TINDAKAN = "",$CATATAN_TINDAKAN = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIP != ""){
            $data["id_file"]    = $ID_FILE;
            $data["id_pemroses"]        = $NIP;
            $data["tindakan"]          = $TINDAKAN;
            $data["catatan_tindakan"]          = $CATATAN_TINDAKAN;
            $data["waktu_tindakan"]        = date("Y-m-d h:i:s");
            $data["akses_pengguna"]          = "Web";
            $this->ds_riwayat_model->insert($data);
        }
    }
    private function view_riwayat_ds($ID_FILE = "")
    {
        $this->ds_riwayat_model->where("id_file",$ID_FILE);
        $this->ds_riwayat_model->where("catatan_tindakan is not null");
        $this->ds_riwayat_model->where("catatan_tindakan <> ''");
        $this->ds_riwayat_model->like("tindakan","Koreksi SK","BOTH");
        // $this->ds_riwayat_model->order_by("id","asc");
        $data = $this->ds_riwayat_model->find_all();
        return $data;
    }
    public function getdatalog(){
        $this->auth->restrict($this->permissionViewlog);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/sk/sk_ds/viewlog');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

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
            
            if($filters['nik_key']){
                $this->log_ds_model->like('upper(pegawai."NIK")',strtoupper($filters['nik_key']),"BOTH");    
            }
            if($filters['status_ttd']){
                $this->log_ds_model->where('STATUS',strtoupper($filters['status_ttd'])); 
            }
            
            
        }
        
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->log_ds_model->count_all();
        $orders = $this->input->post('order');
        foreach($orders as $order){
            if($order['column']==1){
                $this->log_ds_model->order_by("pegawai.NAMA",$order['dir']);
            }
            if($order['column']==2){
                $this->log_ds_model->order_by("STATUS",$order['dir']);
            }
             
            if($order['column']==5){
                $this->log_ds_model->order_by("CREATED_DATE",$order['dir']);
            }
            if($order['column']==6){
                $this->log_ds_model->order_by("CREATED_BY",$order['dir']);
            }
            
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->log_ds_model->limit($length,$start);
        $records=$this->log_ds_model->find_all();
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<b>".$record->NAMA;
                if($record->STATUS == '2')
                    $row []  = "Berhasil";
                else
                    $row []  = 'Gagal Tandatangan';
                $row []  = $record->KETERANGAN;
                $row []  = $record->ID_FILE;
                $row []  = $record->CREATED_DATE;
                $row []  = $record->CREATED_BY;
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    public function scanqr()
    {
        $text = $this->input->get("text");
        $tabel = $this->input->get("tabel");
        Template::set('textbox', $text);
        Template::set('tabel', $tabel);
        Template::set('toolbar_title', "Scan QrCode");
        Template::render();
    }
    public function showinfo()
    {
        $valtextqrcode = $this->input->post('valtextqrcode');
        $data = $this->sk_ds_model->find_by("lokasi_file",$valtextqrcode);
        $id_penandatangan = trim($data->id_pegawai_ttd);

        $pejabat = $this->pegawai_model->find_by("PNS_ID",trim($id_penandatangan));
        Template::set("pejabat",$pejabat);
         
        # Write the PDF contents to a local file

        $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($data->nip_sk));
        Template::set("pegawai",$pegawai);

        $NIP = trim($this->auth->username());
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        Template::set("pegawai_login",$pegawai_login);
        
        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
        // korektor
        $akorektor = $this->sk_ds_korektor_model->find_all_korektor($data->id_file);
        Template::set("collapse",true);
        Template::set("data",$data);
        $output = "";
        if($id_penandatangan != ""){
            $output = $this->load->view('sk/showinfo',array('foto_pegawai'=>$foto_pegawai,'pejabat'=>$pejabat,'pegawai'=>$pegawai,'pegawai_login'=>$pegawai_login,'korektor'=>$akorektor,'data'=>$data,'id_penandatangan'=>$id_penandatangan),true);   
        }
        
        echo $output;
    }
    public function viewdoc($id_file)
    {
        if (empty($id_file)) {
            die("id kosong");
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $direktori.$id_file.".pdf";
        $filesigned = $direktori.$id_file."_signed.pdf";
        if (file_exists($filesigned)) {
           $file_name = $filesigned;
        }
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
          
        // Read the file
        @readfile($file_name);
        die();
    }
    public function readdraftsk($id_file)
    {
        if (empty($id_file)) {
            die("id kosong");
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $direktori.$id_file.".pdf";
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
          
        // Read the file
        @readfile($file_name);
        die();
    }
    public function lihatsksign($id_file)
    {
        if (empty($id_file)) {
            die("id kosong");
        }
        $direktori = trim($this->settings_lib->item('site.pathsk'));
        $file_name = $direktori.$id_file."_signed.pdf";
        
        if (file_exists($file_name)){
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file_name . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            
            // Read the file
            @readfile($file_name);
        } else {
            $file_ds = $this->sk_ds_model->find_by("id_file",$id_file);
            $bucket = "simpeg";
            $key = $file_ds->lokasi_file;
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $aws->viewdoc($key,$bucket);
        }
        die();
    }
}