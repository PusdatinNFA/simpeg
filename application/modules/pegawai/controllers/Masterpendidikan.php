<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class MasterPendidikan extends Admin_Controller
{
    protected $permissionCreate = 'MasterPendidikan.Masters.Create';
    protected $permissionDelete = 'MasterPendidikan.Masters.Delete';
    protected $permissionEdit   = 'MasterPendidikan.Masters.Edit';
    protected $permissionView   = 'MasterPendidikan.Masters.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/pendidikan_model');
        $this->load->model('pegawai/tingkatpendidikan_model');
        
        $tk_pendididikan_all = $this->tingkatpendidikan_model->find_all();
		Template::set('tk_pendididikan_all', $tk_pendididikan_all);
		
		
    }
    public function ajax_list(){
        
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		
		//$this->ref_jabatan_model->where("deleted ",null);
		$total= $this->pendidikan_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();


		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pendidikan_model->where('upper(pendidikan."NAMA") LIKE \'%'.strtoupper($search).'%\'');
		}
		$this->pendidikan_model->limit($length,$start);
		
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pendidikan_model->order_by($iSortCol,$sSortCol);
		$records=$this->pendidikan_model->find_all();

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pendidikan_model->where('upper(pendidikan."NAMA") LIKE \'%'.strtoupper($search).'%\'');
			$jum	= $this->pendidikan_model->count_all();
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->ID;
                $row []  = $record->TK_TEXT;
                $row []  = $record->NAMA;
                $btn_actions = array();
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/masterpendidikan/edit/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
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
    public function index(){
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function delete($record_id){
        if($this->auth->has_permission($this->permissionDelete)){
            if ($this->pendidikan_model->delete($record_id)) {
                log_activity($this->auth->user_id(), 'delete data master pendidikan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message("Sukses Hapus data", 'success');
                echo "Sukses";
            }else{
                echo "Gagal";
            }
        }
        else {
            Template::set_message("Sukses Hapus data", 'error');
        }
    }
	/**
     * Create a Agama object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate); 
		if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('pegawai/masterpendidikan'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        
        Template::set('toolbar_title', "Tambah Pendidikan");

        Template::render();
    }
	public function edit($ID)
    {
        $this->auth->restrict($this->permissionEdit); 
        if($this->input->post('save')){
          $response = $this->save();
          if($response['success']){
                Template::set_message($response['msg'], 'success');
                redirect(base_url('pegawai/masterpendidikan'));
          }
          else {
              if(isset($response['msg'])){
                Template::set_message($response['msg'], 'error');
              } 
          }
        }
        //$this->auth->restrict($this->permissionCreate); 
        $selectedData = $this->pendidikan_model->find($ID);
        Template::set('selectedData',$selectedData);
        Template::set('toolbar_title', "Ubah Pendidikan");

        Template::render();
       
    }
	public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->pendidikan_model->get_validation_rules());
        $response = array(
            'success'=>false
        );
        if ($this->form_validation->run() === false) {
                       
        }
        else {
            $data = $this->pendidikan_model->prep_data($this->input->post());
            $data['NAMA']   = $this->input->post('NAMA');
            
            $id_data = $this->input->post("kode");
            if(isset($id_data) && !empty($id_data)){    
                $this->pendidikan_model->update($id_data,$data);
            }
            else {
                $data['ID']     = $this->input->post('ID') ? $this->input->post('ID') : strtoupper(md5($this->input->post('NAMA'))); 
                $this->pendidikan_model->insert($data);
            }                
            $response ['success']= true;
            $response ['msg']= "Transaksi berhasil";
        }
        return $response;
    }
    public function ajax(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $json = array();
        $limit = 100;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $tkpendidikan = $this->input->get('tkpendidikan');
        $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $json = $this->data_model($q,$start,$limit,$tkpendidikan);
        }
        echo json_encode($json);
    }
    private function data_model($key,$start,$limit,$tkpendidikan=null){
          
            $this->db->start_cache();
            $this->db->like('lower("NAMA")', strtolower($key));
            if ($tkpendidikan){
                $this->db->where('TINGKAT_PENDIDIKAN_ID', $tkpendidikan);
            }
            $this->db->from("hris.pendidikan");
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('ID as id,NAMA as text');
            $this->db->order_by('TINGKAT_PENDIDIKAN_ID');
            $this->db->order_by('NAMA');
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages
                )
            );   
            $this->db->flush_cache();
            return $o;
    }
}
