<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Digital_signature extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
	$this->load->model('pegawai/ropeg_model');
       
    }
     protected $methods = [
			'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
			'sub_get' => ['level' => 10, 'limit' => 10],
    ];
	public function resume_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $penandatangan = $this->get('penandatangan');

        $jml_blm_koreksi = 0;
        $jml_sdh_koreksi = 0;
        $jml_blm_ttd = 0;
        $jml_sdh_ttd = 0;

        $jml_blm_ttd = $this->sk_ds_model->count_all_api("",true,$penandatangan);
        $jml_sdh_ttd = $this->sk_ds_model->count_all_sdhttd_api("",true,$penandatangan);


        $output = array(
            'jml_blm_koreksi' => $jml_blm_koreksi,
            'jml_sdh_koreksi'=>$jml_sdh_koreksi,
            'jml_blm_ttd'=>$jml_blm_ttd,
            'jml_sdh_ttd'=>$jml_sdh_ttd
        );
        $this->response($output, REST_Controller::HTTP_OK);

    }
	// belum ttd
	public function list_get(){

        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("pegawai/pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
		
		$output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $penandatangan = $this->get('penandatangan');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');
        
        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($penandatangan));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";

        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
		$filter_satkers = array();
		if(sizeof($satkers)==0){ // has ALL PRIV
			$filter_satkers[] = $unor_id;
		}
		else {
			$found_priv = false;
			foreach($satkers as $satker){
				if($satker == $unor_id){
					$found_priv = true;
				}
			}
			if(!$found_priv){
				$output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
				return;
			}	
			else {
				$filter_satkers[] = $unor_id;
			}
		}
        $total= $this->sk_ds_model->count_all_api("",true,$PNS_ID);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_api("",true,$PNS_ID);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function listsudahttd_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("pegawai/pegawai_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $penandatangan = $this->get('penandatangan');
        $start = (int)$this->get('start');
        $limit = $this->get('limit');

        $this->pegawai_model->select("PNS_ID,NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($penandatangan));
        $PNS_ID = isset($pegawai_login->PNS_ID) ? $pegawai_login->PNS_ID : "";
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total= $this->sk_ds_model->count_all_sdhttd_api("",true,$PNS_ID);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_sdh_ttd_api("",true,$PNS_ID);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function listbelum_koreksi_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $korektor = $this->get('korektor'); // nip korektor
        $lokasi_file = $this->get('lokasi_file'); // lokasi file
        $limit = $this->get('limit');
         
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        if($lokasi_file != ""){
            $this->sk_ds_model->where("lokasi_file",$lokasi_file);   
        }
        $total= $this->sk_ds_model->count_all_api_blmkoreksi($korektor,"",true);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        if($lokasi_file != ""){
            $this->sk_ds_model->where("lokasi_file",$lokasi_file);   
        }
        $datas = $this->sk_ds_model->find_all_api_blm_koreksi($korektor,"",true);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function listsudah_koreksi_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $appKeyData = $this->getApplicationKey();
        //print_r($appKeyData);
        $satkers = array();
        if($appKeyData->satker_auth){
            $satkers = explode(',',$appKeyData->satker_auth);
        }
        
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $unor_id = $this->get('unor_id');
        $start = (int)$this->get('start');
        $korektor = $this->get('korektor'); // nip korektor
        //die("koreksor");
        $limit = $this->get('limit');
         
        if ($start === NULL)
        {
           $start = 0;
        }
        else {
            if($start<0){
                $output['msg'] = 'Parameter start harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
        }

        if ($limit === NULL)
        {
           $limit = 10;
        }
        else {
            if($limit==-1){
                // no problem
            }
            else if($limit>1000){
                $output['msg'] = 'Parameter limit maksimal 1000 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }
            else if($limit<0){
                $output['msg'] = 'Parameter limit harus >=0 ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
            }

        }
        
        $filter_satkers = array();
        if(sizeof($satkers)==0){ // has ALL PRIV
            $filter_satkers[] = $unor_id;
        }
        else {
            $found_priv = false;
            foreach($satkers as $satker){
                if($satker == $unor_id){
                    $found_priv = true;
                }
            }
            if(!$found_priv){
                $output['msg'] = 'Tidak ada akses untuk pegawai satker ';
                $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code 
                return;
            }   
            else {
                $filter_satkers[] = $unor_id;
            }
        }
        $total= $this->sk_ds_model->count_all_api_sdhkoreksi($korektor,"",true);
        if($limit==-1){

        }
        else {
            $this->db->limit($limit,$start);
        }
        $datas = $this->sk_ds_model->find_all_api_sdh_koreksi($korektor,"",true);
        if(empty($datas))
            $datas = null;
        $this->db->flush_cache();
         $output = array(
            'success' => true,
            'total'=>$total,
            'data'=>$datas
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function detail_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $id_file = $this->input->get('id_file');
        $file_name = $id_file.".pdf";
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($id_file == NULL)
        {
            $output['msg'] = 'Parameter id_file harus diisi';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data = $this->sk_ds_model->find($id_file);
        $b64 = isset($data->teks_base64) ? $data->teks_base64 : "";
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);  

        $output = array(
            'success' => true,
            'data'=>$data
        );
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function cekfile_get(){
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $id_file = $this->input->get('id_file');
        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";
        $ttd = $this->input->get('ttd') ? $this->input->get('ttd') : "-";
        $file_name = $id_file.".pdf";
        $output = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($id_file == NULL)
        {
            $output['msg'] = 'Parameter id_file harus diisi';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $data = $this->sk_ds_model->find($id_file);
        $b64 = isset($data->teks_base64) ? $data->teks_base64 : "";
        $id_pegawai_ttd = isset($data->id_pegawai_ttd) ? $data->id_pegawai_ttd : "";
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') !== 0) {
          throw new Exception('Missing the PDF file signature');
        }

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        file_put_contents(trim($direktori).$file_name, $bin);  

        $output = array(
            'success' => true,
            'data'=>"not display"
        );
        // add log riwayat
        $catatan = "Membuka file SK oleh korektor";
        if($ttd == "1"){
            $catatan = "Membuka file SK oleh penandatangan";
        }
        $this->save_riwayat_ds($id_file,$ses_nip,$catatan,"");
        // end log riwayat
        $this->response($output, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function login_token_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $login = $this->input->post('login');
        $password = $this->input->post('password');
        if($login != "" and $password != ""){
            $token = "";
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $returnlogin  = $ClassApidigital->getTokenBarier($login,$password);
            $return = json_decode($returnlogin);
            if($return->status == "200"){
                $status = true;
                $token = $return->data->token;
                $msg = "Login Berhasil";
            }else{
                $status = false;
                $msg = $return->message;
                $response ['status']= $status;
                $response ['msg']= $msg;
               // $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
            }
             
        }else{
            $status = false;
            $msg = "Lengkapi data user dan password";
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['token'] = $token;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function dapatkantoken_post()
    {
        date_default_timezone_set("Asia/Bangkok");
         
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if ($username == "") {
            $response['success'] = false;
            $response['msg'] = "Silahkan isi user Cert";
            $this->response($response, REST_Controller::HTTP_BAD_REQUEST); // OK (200) being the HTTP response code
            die();
        }

        $this->load->model('settings/settings_model');

        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.barierds"))->first_row();

        $TokenBarier = trim($settings->value);
        $this->load->library('Api_digital');
        $ClassApidigital = new Api_digital();
        $msg = "";
        $returnlogin  = $ClassApidigital->getTokenFromUserCert($TokenBarier,$username);

        $token = "";
            $return = json_decode($returnlogin);
             
            if($return->status){
                $status = true;
                $msg = $return->msg;
                $token = $return->token;
            }else{
                $status = false;
                $msg = "Token tidak ditemukan";
                 
               //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
            }
        $response ['success']= $status;
        $response ['msg']= $msg;
        $response ['token']= $token;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        exit();
    }
    public function gettoken_cert_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $usercert = $this->input->post('usercert');
        $barier = $this->input->post('barier');
        $msg = "";
        $token2 = "";
        //die($usercert." : ".$barier." ini");
        if($usercert != "" and $barier != ""){
            //die($barier);
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $return  = $ClassApidigital->getTokenAksesUserCert($barier,$usercert);
            //print_r($return);
            //die();

            if($return->code == "200"){
                $status = true;
                $msg = "Login Berhasil";

                $token2s = explode(",",$return->message);
                $token2 = $token2s[0]; 
                $msg = $msg." ".$token2s[1]; 
            }else{
                $status = false;
                $msg = $return->message;
                if($msg =="")
                    $msg = "Gagal mendapatkan Token, Cek kembali user Cert anda";
                $response ['status']= $status;
                $response ['msg']= $msg;
            }
             
        }else{
            $status = false;
            $msg = "Silahkan lengkapi data usercert dan barier, token Cert usercert : ".$usercert." ".$barier;
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['token'] = $token2;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function get_list_cert_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $usercert = $this->input->post('usercert');
        $barier = $this->input->post('barier');
        $msg = "";
        $cert = "";
        
        if($usercert != "" and $barier != ""){
            //die($barier);
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $return  = $ClassApidigital->getListCert_login($barier,$usercert);
            //print_r($return);
            //die();
            if($return->code == "200"){
                $status = true;
                $msg = $return->message;
                $cert = $return->data[0]->id;
                 
            }else{
                $status = false;
                $msg = $return->message;
                if($msg =="")
                    $msg = "Gagal";
                $response ['status']= $status;
                $response ['msg']= $msg;
            }
             
        }else{
            $status = false;
            $msg = "Lengkapi data usercert dan barier list cert";
            $response ['status']= $status;
            $response ['msg']= $msg;
            //$this->response($response, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response 
        }
        
        $response ['status']= $status;
        $response ['cert'] = $cert;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $id_file = $this->input->post('id_file');
        $id_ttd = $this->input->post('id_ttd');
        
        $datadetil = $this->sk_ds_model->find($id_file);
        if($datadetil->is_signed != "1"){
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $retuanbase64  = $ClassApidigital->signsk($direktori.$id_file);
            // update data
            $status = false;
            $msg = "";
            if($retuanbase64 != ""){
                $data = array();
                $data["teks_base64"]     = $retuanbase64;
                $data["is_signed"]      = "1";
                $this->sk_ds_model->update($id_file,$data);

                // generate file
                //echo $retuanbase64;
                $new_file_name = $id_file.".pdf";
                $bin = base64_decode($retuanbase64, true);
                # Perform a basic validation to make sure that the result is a valid PDF file
                # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
                # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
                if (strpos($bin, '%PDF') !== 0) {
                  throw new Exception('Missing the PDF file signature');
                }
                
                if (file_exists($direktori.$new_file_name)) {
                   unlink($direktori.$new_file_name);
                }
                file_put_contents(trim($direktori).$new_file_name, $bin);  
                // end 
                $status = true;
                $msg = "SK telah Berhasil ditanda Tangan digital";
            }else{
                $status = false;
                $msg = "Ada kesalahan";
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_sk_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathspesimen"))->first_row();
        // get NIK
        $direktorispesimen = trim($settings->value);

        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        //$usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $NIP = $this->input->post('NIP');
        $data = $this->sk_ds_model->find($id_file);

        $this->pegawai_model->select("NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $NIK = isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : "";

        // add log riwayat
        $this->save_riwayat_ds($id_file,$NIP,"Percobaan tanda tangan","");
        // end log riwayat
        

        if($data->is_signed != "1"){

            // create pdf jika belum ada
            $file_name = $id_file.".pdf";
            $b64 = $data->teks_base64;
            $bin = base64_decode($b64, true);

            if (file_exists($direktori.$file_name)) {
               unlink($direktori.$file_name);
            }

            if (strpos($bin, '%PDF') !== 0) {
              throw new Exception('Missing the PDF file signature');
            }
           // if (!file_exists($direktori.$file_name)) {
               file_put_contents($direktori.$file_name, $bin);    
            //}
            // end create pdf
            
            $this->load->library('signature/esign');
            $esign = new Esign();
            if($NIK != "" && $passphrase != ""){

                $spesimen = "default.png";
                if (file_exists($direktorispesimen.$NIP.'.png')) {
                    $spesimen = $NIP.'.png';
                }
                $return = $esign->sign($NIK,$passphrase,$direktori.$file_name,$direktorispesimen.$spesimen);
                if($return == 1){

                    // cek file signed
                    $filesigned = $direktori.$id_file."_signed.pdf";
                    $base64signed = "";
                    if (file_exists($filesigned)) {
                        $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
                    }

                    $status = true;
                    $msg = "Dokumen sudah ditandatangan";
                    $data = array();
                    if($base64signed != ""){
                        $data["teks_base64_sign"]      = $base64signed;    
                    }
                    $data["is_signed"]      = "1";
                    $this->sk_ds_model->update($id_file,$data);
                    $this->save_log($id_file,$NIK,"Berhasil tanda tangan (Apps)",2,0);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$NIP,"Berhasil tanda tangan","");
                    // end log riwayat
                }else{
                    //$this->save_log($id_file,$NIK,$return." Error",1);
                    $msg = $return;
                    $this->save_log($id_file,$NIK,$msg."(Apps)",1,0);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$NIP,"Gagal tanda tangan",$msg);
                    // end log riwayat
                }
            }else{
                $msg = "Silahkan Lengkapi NIK dan Passphrase ";
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_sign_sk_all_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('sk_ds/log_ds_model');
        $this->load->model('ds_riwayat/ds_riwayat_model');
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathspesimen"))->first_row();
        // get NIK
        $direktorispesimen = trim($settings->value);

        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        //$usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $NIP = $this->input->post('NIP');

        //$this->pegawai_model->select("NIK,NIP_BARU");
        $pegawai_login = $this->pegawai_model->find_by("NIP_BARU",trim($NIP));
        $NIK = isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : "";

        $data = json_encode($id_file,true);
        $replace = array("[","]"," ","\"");
        $id_file = str_replace($replace, '', $data);
        //echo $id_file;
        $aid_file = explode(",", $id_file);
        $jmlberhasil = 0;
        $jmlgagal = 0;
        if($passphrase == ""){
            $response ['status']= false;
            $response ['msg']= "Silahkan Masukan passphrase";
            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        
        if(count($aid_file) > 0){
            for($i=0;$i<count($aid_file);$i++){
                $file_pdf = $aid_file[$i];
                //$this->sk_ds_model->select("*");
                $data = $this->sk_ds_model->find($file_pdf);
                if(isset($data->is_signed) && $data->is_signed != "1"){

                    // create pdf jika belum ada
                    $file_name = $file_pdf.".pdf";
                    $b64 = $data->teks_base64;
                    $bin = base64_decode($b64, true);
                    if (strpos($bin, '%PDF') !== 0) {
                      throw new Exception('Missing the PDF file signature');
                    }
                    if (!file_exists($direktori.$file_name)) {
                       file_put_contents($direktori.$file_name, $bin);    
                    }
                    // end create pdf

                    $this->load->library('signature/esign');
                    $esign = new Esign();
                    if($NIK != "" && $passphrase != ""){
                        $spesimen = "default.png";
                        if (file_exists($direktorispesimen.$NIP.'.png')) {
                            $spesimen = $NIP.'.png';
                        }
                        // add log riwayat
                        $this->save_riwayat_ds($file_pdf,$NIP,"Percobaan tanda tangan dari check all","");
                        // end log riwayat
                        $return = $esign->sign($NIK,$passphrase,$direktori.$file_name,$direktorispesimen.$spesimen);
                        if($return == 1){

                            // cek file signed
                            $filesigned = $direktori.$file_pdf."_signed.pdf";
                            $base64signed = "";
                            if (file_exists($filesigned)) {
                                $base64signed = chunk_split(base64_encode(file_get_contents($filesigned)));
                            }

                            $status = true;
                            $msg = "Dokumen sudah ditandatangan";
                            $data = array();
                            if($base64signed != ""){
                                $data["teks_base64_sign"]      = $base64signed;    
                            }
                            $data["is_signed"]      = "1";
                            $this->sk_ds_model->update($file_pdf,$data);
                            $jmlberhasil++;

                            $this->save_log($file_pdf,$NIK,"Berahasil tanda tangan (Apps)",2,0);
                            // add log riwayat
                            $this->save_riwayat_ds($file_pdf,$NIP,"Tandatangan berhasil dari checkall","");
                            // end log riwayat
                        }else{
                            $status = false;
                            $msg = $return;
                            $this->save_log($file_pdf,$NIK,$msg."(Apps)",1,0);
                            // add log riwayat
                            $this->save_riwayat_ds($file_pdf,$NIP,"Tandatangan gagal dari checkall",$msg);
                            // end log riwayat
                            $jmlgagal++;
                            $response ['status']= $status;
                            $response ['msg']= $msg;
                            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                        }
                    } 
                }else{
                    $status = false;
                    $msg = "Status SK sudah di tandatangan";
                    $jmlgagal++;
                }
            }
        }else{
            $response ['status']= false;
            $response ['msg']= "Silahkan Checklist dokumen";
            $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        //$status = true;
       // $this->save_log("",$NIK,"Sebanyak ".$jmlberhasil." dokumen telah ditandatangan secara digital",2,$NIP);
        $msg = "Sebanyak ".$jmlberhasil." dokumen telah ditandatangan secara digital";
        
        
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    private function save_log($ID_FILE = "",$NIK = "",$KETERANGAN = "",$STATUS = "",$NIP = "")
    {
        date_default_timezone_set("Asia/Bangkok");
        // get data detil
        $data = array();
        if($NIK != ""){
            $data["ID_FILE"]    = $ID_FILE;
            $data["NIK"]        = $NIK;
            $data["KETERANGAN"]          = $KETERANGAN;
            $data["CREATED_DATE"]        = date("Y-m-d h:i:s");
            if($NIP != ""){
                $data["CREATED_BY"]          = $NIP;
            }
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
            $data["akses_pengguna"]          = "Apps";
            $this->ds_riwayat_model->insert($data);
        }
    }
    public function send_sign_sk_bppt_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        //$this->load->library("settings.php");
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.pathsk"))->first_row();

        $direktori = trim($settings->value);
        //die($direktori);
        $settings = $this->db->query("
            select 
                s.\"value\" from hris.settings s
                
                where s.\"name\" = ? 
            
            ",array("site.barierds"))->first_row();

        $barierds = trim($settings->value);
        $id_file = $this->input->post('id_file');
        $token = $this->input->post('token');
        $usercert = $this->input->post('usercert');
        $passphrase = $this->input->post('passphrase');
        $datadetil = $this->sk_ds_model->find($id_file);
        if($datadetil->is_signed != "1"){
            $this->load->library('Api_digital');
            $ClassApidigital = new Api_digital();
            $retuanbase64 = "";
            $return  = $ClassApidigital->signsk_all($barierds,$usercert,$passphrase,$direktori.$id_file,$token);
            $retuanbase64 = json_decode($return);
            //print_r($retuanbase64);
            //die();
            // update data
            $status = false;
            $msg = "";
            if($retuanbase64->status){
                $data = array();
                $data["teks_base64"]     = $retuanbase64->base_64;
                $data["is_signed"]      = "1";
                $this->sk_ds_model->update($id_file,$data);

                // generate file
                //echo $retuanbase64;
                $new_file_name = $id_file.".pdf";
                $bin = base64_decode($retuanbase64->base_64, true);
                # Perform a basic validation to make sure that the result is a valid PDF file
                # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
                # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
                if (strpos($bin, '%PDF') !== 0) {
                  throw new Exception('Missing the PDF file signature');
                }
                if (file_exists($direktori.$new_file_name)) {
                   unlink($direktori.$new_file_name);
                }
                file_put_contents(trim($direktori).$new_file_name, $bin);  
                // end 
                $status = true;
                $msg = $retuanbase64->msg;
            }else{
                $status = false;
                $msg = $retuanbase64->msg;
            }
        }else{
            $status = false;
            $msg = "Status SK sudah di tandatangan";
        }
        
        $response ['status']= $status;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_koreksi_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("sk_ds/sk_ds_korektor_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
       
        $id_file = $this->input->post('id_file'); // id file DD
        $catatan = $this->input->post('catatan');
        $status = $this->input->post('status');
        $is_returned = $this->input->post('is_returned');

        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";

        // get data detil
        $datadetil = $this->sk_ds_korektor_model->find($id_file);
        $data = array();
        $return = false;
        // jika statusnya lanjutkan, maka ubah status koreksi pengoreksi selanjutnya jadi 2 (siap koreksi)
        if($datadetil->is_corrected == "2"){
           if($status=="1"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                $korektor_selanjutnya = $korektor_ke + 1;
                // cek apakah ada korektor selanjutnya, jika sudah korektor terakhir maka update ketabel file_ds supaya siap tanda tangan
                $this->sk_ds_korektor_model->where("korektor_ke",$korektor_selanjutnya);
                $nextkorektor = $this->sk_ds_korektor_model->find_by("id_file",$file_id);
                if(isset($nextkorektor->id)){
                    // jika ada korektor selanjutnya
                    $data_updates = array(
                        'is_corrected'     => 2
                    );
                    $this->sk_ds_korektor_model->where("id_file",$file_id);
                    $this->sk_ds_korektor_model->update_where('korektor_ke', $korektor_selanjutnya, $data_updates);
                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$ses_nip,"SK diteruskan ke korektor selanjutnya ".$korektor_selanjutnya,"");
                    // end log riwayat
                }else{
                    // jika tidak ada korektor selanjutnya
                    $data_updates = array(
                        'is_corrected'     => 1
                    );
                    $this->sk_ds_model->update($file_id,$data_updates);

                    // add log riwayat
                    $this->save_riwayat_ds($id_file,$ses_nip,"SK diteruskan ke penandatangan oleh korektor","");
                    // end log riwayat


                }
            }
            // jika statusnya dikembalikan
            if($status=="3"){
                $korektor_ke = $datadetil->korektor_ke;
                $file_id = $datadetil->id_file;
                // jika tidak ada korektor selanjutnya
                $data_updates = array(
                    'is_signed'     => 3,
                    'is_corrected'     => 3,
                    'is_returned'     => 1,
                    'catatan'     => $catatan
                    
                );
                $this->sk_ds_model->update($file_id,$data_updates);
                $data["is_returned"]     = $is_returned;

                // add log riwayat
                $this->save_riwayat_ds($id_file,$ses_nip,"SK dikembalikan oleh korektor",$catatan);
                // end log riwayat

            }
            

            // update data
            $data["is_corrected"]     = $status;
            $data["catatan_koreksi"]     = $catatan;
            
            
            $msg = "";
            if($this->sk_ds_korektor_model->update($id_file,$data)){
                $return = true;
                $msg = "Koreksi Selesai";
            }else{
                $msg = "Koreksi error ".$this->sk_ds_korektor_model->error;
            } 
        }else{
            $msg = "Status SK sudah dikoreksi";
        }
        
        $response ['success']= $return;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function send_koreksi_penandatangan_post()
    {

        date_default_timezone_set("Asia/Bangkok");
        $this->load->model("sk_ds/sk_ds_model");
        $this->load->model("sk_ds/sk_ds_korektor_model");
        $this->load->model('ds_riwayat/ds_riwayat_model');
        $id_file = $this->input->post('id_file'); // id file DD
        $catatan = $this->input->post('catatan');
        $status = $this->input->post('status');
        $is_returned = $this->input->post('is_returned');
        $ses_nip = $this->input->get('nip') ? $this->input->get('nip') : "-";
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
                // add log riwayat
                $this->save_riwayat_ds($id_file,$ses_nip,"Koreksi SK oleh penandatangan",$catatan);
                // end log riwayat
            }else{
                $msg = "Koreksi error ".$this->sk_ds_model->error;
            } 
        }else if($datadetil->is_signed == "1"){
            $msg = "Status SK sudah tandatangan, tidak bisa dikembalikan";
        }
        
        $response ['success']= $return;
        $response ['msg']= $msg;
        $this->response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
 

}
