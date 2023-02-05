<?php defined('BASEPATH') || exit('No direct script access allowed');

class Riwayat_pindah_unit_kerja_model extends BF_Model
{
    protected $table_name	= 'rwt_pindah_unit_kerja';
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
			'field' => 'SK_NOMOR',
			'label' => 'NO SK',
			'rules' => 'required',
		),
		array(
			'field' => 'SK_TANGGAL',
			'label' => 'TANGGAL SK',
			'rules' => 'required',
		),
		array(
			'field' => 'ID_UNOR_BARU',
			'label' => 'UNOR',
			'rules' => 'required',
		),
		array(
			'field' => 'ID_INSTANSI',
			'label' => 'INSTANSI',
			'rules' => '',
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
    public function find_all($PNS_ID ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*,satker.NAMA_UNOR,satker_induk."ABBREVIATION"');
		}
		if($PNS_ID!=""){
			$this->db->where('PNS_ID',$PNS_ID);
		}
		// $this->db->order_by('SK_TANGGAL',"desc");
		$this->db->join("hris.unitkerja as satker_induk", 'rwt_pindah_unit_kerja.ID_UNOR_BARU = satker_induk.ID', 'left');
		$this->db->join("hris.unitkerja as satker", 'rwt_pindah_unit_kerja.ID_SATUAN_KERJA_PANUGASAN = satker.ID', 'left');
		$this->db->order_by('SK_TANGGAL',"ASC");
		$this->db->order_by('rwt_pindah_unit_kerja.ID',"ASC");
		return parent::find_all();
	}
	public function find_aktif($PNS_NIP = "")
	{

		if (empty($this->selects)) {
			$this->select(
				'
				TRIM("ID_UNOR_BARU") "ID_UNOR_BARU",
				TRIM("NAMA_UNOR_BARU") "NAMA_UNOR_BARU",
				TRIM("ID_SATUAN_KERJA") "ID_SATUAN_KERJA",
				TRIM("NAMA_SATUAN_KERJA") "NAMA_SATUAN_KERJA",
				TRIM("ID_SATUAN_KERJA_PANUGASAN") "ID_SATUAN_KERJA_PANUGASAN",
				pengelola_sdm_kawasan_id
				'
			);
		}
		// if ($PNS_NIP != "") {
			$this->db->where('PNS_NIP', $PNS_NIP);
		// }
		
		$this->db->join("hris.unitkerja as satker", 'rwt_pindah_unit_kerja.ID_SATUAN_KERJA_PANUGASAN = satker.ID', 'left');
		$this->db->order_by('SK_TANGGAL',"desc");
		$this->db->order_by('rwt_pindah_unit_kerja.ID',"desc");
		return parent::find_all();
	}
	public function isUniq($PNS_NIP = "",$ID_UNOR_BARU = "",$SK_TANGGAL= "")
	{

		if (empty($this->selects)) {
			$this->select(
				'
				TRIM("ID_UNOR_BARU") "ID_UNOR_BARU",
				TRIM("NAMA_UNOR_BARU") "NAMA_UNOR_BARU",
				TRIM("ID_SATUAN_KERJA") "ID_SATUAN_KERJA",
				TRIM("NAMA_SATUAN_KERJA") "NAMA_SATUAN_KERJA",
				TRIM("ID_SATUAN_KERJA_PANUGASAN") "ID_SATUAN_KERJA_PANUGASAN"
				'
			);
		}
		$this->db->where('PNS_NIP', $PNS_NIP);
		$this->db->where('ID_UNOR_BARU', $ID_UNOR_BARU);
		$this->db->where('SK_TANGGAL', $SK_TANGGAL);
		return parent::count_all()<=0;
	}
}