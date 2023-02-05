<?php defined('BASEPATH') || exit('No direct script access allowed');

class Kepakaran_pegawai_model extends BF_Model
{
    protected $table_name	= 'kepakaranpegawai';
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
	public function find_all($nip ="")
	{		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*, p."NIP_BARU", p."NAMA",
			k.namaid as kepakaranid, k.namaen as kepakaranen, bk.namaid as bkepakaranid, bk.namaen as bkepakaranen, p.ref,
			bp.namaid as bpenelitianid, bp.namaen as bpenelitianen, vw."NAMA_UNOR" AS nama_satker,vw."NAMA_UNOR_FULL');
		}
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'left');
		$this->db->join("vw_unit_list as vw","p.\"UNOR_ID\"=vw.\"ID\" ", 'left',false);
		$this->db->join('bidangpenelitian bp',$this->table_name .'.bidang_penelitian = bp.id','LEFT');
		$this->db->join('bidangkepakaran bk',$this->table_name .'.bidang_kepakaran = bk.id','LEFT');
		$this->db->join('kepakaran k',$this->table_name .'.rumpun_kepakaran = k.id','LEFT');
		$this->db->order_by('kodepenelitian','ASC');
		return parent::find_all();
	}
	public function count_all($nip ="")
	{
		
		if(empty($this->selects))
		{
			$this->select($this->table_name .'.*');
		}
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'left');
		return parent::count_all();
	}
	public function find_bidang_kepakaran($nip ="")
	{		
		if(empty($this->selects))
		{
			$this->select('DISTINCT(bk.kode), bk.*');
		}
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'left');
		$this->db->join('bidangkepakaran bk',$this->table_name .'.bidang_kepakaran = bk.id','LEFT');
		$this->db->join('kepakaran k',$this->table_name .'.rumpun_kepakaran = k.id','LEFT');
		return parent::find_all();
	}
	public function find_bidang_penelitian($nip ="")
	{		
		if(empty($this->selects))
		{
			$this->select('DISTINCT(bp.kode), bp.*');
		}
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'left');
		$this->db->join('bidangpenelitian bp',$this->table_name .'.bidang_penelitian = bp.id','LEFT');
		$this->db->join('bidangkepakaran bk',$this->table_name .'.bidang_kepakaran = bk.id','LEFT');
		$this->db->join('kepakaran k',$this->table_name .'.rumpun_kepakaran = k.id','LEFT');
		return parent::find_all();
	}
	public function find_detail_bidang_kepakaran($nip ="")
	{		
		$this->select('DISTINCT(bk.kode), k.namaid as knamaid,k.namaen as knamaen, bk.namaid as bknamaid,
		bk.namaen as bknamaen, p.NAMA, p.NIP_BARU,u.NAMA_UNOR');
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'inner');
		$this->db->join('bidangkepakaran bk',$this->table_name .'.bidang_kepakaran = bk.id','LEFT');
		$this->db->join('kepakaran k',$this->table_name .'.rumpun_kepakaran = k.id','LEFT');
		$this->db->join('vw_unit_list u','u.ID = p.UNOR_INDUK_ID','LEFT');
		$this->db->where('bidang_kepakaran is not null');
		$this->db->order_by('p.NAMA','ASC');
		return parent::find_all();
	}
	public function find_detail_bidang_penelitian($nip ="")
	{		
		$this->select('DISTINCT(bp.kode), k.namaid as knamaid,k.namaen as knamaen, bk.namaid as bknamaid,
		bk.namaen as bknamaen, bp.namaid as bpnamaid, bp.namaen as bpnamaen, p.NAMA, p.NIP_BARU,u.NAMA_UNOR');
		if($nip!=""){
			$this->db->where('nip',$nip);
		}
		$this->db->join('pegawai p', 'p.NIP_BARU = kepakaranpegawai.nip', 'inner');
		$this->db->join('bidangpenelitian bp',$this->table_name .'.bidang_penelitian = bp.id','LEFT');
		$this->db->join('bidangkepakaran bk',$this->table_name .'.bidang_kepakaran = bk.id','LEFT');
		$this->db->join('kepakaran k',$this->table_name .'.rumpun_kepakaran = k.id','LEFT');
		$this->db->join('vw_unit_list u','u.ID = p.UNOR_INDUK_ID','LEFT');
		$this->db->where('bidang_penelitian is not null');
		$this->db->order_by('p.NAMA','ASC');
		return parent::find_all();
	}

	public function find_first_row(){
		return $this->db->get($this->db->schema.".".$this->table_name)->first_row();
	}
}