<?php 
require APPPATH . '/libraries/LIPIAPI_REST_Controller.php';
class Users extends  LIPIAPI_REST_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
    $this->load->model('pegawai/ropeg_model');
       
    }
     protected $methods = [
            'index_get' => ['level' => 10, 'limit' => 10],
            'xlist_get' => ['level' => 0, 'limit' => 10],
            'sub_get' => ['level' => 10, 'limit' => 10],
            'add_riwayat_pendidikan_post' => ['level' => 0, 'limit' => 10],
    ];
    public function login_post(){

        $this->load->model('users/user_model');
        $this->load->library('users/auth');
        $post = $this->post();
        $status = false;
        $msg = "";
        if ($this->input->post('login') === NULL)
        {
            $output['msg'] = 'Parameter login/username di butuhkan';
            $this->response($output, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        // Cek Login 
        $resultlogin = $this->auth->loginapi(
            $this->input->post('login'),
            $this->input->post('password'),1
        );
        if(isset($resultlogin->username) && $resultlogin->username != ""){
            $nip = trim($resultlogin->nip) != "" ? trim($resultlogin->nip) : trim($resultlogin->username);
            $username = trim($resultlogin->username);
            $role_id = trim($resultlogin->role_id);
            $display_name = trim($resultlogin->display_name);
            $photo = trim($resultlogin->PHOTO);
            $data_json = array(
                        'nip' => $nip,
                        'username' => $username,
                        'role_id' => $role_id,
                        'display_name' => $display_name,
                        'foto' => $photo,
                    );
            $status = true;
            $msg = "Login berhasil";
        }else{
            $data_json = array(
                        'nip' => null,
                        'username' => null,
                        'role_id' => null,
                        'display_name' => null,
                        'foto' => null
                        );
            $msg = "Login gagal";

        }
        

        $data = array(
            'status' => $status,
            'status_code' => '200',
            'msg' => $msg,
            'data' => $data_json
        );

        
        $this->response($data, 200);
    }

}
