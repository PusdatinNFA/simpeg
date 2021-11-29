<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_kgb_model extends BF_Model
{
    protected $table_name	= 'mv_kgb';
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
	protected $insert_validation_rules  = array(
		
	);
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
	
	public function getEstimasiKgb($tanggal){
		if(empty($this->selects))
		{
			$this->select("distinct on(pegawai_nip) pegawai_nip,
				id,
				mv_kgb.ref,
				unitkerja.\"ABBREVIATION\",
				pegawai.\"NAMA\" AS nama_pegawai,
				mv_kgb.golongan_id,
				masa_kerja_thn as o_masa_kerja_thn,
				masa_kerja_bln as o_masa_kerja_bln,
				((masa_kerja_thn*12)+masa_kerja_bln) as o_jml_masa_kerja_bln,
				tmt_sk,
				'$tanggal' tgl_estimasi,
				mv_kgb.n_gapok as gaji_lama,
				(select \"BASIC\" from wage where mv_kgb.golongan_id = wage.golongan_id and wage.working_period_month = (
				EXTRACT (
				YEAR 
				FROM
					age('".$tanggal."' :: DATE, tmt_sk :: DATE )) * 12 + EXTRACT (
				MONTH 
				FROM
				age( '".$tanggal."' :: DATE, tmt_sk :: DATE )) 
			) + (( masa_kerja_thn * 12 ) + masa_kerja_bln )) as gaji_baru,
				riwayat,
				(
					EXTRACT ( YEAR FROM age( '".$tanggal."' :: date ,tmt_sk :: date )) * 12
					+ EXTRACT ( MONTH FROM age( '".$tanggal."' :: date ,tmt_sk :: date ))
				)+
				((masa_kerja_thn*12)+masa_kerja_bln) AS n_jml_bulan ");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = mv_kgb.pegawai_nip", "LEFT");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->where("tmt_sk <= '".$tanggal."'");
		$this->db->where("(n_gapok <= (select \"BASIC\" from wage where mv_kgb.golongan_id = wage.golongan_id and wage.working_period_month = (
		EXTRACT (
		YEAR 
		FROM
			age( '".$tanggal."' :: DATE, tmt_sk :: DATE )) * 12 + EXTRACT (
		MONTH 
		FROM
		age( '".$tanggal."' :: DATE, tmt_sk :: DATE )) 
		) + (( masa_kerja_thn * 12 ) + masa_kerja_bln )
		and status = 1
		)
		)
		");
		$this->db->order_by('pegawai_nip');
		$this->db->order_by('tmt_sk',"desc");
		return parent::find_all();
	}
	public function getEstimasiKgbCount($tanggal){
		if(empty($this->selects))
		{
			$this->select("count (distinct pegawai_nip) jml");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = mv_kgb.pegawai_nip", "LEFT");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->where("tmt_sk <= '".$tanggal."'");
		$this->db->where("(n_gapok <= (select \"BASIC\" from wage where mv_kgb.golongan_id = wage.golongan_id and wage.working_period_month = (
		EXTRACT (
		YEAR 
		FROM
			age( '".$tanggal."' :: DATE, tmt_sk :: DATE )) * 12 + EXTRACT (
		MONTH 
		FROM
		age( '".$tanggal."' :: DATE, tmt_sk :: DATE )) 
	) + (( masa_kerja_thn * 12 ) + masa_kerja_bln )
	and status = 1
	)
	)");
		
		return parent::find_all();
	}
}