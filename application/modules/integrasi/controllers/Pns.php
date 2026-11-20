<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Pns controller
 */
class Pns extends Admin_Controller
{
    protected $permissionUploadBkn   = 'Riwayatpns.Kepegawaian.UploadBkn';
    protected $permissionEdit   = 'Riwayatpns.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatpns.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict($this->permissionUploadBkn);
        $this->load->model('pegawai/riwayat_pns_cpns_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('masters/pengelola_sdm_kawasan_model');
        $pengelolasdms = $this->pengelola_sdm_kawasan_model->find_all();
        Template::set('pengelolasdms', $pengelolasdms);
    }

    /**
     * Display a list of jabatan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Integrasi Data PNS");
        Template::set_view("integrasi/pns");
        Template::render();
    }

    public function ajax_list()
    {
        $this->load->library('convert');
        $convert = new convert();
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');

        $length = $this->input->post('length');
        $start = $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";

        $advanced_search_filters  = $this->input->post("advanced_search_filters");
        $this->db->start_cache();
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as  $filter) {
                $filters[$filter['name']] = $filter["value"];
            }
            if ($filters['pengelolasdm'] != "") {
                if ($filters['pengelolasdm'] == 1) {
                    $this->db->group_start();
                    $this->db->where('pengelola_sdm_kawasan_id', $filters['pengelolasdm']);
                    $this->db->or_where('pengelola_sdm_kawasan_id is null');
                    $this->db->group_end();
                } else
                    $this->db->where('pengelola_sdm_kawasan_id', $filters['pengelolasdm']);
            }
            if ($filters['nip'] != "") {
                $this->db->where('NIP_BARU', $filters['nip']);
            }
            if ($filters['filter_unit_kerja'] != "") {
                $this->db->where('satker_id', $filters['filter_unit_kerja']);
            }
            if ($filters['filter_tahun']){
                $this->db->where('EXTRACT(year from "TMT_CPNS") '.$filters['operator_tahun'].$filters['filter_tahun']);
            }
        }
        $this->db->stop_cache();

        $total = $this->riwayat_pns_cpns_model->countUnsyncBkn();
        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->riwayat_pns_cpns_model->limit($length, $start);
        $records = $this->riwayat_pns_cpns_model->getUnsyncBkn();
        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                if ($record->TMT_PNS && $record->NOMOR_SK_CPNS && $record->TGL_SK_CPNS)
                    $row[]  = "<input name='id_data[]' class='checkbox-ak' type='checkbox' value='" . $record->id_tabel . "'>";
                else $row[] = "";
                $row[]  = $nomor_urut;
                $row[]  = $record->NAMA . "<br>" . $record->NIP_BARU;
                $row[]  = $record->TMT_PNS;
                $row[]  = $record->NO_SK_PNS . "<br><i>TANGGAL : " . $record->TGL_SK_PNS . "</i>";
                $row[]  = $record->NAMA_UNOR_FULL;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-warning'  target='_BLANK'><i class='glyphicon glyphicon-user'></i> </a>"; 
                if ($record->id_arsip != "") {
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionEdit)) {
                    // $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_angka_kredit/edit/" . $record->id_tabel . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-pencil'></i> </a>";
                }
                $btn_actions[] = "<btn data-id='" . $record->PNS_ID . "' data-toggle='tooltip' title='Get data' tooltip='Get data' class='btn btn-sm btn-info btn-download'><i class='fa fa-download'></i> </btn>";
                
                $row[] = "<div class='btn-group'>" . implode(" ", $btn_actions) . "</div>";


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
            if ($filters['pengelolasdm'] != "") {
                if ($filters['pengelolasdm'] == 1) {
                    $this->db->group_start();
                    $this->db->where('pengelola_sdm_kawasan_id', $filters['pengelolasdm']);
                    $this->db->or_where('pengelola_sdm_kawasan_id is null');
                    $this->db->group_end();
                } else
                    $this->db->where('pengelola_sdm_kawasan_id', $filters['pengelolasdm']);
            }
            if ($filters['nip'] != "") {
                $this->db->where('nip', $filters['nip']);
            }
            if ($filters['filter_unit_kerja'] != "") {
                $this->db->where('satker_id', $filters['filter_unit_kerja']);
            }
            
            if ($filters['filter_tahun']){
                $this->db->where('tahunMulaiPenilaian'.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('tahunSelesaiPenilaian'.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }

        $records = $this->riwayat_pns_cpns_model->getUnsyncBkn();
        return download_excel($records, "Data Angka Kredit");
    }
}
