<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_kgb extends Admin_Controller
{
    protected $permissionCreate = 'riwayatkgb.Kepegawaian.Create';
    protected $permissionDelete = 'riwayatkgb.Kepegawaian.Delete';
    protected $permissionEdit   = 'riwayatkgb.Kepegawaian.Edit';
    protected $permissionView   = 'riwayatkgb.Kepegawaian.View';
    protected $permissionPrint   = 'riwayatkgb.Kepegawaian.Print';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_kgb_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/golongan_model');
        $this->load->model('pegawai/lokasi_model');
        $this->load->model('pegawai/jenis_jabatan_model');
        $this->load->model('ref_jabatan/jabatan_model');
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
        Template::set('jenis_jabatans', $jenis_jabatans);
        
        $this->load->model('pegawai/unitkerja_model');
        
    }
    public function ajax_list(){
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');

        $this->pegawai_model->where("PNS_ID",$PNS_ID);
        $pegawai_data = $this->pegawai_model->find_first_row();
       
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->riwayat_kgb_model->where("pegawai_nip",$pegawai_data->NIP_BARU);
        $total= $this->riwayat_kgb_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        
        
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->riwayat_kgb_model->where('upper("SEBAGAI") LIKE \''.strtoupper($search).'%\'');
        }
        $this->riwayat_kgb_model->limit($length,$start);
        
        $this->riwayat_kgb_model->where("pegawai_nip",$pegawai_data->NIP_BARU); 
        $records=$this->riwayat_kgb_model->find_all();
            
        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->riwayat_kgb_model->where('upper("SEBAGAI") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->riwayat_kgb_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        $this->load->helper('aplikasi');
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                
                $row []  = $convert->fmtDate($record->tmt_sk,"dd-mm-yyyy");
                $row []  = $record->no_sk;
                $row []  = $convert->fmtDate($record->tgl_sk,"dd-mm-yyyy");
                $row []  = $record->n_gol_ruang;
                $row []  = rupiah($record->n_gapok);

                $btn_actions = array();
                if($record->KETERANGAN_BERKAS != ""){
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/viewdoc/".$record->id."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen ".$record->no_sk."' class='btn btn-sm btn-info show-modal'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionPrint))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."/pdf' data-toggle='tooltip' title='Print SK' tooltip='Print SK' class='btn btn-sm btn-warning show-modal'><i class='fa fa-print'></i> </a>";
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/printsk/".$record->ref."' data-toggle='tooltip' title='Download Dokumen word' tooltip='Download word' class='btn btn-sm btn-info'><i class='fa fa-download'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayat_kgb/edit/".$record->ref."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-kgb'><i class='fa fa-trash-o'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function show($PNS_ID,$record_id=''){
        if(empty($record_id)){
            $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_kgb_crud");
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat KGB");
            Template::render();
        }
        else {
            $datadetil = $this->riwayat_kgb_model->find($record_id); 
            $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_kgb_crud");
           
            Template::set('detail_riwayat', $datadetil);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat KGB");

            Template::render();
        }
    }
    public function list_satker(){
        $this->db->order_by('NAMA_UNOR',"asc");
        $satkers = $this->unitkerja_model->find_satker();
        $data = array();
        foreach($satkers as $satker){
            //echo json_encode($satker);
            $data[] = array(
                'id'=>$satker->ID,
                'text'=>$satker->NAMA_UNOR,
            );
        }
        $output = array(
            'results'=>$data 
        );
        echo json_encode($output);
    }
    public function add($PNS_ID){
        $list_golongan = $this->golongan_model->find_all();
        Template::set('list_golongan',$list_golongan);
        $this->show($PNS_ID);
    }
    public function edit($ref_id=''){
        $kgb_data = $this->riwayat_kgb_model->find_by("ref",$ref_id);
        $pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$kgb_data->pegawai_nip);
        $list_golongan = $this->golongan_model->find_all();
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        Template::set_view("kepegawaian/riwayat_kgb_crud");
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('list_golongan',$list_golongan);
        Template::set('toolbar_title', "Ubah Riwayat KGB");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
    }
    public function printsk($ref_id='',$type = ""){
        $this->auth->restrict($this->permissionCreate);
        $this->load->helper('aplikasi');
        $kgb_data = $this->riwayat_kgb_model->find_ref($ref_id);
        
        $pegawai_data       = $this->pegawai_model->find_by("NIP_BARU",trim($kgb_data->pegawai_nip));
        $sk_sebelum         = $this->riwayat_kgb_model->getLastRiwayat($kgb_data->pegawai_nip,$kgb_data->tmt_sk);
        $golongan_terakhir  = $this->golongan_model->getGolongan($sk_sebelum->golongan_id);
        $list_golongan = $this->golongan_model->getGolongan($kgb_data->n_golongan_id);
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        $template_data= $this->riwayat_kgb_model->getTemplate(TEMPLATE_SK_KGB_KOLEKTIF);
        if(!$kgb_data){
            $output = array(
                'success'=>false,
                'message'=>'Data tidak ditemukan'
            );
            echo json_encode($output);
            die();
        }
        // header('Set-Cookie: fileDownload=true; path=/');
        $gaji_baru = $this->riwayat_kgb_model->getWage($kgb_data->golongan2_name,$kgb_data->n_masakerja_thn);
        $this->load->library('Template_doc_lib');
        $TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
        $tembusan_data = array();
        $tembusan_data []['name'] = 'Kepala Badan Kepegawaian Negara, u.p.Deputi Bidang Sistem Informasi Kepegawaian di Jakarta;';
        $tembusan_data []['name'] = 'Kepala Biro Perencanaan dan Keuangan BRIN di Jakarta;';
        $tembusan_data []['name'] = 'Kepala Unit Kerja terkait di Lingkungan BRIN;';
        $tembusan_data []['name'] = 'Pegawai yang bersangkutan';
        $TBS->MergeBlock('tb', $tembusan_data);
        $sk_data = array(
            'p_nip'=>$kgb_data->nip,
            'no_sk'=>$kgb_data->no_sk,
            'dt_header'=>getIndonesiaFormat($kgb_data->tgl_sk),
            'pre'=>"",
            'name_bergelar'=>$pegawai_data->NAMA,
            'nip'=>$pegawai_data->NIP_BARU,
            'location'=>"Jakarta",
            'to'=>'Kantor Pelayanan Perbendaharaan Negara II',
            'kantor_kppn_region'=>$kgb_data->kantor_pembayaran ?$kgb_data->kantor_pembayaran : "Jakarta",
            'tgl_sk'=>date('d-m-Y'),
            'tempat'=>'Jakarta',
            'p_birth_place'=>'',
            'p_birth_date'=>'',
            'lokasi_sk'=>'',
            'pp'=>'PP No 30 Tahun 2015',
            'masa_kerja_thn'=>$kgb_data->n_masakerja_thn,
            'masa_kerja_bln'=>$kgb_data->n_masakerja_bln,
            'tmt'                   => getIndonesiaFormat($kgb_data->tmt_sk),
            'pangkat_text'          => $list_golongan->NAMA_PANGKAT . "-".$list_golongan->NAMA,
            'jabatan_text'          => $kgb_data->n_jabatan_text,
            'gapok_baru_number'     => rupiah($kgb_data->n_gapok),
            'gapok_baru_text'       => Terbilang($kgb_data->n_gapok),
            'gapok_lama_number'     => rupiah($sk_sebelum->n_gapok),
            'gapok_lama_text'       => Terbilang($sk_sebelum->n_gapok),
            'satuan_kerja'          => $kgb_data->unit_kerja_text,
            'p_instansi'            => '',
            'kepala_satker_ybs'     => '',
            'foto'                  => "",
            'lastpangkat_tgl_sk'    => $sk_sebelum->tgl_sk ? getIndonesiaFormat($sk_sebelum->tgl_sk) : "",
            'lastpangkat_no_sk'     => $sk_sebelum->no_sk ? $sk_sebelum->no_sk : "",
            'lastpangkat_tmt'       => $sk_sebelum->tmt_sk ? getIndonesiaFormat($sk_sebelum->tmt_sk) : "",
            'lastpangkat_wpy'       => $sk_sebelum->masa_kerja_thn != "" ? $sk_sebelum->masa_kerja_thn : "0",
            'lastpangkat_wpm'       => $sk_sebelum->masa_kerja_bln != "" ? $sk_sebelum->masa_kerja_bln : "0",
            'gol_notation'          => $list_golongan->NAMA_PANGKAT."-".$list_golongan->NAMA,
            'pejabat'               => $kgb_data->nama_pejabat_lama,
            'pejabat_baru'          => $kgb_data->nama_pejabat,
        );
        // print_r($sk_data);
        $sk_datas = array();
        $sk_datas[] = $sk_data;
        $TBS->MergeBlock('o',$sk_datas);
        //$TBS->MergeField('r', $sk_data);
        $output_file_name = $kgb_data->ref;
        if($type=="pdf"){
            $dir = trim($this->settings_lib->item('site.pathsk'))."kgb";
            $fullpath_docx =  $dir.DIRECTORY_SEPARATOR.$output_file_name.".docx";
            $fullpath_pdf =  $dir.DIRECTORY_SEPARATOR.$output_file_name.".pdf";
            $TBS->Show(OPENTBS_FILE,$fullpath_docx);
            $output = shell_exec('unoconv -f pdf '.$fullpath_docx.' 2>&1');
            $data_base64 = file_get_contents($fullpath_pdf);
            $base64 = 'data:application/pdf;base64,' . base64_encode($data_base64);
            echo '<embed src="'.$base64.'" width="100%" height="700" alt="pdf">';

            // Header content type
            // header('Content-type: application/pdf');
            // header('Content-Disposition: inline; filename="' . $output_file_name . '"');
            // header('Content-Transfer-Encoding: binary');
            // header('Accept-Ranges: bytes');
            // // Read the file
            // @readfile($fullpath_pdf);
            die();
        }
        else {
            $output_file_name = $output_file_name.".docx";
            $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.  
        }
    }
    public function create_sk($type,$pegawai_nip,$tanggal){
        $this->auth->restrict($this->permissionCreate);
        $this->load->helper('aplikasi');
        $golongan           = $this->input->get("golongan");
        $mk_tahun           = $this->input->get("mk_tahun");
        $pegawai_data       = $this->pegawai_model->find_by("NIP_BARU",trim($pegawai_nip));
        $datajabatan        = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawai_data->JABATAN_INSTANSI_REAL_ID));
        $sk_sebelum         = $this->riwayat_kgb_model->getLastRiwayat($pegawai_nip,$tanggal);
        $wages              = $this->riwayat_kgb_model->getWage($golongan,$mk_tahun);
        $list_golongan      = $this->golongan_model->getGolongan($sk_sebelum->golongan_id);

        $unit_kerja_induk_data          = $this->unitkerja_model->find($pegawai_data->UNOR_ID);
        $template_data                  = $this->riwayat_kgb_model->getTemplate(TEMPLATE_SK_KGB_KOLEKTIF);
        // save to riwayat KGB baru
        $no_sk                          = $this->create_nomor();
        $data   = array();
        $data["no_sk"]                  = $no_sk;
        $data["pegawai_nip"]            = $pegawai_data->NIP_BARU;
        $data["pegawai_nama"]           = $pegawai_data->NAMA;
        $data["birth_place"]            = $lokasi_lahir_data->NAMA;
        $data["birth_date"]             = $pegawai_data->TGL_LAHIR;
        $data["pegawai_id"]             = $pegawai_data->ID;
        $data["n_jabatan_text"]         = $datajabatan->NAMA_JABATAN;
        $data["n_gol_ruang"]            = $list_golongan->NAMA_PANGKAT;
        $data["unit_kerja_text"]        = $unit_kerja_induk_data->NAMA_UNOR;
        $data["unit_kerja_induk_text"]  = $unit_kerja_induk_data->ABBREVIATION;
        $data["unit_kerja_induk_id"]    = $unit_kerja_induk_data->UNOR_INDUK;
        $data["kantor_pembayaran"]      = "KPPN II";
        $data["tgl_sk"]                 = date("Y-m-d");
        $data["tmt_sk"]                 = $tanggal;
        $data["n_golongan_id"]          = $list_golongan->ID;
        $data["id_golongan"]            = $golongan;
        $data["n_masakerja_thn"]        = $mk_tahun;
        $data["n_masakerja_bln"]        = 0;
        $data["n_gapok"]                = $wages->BASIC;
        $data["pejabat"]                =  trim($this->settings_lib->item('site.pejabat_jabatan'));
        $data['nama_pejabat']           = trim($this->settings_lib->item('site.pejabat_kgb_nama'));
        $data['nama_pejabat_lama']      = $sk_sebelum->nama_pejabat; // ambil dari nama pejabat dari sk yang lama
        // jabatan terakhir
        $data["o_gol_ruang"]            = $list_golongan->NAMA_PANGKAT." - ".$list_golongan->NAMA;
        $data["o_gol_tmt"]              = $sk_sebelum->tmt_sk;
        $data["o_masakerja_thn"]        = $sk_sebelum->masa_kerja_thn;
        $data["o_masakerja_bln"]        = $sk_sebelum->masa_kerja_bln != "" ? $sk_sebelum->masa_kerja_bln : 0;
        if(empty($data["tgl_sk"])){
            unset($data["tgl_sk"]);
        }
        if(empty($data["tmt_sk"])){
            unset($data["tmt_sk"]);
        }
        // print_r($data);
        // die($no_sk);
        if(!$this->riwayat_kgb_model->is_uniq($pegawai_data->NIP_BARU,$list_golongan->ID,$mk_tahun,0)){
            $id = $this->riwayat_kgb_model->insert($data);    
            if($id){
                $this->update_nomor($nomor_kgb);
            }
            // end save
            $kgb_data = $this->riwayat_kgb_model->find($id);
            $ref_id = $kgb_data->ref;
            if(!$kgb_data){
                $output = array(
                    'success'=>false,
                    'message'=>'Data tidak ditemukan'
                );
                echo json_encode($output);
                die();
            }
            // header('Set-Cookie: fileDownload=true; path=/');
            $gaji_baru = $this->riwayat_kgb_model->getWage($kgb_data->golongan2_name,$kgb_data->n_masakerja_thn);
            $this->load->library('Template_doc_lib');
            $TBS = $this->template_doc_lib->gen_xls(BASEPATH."../../assets/templates/".$template_data->template_file);
            $tembusan_data = array();
            $tembusan_data []['name'] = 'Kepala Badan Kepegawaian Negara, u.p.Deputi Bidang Sistem Informasi Kepegawaian di Jakarta;';
            $tembusan_data []['name'] = 'Kepala Biro Perencanaan dan Keuangan BRIN di Jakarta;';
            $tembusan_data []['name'] = 'Kepala Unit Kerja terkait di Lingkungan BRIN;';
            $tembusan_data []['name'] = 'Pegawai yang bersangkutan';
            $TBS->MergeBlock('tb', $tembusan_data);
            $sk_data = array(
                'p_nip'                 => $kgb_data->nip,
                'no_sk'                 => $kgb_data->no_sk,
                'dt_header'             => getIndonesiaFormat($kgb_data->tgl_sk),
                'pre'                   => "",
                'name_bergelar'         => $pegawai_data->NAMA,
                'nip'                   => $pegawai_data->NIP_BARU,
                'location'              => "Jakarta",
                'to'                    => 'Kepala Kantor Pelayanan Perbendaharaan Negara',
                'kantor_kppn_region'    => $kgb_data->kantor_pembayaran,
                'tgl_sk'                => date('d-m-Y'),
                'tempat'                => 'Jakarta',
                'p_birth_place'         => '',
                'p_birth_date'          => '',
                'lokasi_sk'             => '',
                'pp'                    => 'PP No 30 Tahun 2015',
                'masa_kerja_thn'        => $kgb_data->n_masakerja_thn,
                'masa_kerja_bln'        => $kgb_data->n_masakerja_bln,
                'tmt'                   => getIndonesiaFormat($kgb_data->tmt_sk),
                'pangkat_text'          => $list_golongan->NAMA_PANGKAT . "-".$list_golongan->NAMA,
                'jabatan_text'          => $kgb_data->n_jabatan_text,
                'gapok_baru_number'     => rupiah($kgb_data->n_gapok),
                'gapok_baru_text'       => Terbilang($kgb_data->n_gapok),
                'gapok_lama_number'     => rupiah($sk_sebelum->n_gapok),
                'gapok_lama_text'       => Terbilang($sk_sebelum->n_gapok),
                'satuan_kerja'          => $kgb_data->unit_kerja_text,
                'p_instansi'            => '',
                'kepala_satker_ybs'     => '',
                'foto'                  => "",
                'lastpangkat_tgl_sk'    => $sk_sebelum->tgl_sk ? getIndonesiaFormat($sk_sebelum->tgl_sk) : "",
                'lastpangkat_no_sk'     => $sk_sebelum->no_sk ? $sk_sebelum->no_sk : "",
                'lastpangkat_tmt'       => $sk_sebelum->tmt_sk ? getIndonesiaFormat($sk_sebelum->tmt_sk) : "",
                'lastpangkat_wpy'       => $sk_sebelum->masa_kerja_thn != "" ? $sk_sebelum->masa_kerja_thn : "0",
                'lastpangkat_wpm'       => $sk_sebelum->masa_kerja_bln != "" ? $sk_sebelum->masa_kerja_bln : "0",
                'gol_notation'          => $list_golongan->NAMA_PANGKAT."-".$list_golongan->NAMA,
                'pejabat'               => $kgb_data->nama_pejabat_lama,
                'pejabat_baru'          => $kgb_data->nama_pejabat,
            );
            // print_r($sk_data);
            $sk_datas = array();
            $sk_datas[] = $sk_data;
            $TBS->MergeBlock('o',$sk_datas);
            //$TBS->MergeField('r', $sk_data);
            $output_file_name = $kgb_data->ref;
            if($type=="pdf"){
                $dir = trim($this->settings_lib->item('site.pathsk'))."kgb";
                $fullpath_docx  = $dir.DIRECTORY_SEPARATOR.$output_file_name.".docx";
                $fullpath_pdf   = $dir.DIRECTORY_SEPARATOR.$output_file_name.".pdf";
                $TBS->Show(OPENTBS_FILE,$fullpath_docx);
                $output         = shell_exec('unoconv -f pdf '.$fullpath_docx.' 2>&1');
                $data_base64    = file_get_contents($fullpath_pdf);
                $base64         = 'data:application/pdf;base64,' . base64_encode($data_base64);
                echo '<embed src="'.$base64.'" width="100%" height="700" alt="pdf">';

                // Header content type
                // header('Content-type: application/pdf');
                // header('Content-Disposition: inline; filename="' . $output_file_name . '"');
                // header('Content-Transfer-Encoding: binary');
                // header('Accept-Ranges: bytes');
                // // Read the file
                // @readfile($fullpath_pdf);
                die();
            }
            else {
                $output_file_name = $output_file_name.".docx";
                $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.  
            }
        }else{
            die("SK sudah pernah di print, silahkan print ulang");
        }
    }
    private function create_nomor(){
        $this->load->helper('aplikasi');
        $nomor_terakhir = trim($this->settings_lib->item('site.nomor_kgb'));
        $format_nomor   = trim($this->settings_lib->item('site.format_kgb'));
        $nomor          = (int)$nomor_terakhir+1;
        $tahun          = date("Y");
        $bulan          = getRomawiBulan((int)date("m"));
        $param = array($nomor, $bulan,$tahun);
        return sprintf($format_nomor,$nomor,$bulan,$tahun);
    }
    private function update_nomor($nomor){
        $nomor_terakhir = trim($this->settings_lib->item('site.nomor_kgb'));
        $nomor          = (int)$nomor_terakhir+1;
        $this->load->model('settings/settings_model', 'settings_model');
        $dataupdate = array();
        $dataupdate["value"] = $nomor;
        $this->settings_model->update_where("name",'site.nomor_kgb', $dataupdate);
    }
    public function detil($ref_id=''){
        $list_golongan = $this->golongan_model->find_all();
        $this->riwayat_kgb_model->where("ref",$ref_id);
        $kgb_data = $this->riwayat_kgb_model->first_row();  
        $selectedUnitKerjaInduk = $this->unitkerja_model->find_by_id($kgb_data->unit_kerja_induk_id);
        
        $this->pegawai_model->where("ID",$kgb_data->pegawai_id);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $this->auth->restrict($this->permissionEdit);
        Template::set_view("kepegawaian/riwayat_kgb_detil");
        Template::set('list_golongan',$list_golongan);
        Template::set('detail_riwayat', $kgb_data);    
        Template::set('PNS_ID', $pegawai_data->PNS_ID);
        Template::set('toolbar_title', "Riwayat Pekerjaan");
        Template::set('selectedUnitKerjaInduk',$selectedUnitKerjaInduk);   
        Template::render();
        Template::render();
    }
    
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_kgb_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        
        
        $data = $this->riwayat_kgb_model->prep_data($this->input->post());
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name  = $_FILES['file_dokumen']['name'];
            // $file_name =$_FILES['image']['tmp_name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            //echo $file_ext[1];echo "<br>";
            $data_base64 = file_get_contents($file_tmp);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data_base64);

            if(in_array(end($file_ext),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        }
        if($base64 == "")
            unset($data['FILE_BASE64']);
        else{
            $data['FILE_BASE64']        = $base64;
            $data['KETERANGAN_BERKAS']  = isset($file_ext[$jmltitik-1]) ? $file_ext[$jmltitik-1]."  ".$type. " ".$file_size." KB" : null;
        }
        // end upload berkas
        $pegawai_data = $this->pegawai_model->find_by("PNS_ID",$this->input->post("PNS_ID"));
        $golongan_data = $this->golongan_model->find($this->input->post('n_golongan_id'));
        $unit_kerja_induk_data = $this->unitkerja_model->find($this->input->post('unit_kerja_induk_id'));
        $lokasi_lahir_data = $this->lokasi_model->find($pegawai_data->TEMPAT_LAHIR_ID);
        $data["pegawai_nip"] = $pegawai_data->NIP_BARU;
        $data["pegawai_nama"] = $pegawai_data->NAMA;
        $data["birth_place"] = $lokasi_lahir_data->NAMA;
        $data["birth_date"] = $pegawai_data->TGL_LAHIR;
        $data["pegawai_id"] = $pegawai_data->ID;
        $data["n_gol_ruang"] = $golongan_data->NAMA_PANGKAT." - ".$golongan_data->NAMA;
        $data["unit_kerja_text"]        = $unit_kerja_induk_data->NAMA_UNOR;
        $data["unit_kerja_induk_text"]  = $unit_kerja_induk_data->ABBREVIATION;
        
        if(empty($data["tgl_sk"])){
            unset($data["tgl_sk"]);
        }
        if(empty($data["tmt_sk"])){
            unset($data["tmt_sk"]);
        }
        if(empty($data["last_education_date"])){
            unset($data["last_education_date"]);
        }
        if(empty($data["n_tmt_jabatan"])){
            unset($data["n_tmt_jabatan"]);
        }
        
        $id_data = $this->input->post("id");
        if(isset($id_data) && !empty($id_data)){
            $this->riwayat_kgb_model->update($id_data,$data);
        }
        else $this->riwayat_kgb_model->insert($data);
        $response ['success']= true;
        $response ['msg']= "berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
        if ($this->riwayat_kgb_model->delete($record_id)) {
             log_activity($this->auth->user_id(), 'delete data Riwayat KGB : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
             Template::set_message("Sukses Hapus data", 'success');
             echo "Sukses";
        }else{
            echo "Gagal";
        }

        exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_kgb");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    public function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_kgb_model->find($id);
        $FILE_BASE64 = $datadetil->FILE_BASE64;
        echo '<embed src="'.$FILE_BASE64.'" width="100%" height="700" alt="pdf">';
        die();
    }
}
