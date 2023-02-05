<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Hukdis extends Admin_Controller
{
	protected $permissionView   = 'Nominatif.Hukdis.View';
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
        $this->load->model('pegawai/jenis_hukuman_model');
        $this->load->model('pegawai/riwayat_huk_dis_model');
        $jenis_hukumans = $this->jenis_hukuman_model->find_all();
		Template::set('jenis_hukumans', $jenis_hukumans);
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
    	Template::set('toolbar_title', "Hukuman Disiplin");
		Template::set("collapse",true);
		Template::set_view("data_nominatif/v_hukdis");
        Template::render();
	}

    public function ajax_list(){

        $this->load->helper('aplikasi');
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $this->db->start_cache();
        $advanced_search_filters  = $this->input->post("advanced_search_filters");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}

            if ($filters['ID_JENIS_HUKUMAN']){
                $this->riwayat_huk_dis_model->where('ID_JENIS_HUKUMAN',$filters['ID_JENIS_HUKUMAN']);
            }
            if ($filters['filter_kategori']){
                $this->riwayat_huk_dis_model->where('jenis_hukuman.TINGKAT_HUKUMAN',$filters['filter_kategori']);
            }
            if ($filters['nip']){
                $this->riwayat_huk_dis_model->where('pegawai.NIP_BARU',$filters['nip']);
            }
            if ($filters['filter_unit_kerja']){
                $this->riwayat_huk_dis_model->where('pegawai.UNOR_INDUK_ID',$filters['filter_unit_kerja']);
            }
            if ($filters['filter_tahun']){
                $this->riwayat_huk_dis_model->where('extract(year from "SK_TANGGAL") = '.$filters['filter_tahun']);
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
        $this->db->stop_cache();
        
        $this->riwayat_huk_dis_model->limit($length,$start);
        
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol,$sSortCol);
        

        $total= $this->riwayat_huk_dis_model->count_all();
        $output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();

        $records=$this->riwayat_huk_dis_model->find_detil();

        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                // die(json_encode($record));
                $tingkat = "<label class='label label-info'>Ringan</label>";
                if ($record->TINGKAT_HUKUMAN == 'B')                    
                    $tingkat = "<label class='label label-danger'>Berat</label>";
                if ($record->TINGKAT_HUKUMAN == 'S')
                    $tingkat = "<label class='label label-warning'>Sedang</label>";
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='70%'>";
                $row []  = $record->NAMA.'<br>'.$record->NIP_BARU;
                $row []  = $record->SK_NOMOR;
                $row []  = $tingkat.'<br>'.$record->nama_hukuman;
                $row []  = getIndonesiaFormat($record->SK_TANGGAL).'<br>TMT :'.($record->TANGGAL_MULAI_HUKUMAN?getIndonesiaFormat($record->TANGGAL_MULAI_HUKUMAN):"-");
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                if ($record->id_arsip)
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' class='btn btn-sm btn-warning modal-custom-global'><i class='fa fa-eye'></i> </a>"; 
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info' target='_BLANK'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                    
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();        
    }

    public function download()
	{
        $this->load->helper('aplikasi');
		$advanced_search_filters  = $_GET;
		if ($advanced_search_filters) {
			$filters = $advanced_search_filters;

            if ($filters['ID_JENIS_HUKUMAN']){
                $this->riwayat_huk_dis_model->where('ID_JENIS_HUKUMAN',$filters['ID_JENIS_HUKUMAN']);
            }
            if ($filters['nip']){
                $this->riwayat_huk_dis_model->where('pegawai.NIP_BARU',$filters['nip']);
            }
            if ($filters['filter_unit_kerja']){
                $this->riwayat_huk_dis_model->where('pegawai.UNOR_INDUK_ID',$filters['filter_unit_kerja']);
            }
            if ($filters['filter_tahun']){
                $this->riwayat_huk_dis_model->where('extract(year from "SK_TANGGAL") = '.$filters['filter_tahun']);
            }
            if ($filters['filter_kategori']){
                $this->riwayat_huk_dis_model->where('jenis_hukuman.TINGKAT_HUKUMAN',$filters['filter_kategori']);
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
            $records=$this->riwayat_huk_dis_model->find_detil();
			$this->load->library('Excel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->setActiveSheetIndex(0);


			$type = PHPExcel_Cell_DataType::TYPE_STRING;
			$row = 1;

			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit("No", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit("Nama", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit("NIP", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("Nomor SK", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit("Tanggal SK", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit("Kategori", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit("Jenis Hukuman", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->setValueExplicit("Tanggal Mulai Hukuman", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("Tanggal Akhir Hukuman", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $row)->setValueExplicit("Masa Tahun", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $row)->setValueExplicit("Masa Bulan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $row)->setValueExplicit("No SK Pembatalan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $row)->setValueExplicit("Tanggal SK Pembatalan", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $row)->setValueExplicit("Satuan Kerja", $type);
			$no = 1;
			// $keys = array_keys($records);
			if (isset($records) && is_array($records) && count($records)) :
				foreach ($records as $record) {
					$row++;
                    $tingkat = "Ringan";
                    if ($record->TINGKAT_HUKUMAN == 'B')                    
                        $tingkat = "Berat";
                    if ($record->TINGKAT_HUKUMAN == 'S')
                        $tingkat = "Sedang";
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $record->NAMA);
					$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($record->NIP_BARU);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $record->SK_NOMOR);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, getIndonesiaFormat($record->SK_TANGGAL));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $tingkat);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $record->nama_hukuman);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, getIndonesiaFormat($record->TANGGAL_MULAI_HUKUMAN));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, getIndonesiaFormat($record->TANGGAL_AKHIR_HUKUMAN));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $record->MASA_TAHUN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $record->MASA_BULAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $record->NO_SK_PEMBATALAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row,getIndonesiaFormat($record->TANAGGAL_SK_PEMBATALAN));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $record->NAMA_UNOR);
					$no++;
				}
			endif;

			$filename = "Data Hukuman Disiplin " . date('Ymdhi') . '.xls';
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