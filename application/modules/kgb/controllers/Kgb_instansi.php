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
                $row []  = "<b>".$record->nama_pegawai."</b><br>".$record->pegawai_nip."<br>".$tanggal;
                $row []  = $rec_golongan->NAMA_PANGKAT." - ".$rec_golongan->NAMA." <br><b><i>Sk Terakhir:</i></b> ".date("d-m-Y", strtotime($record->tmt_sk))." <br><small class='label text-center bg-yellow'>".$record->riwayat."</small>";
                $row []  = "<b><i>Lama</i></b> ".$record->o_masa_kerja_thn."th ".$record->o_masa_kerja_bln."bln, <br><b><i>Baru </i></b>".($record->n_jml_bulan/12)."th 0bln";
               	$row []  = "<b><i>Lama</i></b> ".number_format($record->gaji_lama,0,",",".").",<br><b><i>Baru </i></b>".number_format($record->gaji_baru,0,",",".");
               	$row []  = $record->ABBREVIATION;
                $row []  = $informasi_hukdis;
                $btn_actions = array();
                if($record->tmt_sk == $tanggal){
                	if($record->riwayat == 'kgb'){
                		// $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."/pdf' data-toggle='tooltip' title='Print Ulang SK KGB' tooltip='Print Ulang SK KGB' class='btn btn-sm btn-success show-modal'><i class='fa fa-print'></i> </a>";	
                		// $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."' data-toggle='tooltip' title='Download Dokumen word' tooltip='Download word' class='btn btn-sm btn-info'><i class='fa fa-download'></i> </a>";
                	}
                }else{
                	// $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/create_sk/pdf/".$record->pegawai_nip."/".$tanggal."?golongan=$rec_golongan->NAMA2&mk_tahun=$mk_tahun' data-toggle='tooltip' title='Print SK' tooltip='Print SK' class='btn btn-sm btn-warning show-modal'><i class='fa fa-print'></i> </a>";
                }
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref_pegawai."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
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
 	public function download_nominatif()
	{
        $this->load->helper('aplikasi');
		$advanced_search_filters  = $_GET;
		if ($advanced_search_filters) {
			$filters = $advanced_search_filters;
			// die(json_encode($advanced_search_filters));
			// $filters = array();
			// foreach($advanced_search_filters as  $filter){
			// 	$filters[$filter['name']] = $filter["value"];
			// }
			if ($filters['filter_unit_kerja']) {
				$this->db->group_start();
				$this->db->where('unitkerja."UNOR_INDUK"', $filters['filter_unit_kerja']);
				$this->db->group_end();
			}
			if ($filters['nip_sk']) {
				$this->db->group_start();
				$this->db->where('mv_kgb.pegawai_nip', $filters['nip_sk']);
				$this->db->group_end();
			}
			if ($filters['filter_kawasan']) {
				$this->db->group_start();
				$this->db->where('pegawai.kawasan_id',$filters['filter_kawasan']);
				$this->db->group_end();
			}
			if($filters['status'] != ""){
				$this->db->group_start();
				if ($filters['status'] == 0)
					$this->db->where('r.id is null');
				if ($filters['status'] == 1){
					$this->db->where('r.id is not null');
					$this->db->where('r.final is null');
				}
				if ($filters['status'] == 2){
					$this->db->where('r.id is not null');
					$this->db->where('r.final is not null');
				}
				if ($filters['status'] == 3){
					$this->db->where('r.id is null');
					$this->db->or_where('r.final is null');
				}
				
				$this->db->group_end();
			}
			if($filters['pengelolasdm'] != ""){
				$this->pegawai_model->where('coalesce(pengelola_sdm_kawasan_id,1)',$filters['pengelolasdm']);
			}

			$tanggal 		= $filters['filter_tahun'] . "-" . $filters['filter_bulan'] . "-01";
			$records = $this->vw_kgb_model->getEstimasiKgbDetail($tanggal);
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->setActiveSheetIndex(0);

			// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Template database ".$tte->nama_proses);

			$type = PHPExcel_Cell_DataType::TYPE_STRING;
			$row = 1;

			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit("No", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit("Nama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit("NIP", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("Pangkat", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit("Golongan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit("Jabatan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit("Tanggal SK Lama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->setValueExplicit("Nomor SK Lama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("Pejabat Lama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $row)->setValueExplicit("MK lama (Tahun)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $row)->setValueExplicit("MK Lama (Bulan)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $row)->setValueExplicit("Gaji Pokok Lama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $row)->setValueExplicit("Terbilang (lama)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $row)->setValueExplicit("Tanggal Berlaku Lama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(14, $row)->setValueExplicit("MK Baru (Tahun)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(15, $row)->setValueExplicit("MK Baru (Tahun)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(16, $row)->setValueExplicit("Gaji Baru", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(17, $row)->setValueExplicit("Terbilang (baru)", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(18, $row)->setValueExplicit("TMT Gaji Baru", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(19, $row)->setValueExplicit("Satuan Kerja", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(20, $row)->setValueExplicit("Kawasan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(21, $row)->setValueExplicit("Tanggal SK Baru", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(22, $row)->setValueExplicit("Nomor SK Baru", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(23, $row)->setValueExplicit("Pejabat Baru", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(24, $row)->setValueExplicit("is_generated", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(25, $row)->setValueExplicit("is_archived", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(26, $row)->setValueExplicit("Status KGB", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(27, $row)->setValueExplicit("Kawasan SDM", $type);
			$no = 1;
			// $keys = array_keys($records);
			if (isset($records) && is_array($records) && count($records)) :
				foreach ($records as $kgb) {
					$row++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $kgb->nama_pegawai);
					$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($kgb->pegawai_nip);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $kgb->pangkat_text);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $kgb->golongan_text);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $kgb->NAMA_JABATAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $kgb->id_riwayat_kgb? getIndonesiaFormat($kgb->o_tgl_sk):getIndonesiaFormat($kgb->tgl_sk));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $kgb->id_riwayat_kgb? $kgb->o_no_sk: $kgb->no_sk);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $kgb->id_riwayat_kgb? $kgb->nama_pejabat_lama:$kgb->pejabat);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $kgb->id_riwayat_kgb? $kgb->r_mk_thn:$kgb->o_masa_kerja_thn);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $kgb->id_riwayat_kgb? $kgb->r_mk_bln: $kgb->o_masa_kerja_bln);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $kgb->id_riwayat_kgb? rupiah($kgb->r_gapok):rupiah($kgb->gaji_lama));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $kgb->id_riwayat_kgb? Terbilang($kgb->r_gapok):Terbilang($kgb->gaji_lama)." rupiah");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $kgb->id_riwayat_kgb? getIndonesiaFormat($kgb->o_tmt_sk):getIndonesiaFormat($kgb->tmt_sk));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $kgb->n_jml_bulan/12);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, 0);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, rupiah($kgb->gaji_baru));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, Terbilang($kgb->gaji_baru)." rupiah");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, getIndonesiaFormat($kgb->tgl_estimasi));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row, $kgb->ABBREVIATION);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row, $kgb->kawasan);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row, $kgb->id_riwayat_kgb? getIndonesiaFormat($kgb->tgl_sk): "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row, $kgb->id_riwayat_kgb? $kgb->no_sk: "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, $kgb->id_riwayat_kgb? $kgb->pejabat: "");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $row, $kgb->id_riwayat_kgb? 1 : 0);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $kgb->id_arsip? 1 : 0);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $row, $kgb->final? "Final" : ($kgb->id_riwayat_kgb? "Draft":"Belum Diproses"));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $kgb->pengelola_sdm_kawasan_id);
					$no++;
				}
			endif;

			$filename = "Nominatif KGB  " . $tanggal . ' ' . date('Ymdhi') . '.xls';
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
			ob_end_clean();
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
			exit; //done.. exiting!
		}
	
		
	}   
}