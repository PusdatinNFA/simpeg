<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Penghargaan controller
 */
class Penghargaan extends Admin_Controller
{
    protected $permissionUploadBkn   = 'Riwayatpenghargaan.Kepegawaian.UploadBkn';
    protected $permissionEdit   = 'Riwayatpenghargaan.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatpenghargaan.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict($this->permissionUploadBkn);
        $this->load->model('pegawai/riwayat_penghargaan_model');
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
        Template::set('toolbar_title', "Integrasi Penghargaan");
        Template::set_view("integrasi/penghargaan");
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
                $this->db->where('nip', $filters['nip']);
            }
            if ($filters['filter_unit_kerja'] != "") {
                $this->db->where('UNOR_ID', $filters['filter_unit_kerja']);
            }         
            if ($filters['filter_tahun']){
                $this->db->where('tahun '.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('tahun '.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }
        $this->riwayat_penghargaan_model->where('coalesce(sync_bkn,0)',0);
        $this->db->stop_cache();
        $total = $this->riwayat_penghargaan_model->countUnsyncBkn();
        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->riwayat_penghargaan_model->limit($length, $start);
        $records = $this->riwayat_penghargaan_model->getUnsyncBkn();
        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                if ($record->skNomor && $record->hargaId && $record->skDate && $record->tahun)
                    $row[]  = "<input name='id_data[]' class='checkbox-ak' type='checkbox' value='" . $record->id_tabel . "'>";
                else $row[] = "";
                $row[]  = $nomor_urut;
                $row[]  = $record->NAMA . "<br>" . $record->nip;
                $row[]  = $record->skNomor . "<br><i>TANGGAL : " . getIndonesiaFormat($record->skDate) . "</i>";
                $row[]  = $record->hargaNama;
                $row[]  = $record->tahun;
                $row[]  = $record->NAMA_UNOR_FULL;
                $btn_actions = array();
                if ($record->id_arsip != "") {
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionEdit)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/Riwayatpenghargaan/edit/".$record->pnsOrangId. '/' . $record->id_tabel . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-pencil'></i> </a>";
                }
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
                $this->db->where('tahun '.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('tahun '.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }

        $records = $this->riwayat_penghargaan_model->getUnsyncBkn();
        return download_excel($records, "Data Penghargaan");
    }
}
