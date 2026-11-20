<?php defined('BASEPATH') || exit('No direct script access allowed');

class Vw_kpo_model extends BF_Model
{
    protected $table_name	= 'vw_kpo';
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
	
	public function getEstimasiKpo($tanggal){
		if(empty($this->selects))
		{
			$this->select("distinct on(vw_kpo.pegawai_nip) vw_kpo.pegawai_nip,
				tmt_cpns,
				tmt_pns,
				vw_kpo.id,
				vw_kpo.ref,
				dt_kp_yad,
				tingkat_pendidikan,
				pegawai.ref as ref_pegawai,
				unitkerja.\"ABBREVIATION\",
				pegawai.\"NAMA\" AS nama_pegawai,
				vw_kpo.golongan_id,
				golongan_maksimal,
				vw_kpo.masa_kerja_thn as o_masa_kerja_thn,
				vw_kpo.masa_kerja_bln as o_masa_kerja_bln,
				((vw_kpo.masa_kerja_thn*12)+vw_kpo.masa_kerja_bln) as o_jml_masa_kerja_bln,
				vw_kpo.tmt_sk,
				'$tanggal' tgl_estimasi,
				nama_kawasan,
				jenis_jabatan,
				nama_jabatan,
				tmt_jabatan,
				pengelola_sdm_kawasan_id,
				(select count(*) as total from rwt_hukdis where  trim(\"ID_JENIS_HUKUMAN\") = '7' and trim(\"PNS_NIP\") = trim(pegawai.\"NIP_BARU\")
				) as riwayat_hukdis,
				vw_kpo.next_gol_id,vw_kpo.next_gol_nama,vw_kpo.next_gol_pangkat, pegawai.PNS_ID
				");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = vw_kpo.pegawai_nip", "INNER");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",STATUS_NON_ACTIVE);
		$this->db->where("vw_kpo.dt_kp_yad <= '".$tanggal."'");
		$this->db->where("pegawai.\"JENIS_JABATAN_ID\" <> '2'"); //  bukan sebagai JF
		$this->db->where("vw_kpo.golongan_id != '45'");
		$this->db->order_by('vw_kpo.pegawai_nip');
		$this->db->order_by('vw_kpo.tmt_sk',"desc");
		return parent::find_all();
	}
	public function getEstimasiKpoCount($tanggal){
		if(empty($this->selects))
		{
			$this->select("count (distinct vw_kpo.pegawai_nip) jml");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = vw_kpo.pegawai_nip", "LEFT");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->join("rwt_kgb r", "r.ref = vw_kpo.ref and '".$tanggal."' = r.tmt_sk", "LEFT");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",STATUS_NON_ACTIVE);
		$this->db->where("vw_kpo.dt_kp_yad <= '".$tanggal."'");
		$this->db->where("pegawai.\"JENIS_JABATAN_ID\" <> '2'");
		$this->db->where("vw_kpo.golongan_id != '45'");
		return parent::find_all();
	}
	public function getEstimasiKpoJft($tanggal){
		if(empty($this->selects))
		{
			$this->select("distinct on(vw_kpo.pegawai_nip) vw_kpo.pegawai_nip,
				tmt_cpns,
				tmt_pns,
				vw_kpo.id,
				vw_kpo.ref,
				case when dt_kp_yad_jf > vw_rwt_ak.tgl_sk then dt_kp_yad_jf 
					else(date_trunc('month', vw_rwt_ak.tgl_sk) + interval '1 month')::date end 
				as dt_kp_yad,
				tingkat_pendidikan,
				pegawai.ref as ref_pegawai,
				unitkerja.\"ABBREVIATION\",
				pegawai.\"NAMA\" AS nama_pegawai,
				vw_kpo.golongan_id,
				vw_kpo.masa_kerja_thn as o_masa_kerja_thn,
				vw_kpo.masa_kerja_bln as o_masa_kerja_bln,
				((vw_kpo.masa_kerja_thn*12)+vw_kpo.masa_kerja_bln) as o_jml_masa_kerja_bln,
				vw_kpo.tmt_sk,
				'$tanggal' tgl_estimasi,
				nama_kawasan,
				jenis_jabatan,
				nama_jabatan,
				tmt_jabatan,
				ak_akhir_total,
				pengelola_sdm_kawasan_id,
				(select count(*) as total from rwt_hukdis where  trim(\"ID_JENIS_HUKUMAN\") = '7' and trim(\"PNS_NIP\") = trim(pegawai.\"NIP_BARU\")
				) as riwayat_hukdis,
				vw_kpo.next_gol_id,vw_kpo.next_gol_nama,vw_kpo.next_gol_pangkat, pegawai.PNS_ID
				");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = vw_kpo.pegawai_nip", "INNER");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->join("vw_rwt_ak", "vw_rwt_ak.pegawai_nip = pegawai.NIP_BARU", "LEFT");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",STATUS_NON_ACTIVE);
		$this->db->where("vw_kpo.dt_kp_yad_jf <= '".$tanggal."'");
		// tako ubah syarat kp JF TGL SK AK <= TMT terakhir + 2 tahun
		// $this->db->where("vw_rwt_ak.tgl_sk <= vw_kpo.dt_kp_yad_jf");
		$this->db->where("vw_rwt_ak.tgl_sk <= '".$tanggal."'");
		$this->db->where("vw_rwt_ak.tgl_sk >= vw_kpo.tmt_sk");
		$this->db->where("pegawai.\"JENIS_JABATAN_ID\" = '2'"); //  bukan sebagai JF
		$this->db->where("vw_kpo.golongan_id != '45'");
		$this->db->order_by('vw_kpo.pegawai_nip');
		$this->db->order_by('vw_kpo.tmt_sk',"desc");
		return parent::find_all();
	}
	public function getEstimasiKpoCountJft($tanggal){
		if(empty($this->selects))
		{
			$this->select("count (distinct vw_kpo.pegawai_nip) jml");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = vw_kpo.pegawai_nip", "LEFT");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->join("rwt_kgb r", "r.ref = vw_kpo.ref and '".$tanggal."' = r.tmt_sk", "LEFT");
		$this->db->join("vw_rwt_ak", "vw_rwt_ak.pegawai_nip = pegawai.NIP_BARU", "LEFT");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",STATUS_NON_ACTIVE);
		$this->db->where("vw_kpo.dt_kp_yad_jf <= '".$tanggal."'");
		// tako ubah syarat kp JF TGL SK AK <= TMT terakhir + 2 tahun
		// $this->db->where("vw_rwt_ak.tgl_sk <= vw_kpo.dt_kp_yad_jf");
		$this->db->where("vw_rwt_ak.tgl_sk <= '".$tanggal."'");
		$this->db->where("vw_rwt_ak.tgl_sk >= vw_kpo.tmt_sk");
		$this->db->where("pegawai.\"JENIS_JABATAN_ID\" = '2'"); //  bukan sebagai JF
		$this->db->where("vw_kpo.golongan_id != '45'");
		return parent::find_all();
	}
	public function getEstimasiKgbDetail($tanggal){
		if(empty($this->selects))
		{
			$this->select("distinct on(vw_kpo.pegawai_nip) vw_kpo.pegawai_nip,
				vw_kpo.id,
				vw_kpo.ref,
				pegawai.ref as ref_pegawai,
				unitkerja.\"ABBREVIATION\",
				pegawai.\"NAMA\" AS nama_pegawai,
				vw_kpo.golongan_id,
				vw_kpo.masa_kerja_thn as o_masa_kerja_thn,
				vw_kpo.masa_kerja_bln as o_masa_kerja_bln,
				((vw_kpo.masa_kerja_thn*12)+masa_kerja_bln) as o_jml_masa_kerja_bln,
				vw_kpo.tmt_sk,
				'$tanggal' tgl_estimasi,
				vw_kpo.n_gapok as gaji_lama,
				(select count(*) as total from rwt_hukdis where  \"ID_JENIS_HUKUMAN\" = '4' and trim(\"PNS_NIP\") = trim(pegawai.\"NIP_BARU\")
				) as riwayat_hukdis, 
				(select \"BASIC\" from wage where vw_kpo.golongan_id = wage.golongan_id and wage.working_period_month = (
				EXTRACT (
				YEAR 
				FROM
					age('".$tanggal."' :: DATE, vw_kpo.tmt_sk :: DATE )) * 12 + EXTRACT (
				MONTH 
				FROM
				age( '".$tanggal."' :: DATE, vw_kpo.tmt_sk :: DATE )) 
			) + (( vw_kpo.masa_kerja_thn * 12 ) + vw_kpo.masa_kerja_bln )) as gaji_baru,
				riwayat,
				(
					EXTRACT ( YEAR FROM age( '".$tanggal."' :: date ,vw_kpo.tmt_sk :: date )) * 12
					+ EXTRACT ( MONTH FROM age( '".$tanggal."' :: date ,vw_kpo.tmt_sk :: date ))
				)+
				((vw_kpo.masa_kerja_thn*12)+vw_kpo.masa_kerja_bln) AS n_jml_bulan, vw_kpo.*,
			 	g.NAMA golongan_text, g.NAMA_PANGKAT pangkat_text, jabatan.NAMA_JABATAN,
				r.id id_riwayat_kgb, r.id_arsip, r.o_no_sk, r.o_tgl_sk, r.o_tmt_sk,
				r.nama_pejabat_lama, r.o_masakerja_thn r_mk_thn, r.o_masakerja_bln r_mk_bln, r.o_gapok r_gapok, r.final, k.name as kawasan,coalesce(pegawai.pengelola_sdm_kawasan_id,1) as  pengelola_sdm_kawasan_id ");
		}
		$this->db->join("pegawai", "pegawai.NIP_BARU = vw_kpo.pegawai_nip", "LEFT");
		$this->db->join("unitkerja", "unitkerja.ID = pegawai.UNOR_ID", "LEFT");
		$this->db->join("golongan g", "g.ID = vw_kpo.golongan_id", "LEFT");
		$this->db->join("jabatan", "jabatan.KODE_JABATAN = pegawai.JABATAN_INSTANSI_REAL_ID", "LEFT");
		$this->db->join("rwt_kgb r", "r.ref = vw_kpo.ref and '".$tanggal."' = r.tmt_sk", "LEFT");
		$this->db->join("kawasan k", "k.id = pegawai.kawasan_id", "LEFT");
		// $this->db->join("tbl_sk_ds ds", "ds.nomor_sk = vw_kpo.no_sk", "LEFT");
		$this->db->where("vw_kpo.tmt_sk <= '".$tanggal."'");
		$this->db->where_not_in("pegawai.KEDUDUKAN_HUKUM_ID",STATUS_NON_ACTIVE);
		$this->db->where("(cast(coalesce(nullif(vw_kpo.n_gapok,''),'0') as float) <= (select cast(coalesce(nullif(\"BASIC\",''),'0') as float) from wage where vw_kpo.golongan_id = wage.golongan_id and wage.working_period_month = (
		EXTRACT (
		YEAR 
		FROM
			age( '".$tanggal."' :: DATE, vw_kpo.tmt_sk :: DATE )) * 12 + EXTRACT (
		MONTH 
		FROM
		age( '".$tanggal."' :: DATE, vw_kpo.tmt_sk :: DATE )) 
		) + (( masa_kerja_thn * 12 ) + masa_kerja_bln )
		and status = 1
		)
		)
		");
		$this->db->order_by('vw_kpo.pegawai_nip');
		$this->db->order_by('vw_kpo.tmt_sk',"desc");
		return parent::find_all();
	}

	public function getEstimasi($nip)
	{
		$sql = "select
		*,
		case
				when tanda = 1 then 'gakbisa'
				when tanda = 0 then 'bisa'
				else 'aneh'
		end as statusawal,
		case
				when new_mkthn > cutoff then 'KGB HABIS'
				else 'KGB LANJUT'
		end as statuskedua,
		case
				when tanda = 0
				and new_masa_kerja_bln <> 0 then case
						when (
								waktu - cast (
										(
												cast (new_masa_kerja_bln as text) || ' mons'
										) as interval
								)
						) = tmt_gol then tmt_gol + interval '2 years'
						else (
								waktu +(
										interval '2 year'
								)
						) - cast (
								(
										cast (new_masa_kerja_bln as text) || ' mons'
								) as interval
						)
				end
				when tanda = 0
				and new_masa_kerja_bln = 0
				and tmt_gol = waktu then waktu + interval '2 years'
				when tanda = 0
				and new_masa_kerja_bln = 0 then waktu
				when tanda = 1
				and new_masa_kerja_bln = 0 then case
						when waktu - interval '1 year' = tmt_gol then waktu + interval '1 year'
						else waktu + interval '1 year'
				end
				when tanda = 1
				and new_masa_kerja_bln <> 0 then case
						when (
								waktu -(
										interval '1 year'
								) - cast (
										(
												cast (new_masa_kerja_bln as text) || ' mons'
										) as interval
								)
						) = tmt_gol then tmt_gol + interval '2 years'
						else (
								waktu +(
										interval '1 year'
								)
						) - cast (
								(
										cast (new_masa_kerja_bln as text) || ' mons'
								) as interval
						)
				end
		end as tmt_gol_valid
from
		(
				select
						*,
						case
								when gol in ('3', '4')
								or (
										gol in ('1')
										and ruang in ('1')
								) then mod(new_mkthn::int, 2)
								when gol in ('2')
								and new_mkthn not in (0, 1) then mod(new_mkthn::int + 1, 2)
								when gol in ('1')
								and ruang in ('2', '3', '4') then mod(new_mkthn::int + 1, 2)
								when gol in ('2')
								and ruang in ('1')
								and masa_kerja_thn = 1 then 0
								else 99
						end as tanda,
						case
								when gol in ('3', '4') then 32
								when gol in ('2') then 33
								when (
										gol in ('1')
										and ruang in ('1')
								) then 26
								else 27
						end as cutoff
				from
						(
								select
										*,
										cast (trunc(cektahun) as float) as new_mkthn,
										round((cektahun - trunc(cektahun)) * 12) as new_masa_kerja_bln
								from
										(
												select
														*,
														a.masa_kerja_thn * 12 + a.masa_kerja_bln + extract(
																year
																from
																		age(waktu, tmt_gol)
														) * 12 + extract(
																month
																from
																		age(waktu, tmt_gol)
														) as baru,
														(
																a.masa_kerja_thn * 12 + a.masa_kerja_bln + extract(
																		year
																		from
																				age(waktu, tmt_gol)
																) * 12 + extract(
																		month
																		from
																				age(waktu, tmt_gol)
																)
														) / 12 as cektahun
												from
														(
																SELECT 
																case when CURRENT_DATE > tmt_sk then 
																			 cast (
																				date_trunc(
																						'month',
																						CURRENT_DATE 
																				) as date
																		) else cast (
																				date_trunc(
																						'month',
																						tmt_sk 
																				) as date
																		) end as waktu, 
															coalesce(masa_kerja_thn,0) * 12 + coalesce(masa_kerja_bln,0) as lama,
																		left(golongan_id::text, 1) as gol,
																		substring(golongan_id::text, 2, 1) as ruang, tmt_sk as tmt_gol, COALESCE(masa_kerja_thn,0) masa_kerja_thn, COALESCE(masa_kerja_bln,0) masa_kerja_bln, golongan_id, pegawai_nip 
																		from vw_kpo where pegawai_nip = '".$nip."'
														) a
										) a
						) a
		) a -- where new_mkthn <= cutoff";

		$result = $this->db->query($sql);
		return $result->result();
	}

	public function last_kp($nip)
	{
		$data = $this->db->query("select * from vw_kp_terakhir where pegawai_nip = ?  order by tmt_sk desc NULLS LAST",array($nip))->first_row();
		return $data;
	}

	public function last_kgb($nip)
	{
		$data = $this->db->query("select * from vw_kgb_terakhir where pegawai_nip = ?  order by tmt_sk desc NULLS LAST",array($nip))->first_row();
		return $data;
	}
}