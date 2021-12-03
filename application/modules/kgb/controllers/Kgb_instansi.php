<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Kgb_instansi extends Admin_Controller
{
	protected $permissionViewKgbInstansi   	= 'LayananKGB.View.PengelolaKGBInstansi';
   	protected $permissionViewKgbKawasan   	= 'LayananKGB.View.PengelolaKGBSatker';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('golongan/golongan_model');
        $this->load->model('kgb/vw_kgb_model');
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
    	$this->auth->restrict($this->permissionViewKgbInstansi);
    	Template::set('toolbar_title', "KGB Instansi");
		Template::set("collapse",true);
		Template::set_view("kgb_instansi/v_kgb_instansi");
        Template::render();
	}
	public function surat_pengantar(){
		header('Set-Cookie: fileDownload=true; path=/');
		/*
			Cetak
		*/
	}
    public function ajax_list(){
    	$this->auth->restrict($this->permissionViewKgbInstansi);
    	$this->load->helper('aplikasi');
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}
		$this->db->start_cache();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['filter_unit_kerja']){
				$this->db->group_start();
				$this->db->where('unitkerja."UNOR_INDUK"',$filters['filter_unit_kerja']);
				$this->db->group_end();
			}
			if($filters['nip_sk']){
				$this->db->group_start();
				$this->db->where('pegawai_nip',$filters['nip_sk']);	
				$this->db->group_end();
			}
		}
		$tanggal 		= $filters['filter_tahun']."-".$filters['filter_bulan']."-01";
		$this->db->stop_cache();
		$output 		=array();
		$output['draw']	=$draw;
		$total 			= $this->vw_kgb_model->getEstimasiKgbCount($tanggal)[0]->jml;
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

		$this->vw_kgb_model->limit($length,$start);
		$records = $this->vw_kgb_model->getEstimasiKgb($tanggal);

		$this->db->flush_cache();
		$nomor_urut = 1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$rec_golongan = $this->golongan_model->getGolongan($record->golongan_id);
				$mk_tahun = $record->n_jml_bulan/12;
				// print_r($record);
				$btn_actions_array = array();
				if($record->riwayat_kgb_id){
					$btn_actions_array  [] = "
						Sudah di Proses
					";	
				}
				else {
					$btn_actions_array  [] = "
						Belum di Proses
					";	
				}
				$informasi_hukdis = "";
				if($record->riwayat_hukdis>0){
					$informasi_hukdis = "<small class='label text-center bg-red'>HUKDIS</small>";
				}
				else $informasi_hukdis = "-";
				$row = array();
                $row []  = $nomor_urut;
                $row []  = $record->nama_pegawai."<br>".$record->pegawai_nip;
                $row []  = $rec_golongan->NAMA_PANGKAT." - ".$rec_golongan->NAMA."<br><b><i>Sk Terakhir:</i></b> ".date("d-m-Y", strtotime($record->tmt_sk));
                $row []  = "<b><i>Lama</i></b> ".$record->o_masa_kerja_thn."th ".$record->o_masa_kerja_bln."bln, <br><b><i>Baru </i></b>".($record->n_jml_bulan/12)."th 0bln";
               	$row []  = "<b><i>Lama</i></b> ".number_format($record->gaji_lama,0,",",".").",<br><b><i>Baru </i></b>".number_format($record->gaji_baru,0,",",".");
               	$row []  = $record->ABBREVIATION;
                $row []  = $informasi_hukdis;
                $btn_actions = array();
                if($record->tmt_sk == $tanggal){
                	if($record->riwayat == 'kgb'){
                		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."/pdf' data-toggle='tooltip' title='Print Ulang SK KGB' tooltip='Print Ulang SK KGB' class='btn btn-sm btn-success show-modal'><i class='fa fa-print'></i> </a>";	
                		$btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."' data-toggle='tooltip' title='Download Dokumen word' tooltip='Download word' class='btn btn-sm btn-info'><i class='fa fa-download'></i> </a>";
                	}
                }else{
                	$btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/create_sk/pdf/".$record->pegawai_nip."/".$tanggal."?golongan=$rec_golongan->NAMA2&mk_tahun=$mk_tahun' data-toggle='tooltip' title='Print SK' tooltip='Print SK' class='btn btn-sm btn-warning show-modal'><i class='fa fa-print'></i> </a>";
                }
                
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 
                $output['data'][] = $row;
                $nomor_urut++;
			}
		endif;
		echo json_encode($output);
    }
	function test(){
		echo getIndonesiaFormat('2018-03-01');
	}
	public function masal(){
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/template_sk_kgb_masal.docx");
		$data = array();		
		$data[] = array(
			'nama'=>'sumarno'
		);
		$data[] = array(
			'nama'=>'dhia'
		);
		$TBS->MergeBlock('o', $data);
		$output_file_name = uniqid("KGB");
		$output_file_name = $output_file_name.".docx";
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.			
	}
	private function gen_qrcode($ref){
		$this->load->library('phpqrcode_lib');
        //$ref = uniqid("sk_kgb");
        $barcode_file = $ref.".png";
        $barcode_file_path = APPPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."qrcodes".DIRECTORY_SEPARATOR."".$barcode_file; 
        $qr_data = base_url("kgb/services/detail/".$ref);
        QRcode::png($qr_data, $barcode_file_path, 'L', 4, 2);
        return $barcode_file_path;
	}
	public function cetak_surat_pengantar($thn,$bln,$tipe="docx"){
		
		$this->load->helper('aplikasi');
		$template_data=$this->db->query("select * from mst_templates where id = ?",array(6))->first_row();
		
		$this->db->select("rwt_kgb.ref,rwt_kgb.pejabat, mv_kgb.dt_kgb_yad,rwt_kgb.no_sk,mv_kgb.id,vw.NAMA_UNOR_FULL as unor_full,p.NAMA as name,p.NIP_BARU as nip,mv_kgb.masa_kerja_thn,mv_kgb.masa_kerja_bln,g.NAMA2 as golongan2_name,g.NAMA as golongan_name,g.NAMA_PANGKAT as pangkat_name");
		$this->db->from("mv.mv_kgb");
		$this->db->join("hris.rwt_kgb","mv_kgb.id = rwt_kgb.mv_kgb_id");
        $this->db->join("hris.pegawai p",'mv_kgb.pegawai_id = p."ID"',"INNER");
		$this->db->join("hris.vw_unit_list vw",'p.UNOR_ID=vw.ID');
		$this->db->join("hris.golongan g",'g.ID=p.GOL_ID');
		$this->db->where("rwt_kgb.tmt_sk",$thn."-".$bln."-01");
		$kgbs_data = $this->db->get()->result();
		
		
		$gaji = $this->db->query("select * from wage where wage.\"GOLONGAN\" = ? and wage.\"WORKING_PERIOD\" = ? ",array(str_replace("/","",$kgb_data->golongan2_name),$kgb_data->masa_kerja_thn))->first_row();
		
		
		$this->load->library('Template_doc_lib');
		$TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
		
		$tembusan_data = array();
		$tembusan_data [] = 'Kepala BKN, u.p. Deputi Bidang Sistem Informasi Kepegawaian';
		$tembusan_data [] = 'Kepala Kantor Tata Usaha Anggaran, Kementerian Keuangan';
		$tembusan_data [] = 'Pegawai yang bersangkutan';
		$TBS->MergeBlock('tembusan', $tembusan_data);
		$sk_data =  array();
		foreach($kgbs_data as $kgb_data){
			$sk_data[] = array(
				'p_nip'=>$kgb_data->nip,
				'no'=>$kgb_data->no_sk,
				'to'=>'Kepala Kantor Pelayanan Perbendaharaan Negara',
				'tgl_sk'=>date('d-m-Y'),
				'tempat'=>'Jakarta',
				'lokasi_sk'=>'',
				'uu'=>'PP No 30 Tahun 2015',
				'p_nama'=>$kgb_data->name,
				'masa_kerja_thn'=>$kgb_data->masa_kerja_thn,
				'masa_kerja_bln'=>$kgb_data->masa_kerja_bln,
				'tmt'=>$kgb_data->dt_kgb_yad,
				'p_pangkat'=>$kgb_data->pangkat_name . "/".$kgb_data->golongan_name,
				'gaji_baru'=>rupiah($gaji->BASIC),
				'gaji_baru_text'=>Terbilang($gaji->BASIC),
				'gaji_lama'=>'',
				'p_satker'=>'',
				'foto'=>$this->gen_qrcode($kgb_data->ref),
				'pejabat'=>$kgb_data->pejabat,
				
			);
		}
		//die(date('Y-m-d'));
		$TBS->MergeBlock('r', $sk_data);
		$TBS->MergeField('dok',array(
			'print_date'=>getIndonesiaFormat(date('Y-m-d'))
		));
		
		$output_file_name = uniqid("KGB_");
		$docx_location = "temps/".$output_file_name.".docx";
		$TBS->Show(OPENTBS_FILE,$docx_location );	
		try {
			$unoconv = Unoconv\Unoconv::create([
				 'unoconv.binaries' => '/usr/bin/unoconv',
				] 
			);
			$unoconv->transcode($docx_location, 'pdf', "temps/".$output_file_name.'.pdf');
			header("Content-type: application/pdf");
			header("Content-Disposition: inline; filename=filename.pdf");
			@readfile("temps/".$output_file_name.'.pdf');
			
		}catch(Exception $e){
			echo $e->getMessage();
		}	
		
	}
    
}