<?php defined('BASEPATH') || exit('No direct script access allowed');

class Pengelola_sdm_kawasan_detail_model extends BF_Model
{
    protected $table_name	= 'pengelola_sdm_kawasan_detail';
	protected $key			= 'id';
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
			'field' => 'nama',
			'label' => 'Nama Kawasan',
			'rules' => 'max_length[32]|required',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

	public function find_all()
	{
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		$this->where('status',1);
        $this->db->order_by('id');
		return parent::find_all();
	}
	public function find_kawasan($id)
	{
		if(empty($this->selects))
		{
			$this->select('distinct (kawasan_id) kawasan_id');
		}
		if(!empty($id))
		{
			$this->where('pengelola_sdm_kawasan_id',$id);
		}
		$this->where('status',1);
		$result = parent::find_all();
		return array_column($result,"kawasan_id");
	}
	public function find_kawasan_rule($id,$rule)
	{
		if(empty($this->selects))
		{
			$this->select('distinct (kawasan_id) kawasan_id');
		}
		if(!empty($id))
		{
			$this->where('pengelola_sdm_kawasan_id',$id);
		}
		if(!empty($rule))
		{
			$this->where('kategori',$rule);
		}
		$this->where('status',1);
		$result = parent::find_all();
		return array_column($result,"kawasan_id");
	}
	public function find_satker($id)
	{
		if(empty($this->selects))
		{
			$this->select('distinct (unit_kerja_id) unit_kerja_id');
		}
		if(!empty($id))
		{
			$this->where('pengelola_sdm_kawasan_id',$id);
		}
		$this->where('status',1);
		$result = parent::find_all();
		return array_column($result,"unit_kerja_id");
	}
	public function find_satker_rule($id,$rule)
	{
		if(empty($this->selects))
		{
			$this->select('distinct (unit_kerja_id) unit_kerja_id');
		}
		if(!empty($id))
		{
			$this->where('pengelola_sdm_kawasan_id',$id);
		}
		if(!empty($rule))
		{
			$this->where('kategori',$rule);
		}
		$this->where('status',1);
		$result = parent::find_all();
		return array_column($result,"unit_kerja_id");
	}
	public function find_rule($id)
	{
		if(empty($this->selects))
		{
			$this->select('distinct (kategori) kategori');
		}
		if(!empty($id))
		{
			$this->where('pengelola_sdm_kawasan_id',$id);
		}
		$this->where('status',1);
		$result = parent::find_all();
		return array_column($result,"kategori");
	}
}