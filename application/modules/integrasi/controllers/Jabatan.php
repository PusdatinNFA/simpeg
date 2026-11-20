<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class Jabatan extends Admin_Controller
{
    protected $permissionUploadBkn   = 'riwayatjabatan.Kepegawaian.UploadBkn';
    protected $permissionEdit   = 'riwayatjabatan.Kepegawaian.Edit';
    protected $permissionView   = 'riwayatjabatan.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict($this->permissionUploadBkn);
        $this->load->model('pegawai/Riwayat_jabatan_model');
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
        Template::set('toolbar_title', "Integrasi Data Jabatan");
        Template::set_view("integrasi/jabatan");
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
                $this->db->where('coalesce(pengelola_sdm_kawasan_id,1)', $filters['pengelolasdm']);
            }
            if ($filters['nip'] != "") {
                $this->db->where('nip', $filters['nip']);
            }
            if ($filters['filter_unit_kerja'] != "") {
                $this->db->where('UNOR_INDUK_ID', $filters['filter_unit_kerja']);
            }   
            if ($filters['filter_tahun']){
                $this->db->where('extract(year from "tanggalSk") '.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('extract(year from "tanggalSk") '.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }
        $this->db->where('coalesce(sync_bkn,0)',0);
        $this->db->stop_cache();

        $total = $this->Riwayat_jabatan_model->countUnsyncBkn();
        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->Riwayat_jabatan_model->limit($length, $start);
        $records = $this->Riwayat_jabatan_model->getUnsyncBkn();
        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                if ($record->unorId && $record->jenisJabatan && $record->satuanKerjaId && $record->instansiId){
                    $row[]  = "<input name='id_data[]' class='checkbox-ak' type='checkbox' value='" . $record->id_tabel . "'>";
                } else {
                    $row[]  = "";
                }
                $row[]  = $nomor_urut;
                $row[]  = $record->nama . "<br>" . $record->nip;
                $row[]  = $record->namaJabatan;
                $row[]  = $record->jenisJabatanNama;
                $row[]  = $record->nomorSk."<br>".getIndonesiaFormat($record->tmtJabatan);
                $row[]  = $record->satker;
                $btn_actions = array();
                if ($record->id_arsip) {
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionEdit)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatjabatan/edit/" . $record->pnsId . '/' . $record->id_tabel . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-pencil'></i> </a>";
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
                $this->db->where('coalesce(pengelola_sdm_kawasan_id,1)', $filters['pengelolasdm']);
            }
            if ($filters['nip'] != "") {
                $this->db->where('nip', $filters['nip']);
            }
            if ($filters['filter_unit_kerja'] != "") {
                $this->db->where('satker_id', $filters['filter_unit_kerja']);
            }               
            if ($filters['filter_tahun']){
                $this->db->where('extract(year from "tanggalSk") '.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('extract(year from "tanggalSk") '.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }

        $records = $this->Riwayat_jabatan_model->getUnsyncBkn();
        return download_excel($records, "Data Data Jabatan");
    }
}
