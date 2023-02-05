<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Pensiun extends Admin_Controller
{
	protected $permissionViewPensiunInstansi   = 'Nominatif.Pensiun.View';
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
    	Template::set('toolbar_title', "Nominatif Pensiun");
		Template::set("collapse",true);
		Template::set_view("data_nominatif/v_pensiun");
        Template::render();
	}

    public function ajax_list(){
        $this->load->helper('aplikasi');
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        
        $advanced_search_filters  = $this->input->post("advanced_search_filters");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
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
        
        $this->pegawai_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol,$sSortCol);
		$tanggal 		= $filters['filter_tahun']."-".$filters['filter_bulan']."-01";

        $total =$this->pegawai_model->count_estimasi_pensiun_perbulan($this->UNOR_ID,$tanggal);
        $output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
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
        $records=$this->pegawai_model->find_estimasi_pensiun_perbulan($this->UNOR_ID,$tanggal);
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                // die(json_encode($record));
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='70%'>";
                $row []  = $record->NAMA.'<br>'.$record->NIP_BARU;
                // $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = getIndonesiaFormat($record->TGL_LAHIR);
                $row []  = $record->umur;
                $row []  = getIndonesiaFormat($record->tahunpensiun);
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                    
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();        
    }

    public function download_nominatif()
	{
        $this->load->helper('aplikasi');
		$advanced_search_filters  = $_GET;
		if ($advanced_search_filters) {
			$filters = $advanced_search_filters;

			$tanggal 		= $filters['filter_tahun'] . "-" . $filters['filter_bulan'] . "-01";
			
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
			$records = $this->pegawai_model->find_estimasi_pensiun_perbulan($filters['filter_unit_kerja'],$tanggal);
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
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit("Tanggal Lahir", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->setValueExplicit("Usia", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("BUP", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $row)->setValueExplicit("TMT Pensiun", $type);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $row)->setValueExplicit("Satuan Kerja", $type);
			$no = 1;
			// $keys = array_keys($records);
			if (isset($records) && is_array($records) && count($records)) :
				foreach ($records as $record) {
					$row++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $record->NAMA);
					$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($record->NIP_BARU);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $record->pangkat);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $record->golongan);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $record->NAMA_JABATAN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $record->TGL_LAHIR?getIndonesiaFormat($record->TGL_LAHIR):"");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $record->umur);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $record->PENSIUN);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, getIndonesiaFormat($record->tahunpensiun));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $record->NAMA_UNOR);
					$no++;
				}
			endif;

			$filename = "Nominatif Pensiun  " . $tanggal . ' ' . date('Ymdhi') . '.xls';
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