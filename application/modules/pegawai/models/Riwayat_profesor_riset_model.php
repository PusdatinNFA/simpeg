<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_profesor_riset_model extends BF_Model
{
	protected $table_name	= 'rwt_profesor_riset';
	protected $key			= 'id';
	protected $date_format	= 'datetime';

	protected $log_user 	= false;
	protected $set_created	= true;
	protected $set_modified = true;
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

	/**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_at';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'updated_at';

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'pns_id',
			'label' => 'PNS ID',
			'rules' => 'max_length[32]|required',
		),
		array(
			'field' => 'nomor_sertifikat',
			'label' => 'Nomor Sertifikat',
			'rules' => 'required',
		),
		array(
			'field' => 'tanggal',
			'label' => 'Tanggal',
			'rules' => 'required',
		),
		array(
			'field' => 'kepakaran',
			'label' => 'Kepakaran',
			'rules' => 'required',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= true;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	public function find_all($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($PNS_ID != "") {
			$this->db->where('pns_id', $PNS_ID);
		}
		$this->db->order_by("tanggal","ASC");
		return parent::find_all();
	}

	public function count_all($PNS_ID = "")
	{

		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($PNS_ID != "") {
			$this->db->where('pns_id', $PNS_ID);
		}
		return parent::count_all();
	}
	public function find_detil($PNS_ID = "")
	{
		if (empty($this->selects)) {
			$this->select($this->table_name . '.*, p.NAMA, p.NIP_BARU, u.NAMA_UNOR_FULL');
		}
		if ($PNS_ID != "") {
			$this->db->where('pns_id', $PNS_ID);
		}
		$this->db->join('pegawai p', 'p.PNS_ID = rwt_profesor_riset.pns_id','left');
		$this->db->join('vw_unit_list u', 'p.UNOR_ID = u.ID','left');
		$this->db->order_by("tanggal","ASC");
		return parent::find_all();
	}
	public function count_detil($PNS_ID = "")
	{
		if (empty($this->selects)) {
			$this->select($this->table_name . '.*');
		}
		if ($PNS_ID != "") {
			$this->db->where('pns_id', $PNS_ID);
		}
		$this->db->join('pegawai p', 'p.PNS_ID = rwt_profesor_riset.pns_id','left');
		$this->db->join('vw_unit_list u', 'p.UNOR_ID = u.ID','left');
		return parent::count_all();
	}
	
}
