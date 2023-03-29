<?php defined('BASEPATH') || exit('No direct script access allowed');

class Golongan_model extends BF_Model
{
    protected $table_name	= 'golongan';
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
			'field' => 'NAMA',
			'label' => 'lang:golongan_field_NAMA',
			'rules' => 'max_length[255]',
		),
		array(
			'field' => 'NAMA_PANGKAT',
			'label' => 'lang:golongan_field_NAMA_PANGKAT',
			'rules' => 'max_length[255]',
		),
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;
	public function getGolongan($id="")
	{
		$data = $this->cache->get('golongan');
		if ($data == null) {
			$rows =  $this->db->query('select * from golongan')->result();
			foreach ($rows as $row) {
				$data[$row->ID] = $row;
			}
			$this->cache->write($data, 'golongan');
		}
		return isset($data[$id]) ? $data[$id] : null;
	}
	public function getNextGolongan($id){
		$anexgol = array(
			"11"=>"12",
			"12"=>"13",
			"13"=>"14",
			"14"=>"21",
			"21"=>"22",
			"23"=>"24",
			"31"=>"32",
			"32"=>"33",
			"33"=>"34",
			"34"=>"41",
			"41"=>"42",
			"42"=>"43",
			"43"=>"44",
			"44"=>"45"
		);
		return isset($anexgol[$id]) ? $anexgol[$id] : null;
	}
}