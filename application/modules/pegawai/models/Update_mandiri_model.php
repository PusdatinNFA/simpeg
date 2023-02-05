<?php defined('BASEPATH') || exit('No direct script access allowed');

class Update_mandiri_model extends BF_Model
{
    protected $table_name	= 'update_mandiri';
	protected $key			= 'ID';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $soft_deletes	= false;


	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected $return_insert_id = true;

	// The default type for returned row data.
	protected $return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected $protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'PNS_ID',
			'label' => 'PNS ID',
			'rules' => 'max_length[32]|required',
		),
		array(
			'field' => 'KOLOM',
			'label' => 'KOLOM',
			'rules' => 'max_length[50]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

    
	public function find_first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
	
	public function find_all($PNS_ID = ""){
		 
		$this->db->select('"NAMA",update_mandiri.*',false);
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->order_by("update_mandiri.ID","DESC");
		return parent::find_all();
	}
	public function find_notif(){
		 
		$this->db->select($this->table_name .'.*,"NIP_BARU","NAMA","NAMA_UNOR_FULL", u.display_name as  verifikator');
		// $this->db->where("STATUS IS NULL");
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join('users u', 'u.id = update_mandiri.VERIFIKASI_BY', 'left');
		$this->db->order_by("update_mandiri.ID","ASC");
		return parent::find_all();
	}
	public function find_notif1($satker_id = ""){
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select($this->table_name .'.*,"NIP_BARU","NAMA","NAMA_UNOR_FULL", u.display_name as  verifikator');
		// $this->db->where("STATUS IS NULL");
		$this->db->where("LEVEL_UPDATE",3);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join('users u', 'u.id = update_mandiri.VERIFIKASI_BY', 'left');
		$this->db->order_by("update_mandiri.ID","ASC");
		return parent::find_all();
	}
	public function count_notif(){
		 
		$this->db->select($this->table_name .'.*,"NAMA"');
		// $this->db->where("STATUS IS NULL");
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		return parent::count_all();
	}
	public function find_notif_pribadi(){
		 
		$this->db->select($this->table_name .'.*,"NIP_BARU","NAMA","NAMA_UNOR_FULL",STATUS, u.display_name as  verifikator');
		// $this->db->where("STATUS IS NULL");
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		$this->db->join('users u', 'u.id = update_mandiri.VERIFIKASI_BY', 'left');
		$this->db->order_by("update_mandiri.ID","DESC");
		return parent::find_all();
	}
	public function count_notif_pribadi(){
		 
		$this->db->select($this->table_name .'.*,pegawai."NAMA"');
		// $this->db->where("STATUS IS NULL");
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		return parent::count_all();
	}
	public function count_notif1($satker_id = ""){
		$where_clause = '';
		if($satker_id){
			if(is_array($satker_id) && sizeof($satker_id)>0){
				$where_clause = 'AND ( 1=2 ';
				foreach($satker_id as $u_id){
					$where_clause .= ' OR vw."ESELON_1" = \''.$u_id.'\' OR vw."ESELON_2" = \''.$u_id.'\' OR vw."ESELON_3" = \''.$u_id.'\' OR vw."ESELON_4" = \''.$u_id.'\' ' ;
				}
				$where_clause .= ')';
			}
			else $where_clause = 'AND (vw."ESELON_1" = \''.$satker_id.'\' OR vw."ESELON_2" = \''.$satker_id.'\' OR vw."ESELON_3" = \''.$satker_id.'\' OR vw."ESELON_4" = \''.$satker_id.'\')' ;
		}
		$this->db->select($this->table_name .'.*,"NAMA"');
		// $this->db->where("STATUS IS NULL");
		$this->db->where("LEVEL_UPDATE",3);
		$this->db->where("(status_pegawai != 3 or status_pegawai is null) ".$where_clause);
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->join("vw_unit_list as vw","pegawai.\"UNOR_ID\"=vw.\"ID\"", 'left',false);
		return parent::count_all();
	}
	public function find($id = ""){
		 
		$this->db->select('NAMA,update_mandiri.*, pegawai.ref, pegawai.NIP_BARU');
		$this->db->join('pegawai', 'pegawai.PNS_ID = update_mandiri.PNS_ID', 'left');
		$this->db->order_by("update_mandiri.ID","DESC");
		return parent::find($id);
	}
	public function get_jenis_data($key = ""){		 
		$this->db->select('distinct("NAMA_KOLOM") as id, "NAMA_KOLOM" as text',false);
		$this->db->like('lower("NAMA_KOLOM")',strtolower($key),"BOTH");
		$this->db->order_by("update_mandiri.NAMA_KOLOM","DESC");
		return parent::find_all();
	}
	public function count_rekap_pdm()
	{
		return $this->db->from("vw_rekap_pdm")->get()->num_rows();
	}
	public function find_rekap_pdm()
	{
		$this->db->from("vw_rekap_pdm");
		$this->db->order_by("NAMA_UNOR","ASC");
		$this->db->order_by("NAMA","ASC");
		$data = $this->db->get()->result();
		return $data;
	}
	public function find_prosentase_pdm()
	{
		$data = $this->db->from("vw_prosentase_pdm")->get()->result();
		return $data;
	}
	public function count_prosentase_pdm()
	{
		return $this->db->from("vw_prosentase_pdm")->get()->num_rows();
	}
	public function find_progres_verifikasi()
	{
		$data = $this->db->from("vw_progres_verifikasi")->get()->result();
		return $data;
	}
	public function count_progres_verifikasi()
	{
		return $this->db->from("vw_progres_verifikasi")->get()->num_rows();
	}
	public function find_detail_verifikasi()
	{
		$data = $this->db->from("vw_rekap_verifikator_pdm")->get()->result();
		return $data;
	}
	public function count_detail_verifikasi()
	{
		return $this->db->from("vw_rekap_verifikator_pdm")->get()->num_rows();
	}
	public function find_belum_diverifikasi($pns_id = null)
	{
		if ($pns_id){
			$this->db->where('PNS_ID',$pns_id);
		}
		$data = $this->db->from("vw_pdm_belum_diverif")->get()->first_row();;
		return $data;
	}
	
}