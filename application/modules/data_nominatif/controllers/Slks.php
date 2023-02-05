<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Slks extends Admin_Controller
{
	protected $permissionView   = 'Nominatif.Slks.View';
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
        $this->load->model('pegawai/unitkerja_model');
        $this->load->model('pegawai/riwayat_penghargaan_model');
        $this->load->model('pegawai/riwayat_huk_dis_model');
        $this->load->model('pegawai/riwayat_cltn_model');
		$this->load->helper('aplikasi');
		$this->load->library('LibOpenTbs');
		$this->load->model('masters/pengelola_sdm_kawasan_model');
		$pengelolasdms = $this->pengelola_sdm_kawasan_model->find_all();
		Template::set('pengelolasdms', $pengelolasdms);
    }

    /**
     * Display a list of petajabatan data.
     *
     * @return void
     */
    public function index()
    {
    	$this->auth->restrict($this->permissionView);
    	Template::set('toolbar_title', "SLKS Instansi");
		Template::set("collapse",true);
        $this->load->model('pegawai/kawasan_model');
        $kawasans = $this->kawasan_model->find_all();
        Template::set('kawasans', $kawasans);
		Template::set_view("data_nominatif/v_slks_instansi");
        Template::render();
	}

	public function kawasan()
	{
		Template::set('toolbar_title', "SLKS Kawasan");
		Template::set("collapse",true);
        // $this->load->model('pegawai/kawasan_model');
        // $kawasans = $this->kawasan_model->find_all();
        Template::set('kawasan', json_decode($this->auth->kawasans()));
		Template::set_view("data_nominatif/v_slks_kawasan");
        Template::render();
	}

    public function ajax_list(){
    	// $this->auth->restrict($this->permissionView);
    	$this->load->helper('aplikasi');
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$this->db->start_cache();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if (!$advanced_search_filters){
			Template::redirect(base_url());
		}
		if($advanced_search_filters){
			$filters = array();
			$kawasans = array();
			foreach($advanced_search_filters as  $filter){
				if ($filter['name'] == 'kawasans[]'){
					$kawasans[] = $filter["value"];
				} else
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['nip_sk']){
				$this->db->group_start();
				$this->db->where('pegawai.NIP_BARU',$filters['nip_sk']);	
				$this->db->group_end();
			}
			if($kawasans){
				$this->db->group_start();
				$this->db->where_in('pegawai.kawasan_id',$kawasans);	
				$this->db->group_end();
			}
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
		}
		$tanggal 		= $filters['filter_tahun']."-".$filters['filter_bulan']."-01";
		$this->db->stop_cache();
		$output 		=array();
		$output['draw']	=$draw;
		$cache_name = "total_".$filters['filter_tahun']."_".$filters['filter_bulan']."_".$filters['filter_jenis'];
		$total = "";
		//$this->cache->get($cache_name);
		// if (!$total || $total == ''){
		$total 			= $this->pegawai_model->getEstimasiSlksCount($tanggal, $filters);
  		//  $this->cache->write($total, $cache_name,3600);
		// } 
		// $total 			= count($this->pegawai_model->getEstimasislks($tanggal, $filters));
		$this->pegawai_model->limit($length,$start);
		$records = $this->pegawai_model->getEstimasislks($tanggal, $filters);
        // print_r($records);
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

		$this->db->flush_cache();
		$nomor_urut = 1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$row = array();
                $row []  = $nomor_urut;
                $row []  = $record->NAMA." <br>".$record->NIP_BARU;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->masa_kerja."Th<br><i>CPNS</i> ".$record->TMT_CPNS;
               	$row []  = $record->satya_tahun;
               	$row []  = $record->NAMA_UNOR_FULL;
                $btn_actions = array();

                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/download_drh/".$record->PNS_ID."' data-toggle='tooltip' title='Cetak DRH Singkat' tooltip='Lihat DRH' class='btn btn-sm btn-warning' target='_blank'><i class='fa fa-print'></i> </a>";
                // $btn_actions  [] = "<a href='".base_url()."data_nominatif/slks/downloaddrh/".$record->PNS_ID."' data-toggle='tooltip' title='Download DRH Singkat' tooltip='Download DRH' target='_BLANK' class='btn btn-sm btn-success'><i class='fa fa-download'></i> </a>";
				$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                           
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 
                $output['data'][] = $row;
                $nomor_urut++;
			}
		endif;
		echo json_encode($output);
    }

	public function cetakdrh($pns_id)
	{
		if ($pns_id) {

			$direktori = trim($this->settings_lib->item('site.pathsk'))."draft/";
			$id_file = 'DRH_SIMPLE_'.$pns_id;
			// $filepath = $direktori.$id_file.".docx";
			// if(file_exists($filepath)) {
			// 	$this->generate_pdf_from_word($filepath);
			// } else {
				$data = $this->get_data($pns_id);
				if ($data){
					
					$this->generate_word(null,$data, $id_file);

					$output_file_name = $id_file.'.docx';
					$docx_location = $direktori.$output_file_name;
					$this->generate_pdf_from_word($docx_location);
				}
			// }
			$base64signed = base64_encode(file_get_contents($direktori.$id_file.".pdf"));
			$FILE_BASE64 = "data:application/pdf;base64,".$base64signed;
			echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
			die();
		}
		
	}

	public function get_data($pns_id)
	{
		$pegawai = $this->pegawai_model->get_drh($pns_id);
		$data = null;
		if ($pegawai) {
			$pejabat = $this->unitkerja_model->get_pejabat('157');
			if ($pejabat){
				$pejabat = $pejabat[0];
			}
			$nama_lengkap = (isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN.' ' : ''). (isset($pegawai->NAMA) ? $pegawai->NAMA : '').(isset($pegawai->GELAR_BELAKANG) ? ', '.$pegawai->GELAR_BELAKANG : '');
			$nama_pejabat = (isset($pejabat->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN.' ' : ''). (isset($pejabat->NAMA_PEJABAT) ? $pejabat->NAMA_PEJABAT : '').( isset($pejabat->GELAR_BELAKANG) ? ', '.$pejabat->GELAR_BELAKANG : '');
			$jabatan_pejabat = (isset($pejabat->status_jabatan) && $pejabat->status_jabatan == 'Plt.' ? 'Plt. ' : '').(isset($pejabat->JABATAN_PEJABAT) ? $pejabat->JABATAN_PEJABAT.' ' : '').$pejabat->NAMA_UNOR;
			$this->riwayat_penghargaan_model->where('PNS_NIP',$pegawai->NIP_BARU);
			$rwt_penghargaans = $this->riwayat_penghargaan_model->find_all();
			$rwt_penghargaan = "";
			foreach ($rwt_penghargaans as $rwt) {
				if ($rwt_penghargaan!='')
					$rwt_penghargaan.=';';
				$rwt_penghargaan.=$rwt->jenis_penghargaan_text.', '.getIndonesiaFormat($rwt->SK_TANGGAL).', Nomor '.$rwt->SK_NOMOR;
			}
			$rwt_hukdiss = $this->riwayat_huk_dis_model->find_all($pns_id);
			$rwt_hukdis = "";
			foreach ($rwt_hukdiss as $rwt) {
				if ($rwt_hukdis!='')
					$rwt_hukdis.=';';
				$rwt_hukdis.=$rwt->NAMA_JENIS_HUKUMAN.', '.getIndonesiaFormat($rwt->SK_TANGGAL).', Nomor '.$rwt->SK_NOMOR;
			}
			$rwt_cltns = $this->riwayat_cltn_model->find_all($pns_id);
			$rwt_cltn = "";
			foreach ($rwt_cltns as $rwt) {
				if ($rwt_cltn!='')
					$rwt_cltn.=';';
				$rwt_cltn.=getIndonesiaFormat($rwt->tanggal_awal).' s.d. '.getIndonesiaFormat($rwt->tanggal_akhir).', Nomor '.$rwt->nomor_sk;
			}
			$data = array (
				'NAMA' 			=> $nama_lengkap,
				'TEMPAT_LAHIR' 	=> $pegawai->TEMPAT_LAHIR,
				'TANGGAL_LAHIR' => getIndonesiaFormat($pegawai->TGL_LAHIR),
				'TEMPAT_LAHIR'  => $pegawai->TEMPAT_LAHIR_NAMA?$pegawai->TEMPAT_LAHIR_NAMA:"-",
				'NIP_BARU' 		=> $pegawai->NIP_BARU,
				'NIP_LAMA'  	=> $pegawai->NIP_LAMA?$pegawai->NIP_LAMA:"-",
				'PANGKAT_TEXT'  => $pegawai->PANGKAT_TEXT,
				'GOLONGAN'  	=> $pegawai->GOL_TEXT,
				'TMT_GOLONGAN'  => getIndonesiaFormat($pegawai->TMT_GOLONGAN),
				'NOMOR_SK_CPNS' => $pegawai->NOMOR_SK_CPNS,
				'TMT_CPNS'  	=> getIndonesiaFormat($pegawai->TMT_CPNS),
				'JABATAN_NAMA'  => $pegawai->JABATAN_NAMA,
				'NAMA_SATKER'  	=> $pegawai->SATUAN_KERJA_INDUK_NAMA?$pegawai->SATUAN_KERJA_INDUK_NAMA:"",
				'NO_SK_JABATAN' => $pegawai->NO_SK_JABATAN,
				'TMT_JABATAN'  	=> getIndonesiaFormat($pegawai->TMT_JABATAN),
				'JENIS_KELAMIN' => $pegawai->JENIS_KELAMIN=="M"?"Pria":"Wanita",
				'PENGHARGAAN'  	=> $rwt_penghargaan != ''?$rwt_penghargaan:'-',
				'HUKDIS' 		=> $rwt_hukdis != ''?$rwt_hukdis:'-',
				'CLTN'  		=> $rwt_cltn != ''?$rwt_cltn:'-',
				'KOTA'			=> $pegawai->LOKASI_KERJA?$pegawai->LOKASI_KERJA:"Jakarta",
				'PENDIDIKAN'	=> $pegawai->NAMA_PENDIDIKAN? $pegawai->NAMA_PENDIDIKAN:"",
				'TANGGAL'		=> getIndonesiaFormat(Date('Y-m-d')),
				'JABATAN_PEJABAT' => $jabatan_pejabat,
				'NAMA_PEJABAT' 	=> $nama_pejabat,
				'NIP_PEJABAT' 	=> $pejabat->NIP_PEJABAT
			);
		}

		return $data;
		
	}

	public function downloaddrh($pns_id){
		// $filepath = trim($this->settings_lib->item('site.pathsk'))."draft/".'DRH_SIMPLE_'.$pns_id.".docx";
		// if(file_exists($filepath)) {
        //     $this->force_download($filepath);
        // } else {
			$TBS = $this->libopentbs->TBS;
			$data = $this->get_data($pns_id);
			if ($data){
				$id_file = 'DRH_SIMPLE_'.$pns_id;
				$this->generate_word($TBS,$data,$id_file, true);
			}
		// }
	}

	private function force_download($filepath){
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filepath));
		flush(); // Flush system output buffer
		readfile($filepath);
		die();
	}

	private function generate_word($TBS = null, $data,$id_file, $download = false)
	{
		$template_data = $this->db->query("select * from mst_templates where id = ?",array(TEMPLATE_DRH_SLKS))->first_row();
		$template_name = BASEPATH."../../assets/templates/".$template_data->template_file;
		if (!$TBS)
			$TBS = $this->libopentbs->TBS;
		
		$TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
		
		$TBS->MergeField('p',$data);

		$output_file_name = $id_file.'.docx';
		$direktori = trim($this->settings_lib->item('site.pathsk'))."draft/";
		$docx_location = $direktori.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $docx_location); // Also merges all [onshow] automatic fields.
		if ($download){
			$this->force_download($docx_location);
		}
	}

	private function createfile_pdf($b64 = "",$file_id = ""){
        $direktori = trim($this->settings_lib->item('site.pathsk'))."template/";
        $file_name = $file_id.".pdf";
        if (file_exists($direktori.$file_name)) {
           unlink($direktori.$file_name);
        }
        $bin = base64_decode($b64, true);
        file_put_contents($direktori.$file_name, $bin);
    }

	private function generate_pdf_from_word($file_word = ""){
        $output = shell_exec('unoconv -f pdf '.$file_word.' 2>&1');
    }

	public function download_nominatif()
	{
        $this->load->helper('aplikasi');
		$advanced_search_filters  = $_GET;
		if ($advanced_search_filters) {
			$filters = $advanced_search_filters;
			if ($filters['kawasans']){
				$this->db->group_start();
				$this->db->where_in('pegawai.kawasan_id',$filters['kawasans']);	
				$this->db->group_end();
			}
			if($filters['nip_sk']){
				$this->db->group_start();
				$this->db->where('pegawai.NIP_BARU',$filters['nip_sk']);	
				$this->db->group_end();
			}
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
			$tanggal 		= $filters['filter_tahun'] . "-" . $filters['filter_bulan'] . "-01";
			$records = $this->pegawai_model->getEstimasislks($tanggal, $filters);
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->setActiveSheetIndex(0);

			$type = PHPExcel_Cell_DataType::TYPE_STRING;

			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 1)->setValueExplicit("Nominasi Pegawai yang berhak menerima Penghargaan Satyalancana ".$records[0]->satya_tahun, $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 2)->setValueExplicit("Berdasarkan masa kerja per tanggal ".getIndonesiaFormat($tanggal), $type);
			$row = 4;

			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit("No", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit("Nama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit("NIP", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("Pangkat", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit("Golongan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit("Jabatan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit("Unit Kerja", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->setValueExplicit("Kawasan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("Masa Kerja", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $row)->setValueExplicit("Jenis Penghargaan", $type);
			$no = 1;
			// $keys = array_keys($records);
			if (isset($records) && is_array($records) && count($records)) :
				foreach ($records as $slks) {
					$row++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $slks->NAMA);
					$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($slks->NIP_BARU);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $slks->NAMA_PANGKAT);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $slks->NAMA_GOLONGAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $slks->NAMA_JABATAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $slks->NAMA_UNOR_FULL);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $slks->kawasan);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $slks->masa_kerja." Tahun");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, "Satyalancana Karya Satya ".$slks->satya_tahun." Tahun");
					$no++;
				}
			endif;

			$filename = "Nominatif Satyalancana  ".$records[0]->satya_tahun . $tanggal . ' ' . date('Ymdhi') . '.xls';
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