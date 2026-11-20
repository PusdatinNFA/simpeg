<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Reports controller
 */
class Penghargaan extends Admin_Controller
{
    protected $permissionView = 'RiwayatPenghargaan.Kepegawaian.Rekap';
    protected $permissionEdit = 'Riwayatpenghargaan.Kepegawaian.Edit';
    protected $permissionDelete = 'Riwayatpenghargaan.Kepegawaian.Delete';
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
        $this->load->model('pegawai/riwayat_penghargaan_model');
        $this->load->model('pegawai/jenis_penghargaan_model');
        $this->load->model('pegawai/tingkatpendidikan_model');
        $jenis_penghargaans = $this->jenis_penghargaan_model->find_all();
        Template::set("jenis_penghargaans", $jenis_penghargaans);
        $this->load->model('masters/pengelola_sdm_kawasan_model');
        $pengelolasdms = $this->pengelola_sdm_kawasan_model->find_all();
        Template::set('pengelolasdms', $pengelolasdms);
        $this->load->model('pegawai/KEDUDUKAN_HUKUM_model');
        $KEDUDUKAN_HUKUMs = $this->KEDUDUKAN_HUKUM_model->find_all();
        Template::set('KEDUDUKAN_HUKUMs', $KEDUDUKAN_HUKUMs);
    }

    /**
     * Display a list of petapenghargaan data.
     *
     * @return void
     */
    public function index()
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Penghargaan Pegawai");
        Template::set("collapse", true);
        Template::set_view("rekap/penghargaan");
        Template::render();
    }

    public function ajax_list()
    {

        $this->load->helper('aplikasi');
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');

        $length = $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start = $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $this->db->start_cache();
        $advanced_search_filters = $this->input->post("advanced_search_filters");
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as $filter) {
                $filters[$filter['name']] = $filter["value"];
            }
            if ($filters['nip']) {
                $this->riwayat_penghargaan_model->where('NIP_BARU', $filters['nip']);
            }
            if ($filters['filter_unit_kerja']) {
                $this->riwayat_penghargaan_model->where('UNOR_INDUK_ID', $filters['filter_unit_kerja']);
            }
            if ($filters['ID_JENIS_PENGHARGAAN']) {
                $this->riwayat_penghargaan_model->where('ID_JENIS_PENGHARGAAN', $filters['ID_JENIS_PENGHARGAAN']);
            }
            if ($filters['SK_NOMOR']) {
                $this->riwayat_penghargaan_model->where('SK_NOMOR', $filters['SK_NOMOR']);
            }
            if ($filters['filter_tahun']) {
                $this->riwayat_penghargaan_model->where('extract(year from "SK_TANGGAL") ' . $filters['operator_tahun'] . $filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']) {
                $this->riwayat_penghargaan_model->where('extract(year from "SK_TANGGAL")' . $filters['operator_tahun_selesai'] . $filters['filter_tahun_selesai']);
            }
            if ($filters['pengelolasdm'] != "") {
                $this->pegawai_model->where('coalesce(pengelola_sdm_kawasan_id,1)', $filters['pengelolasdm']);
            }
            if ($filters['active'] == 1) {
                $this->pegawai_model->where('terminated_date is null');
            }
        }
        $this->db->stop_cache();

        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->pegawai_model->order_by($iSortCol, $sSortCol);


        $total = $this->riwayat_penghargaan_model->count_all();
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->riwayat_penghargaan_model->limit($length, $start);
        $this->riwayat_penghargaan_model->order_by('PNS_NIP');
        $records = $this->riwayat_penghargaan_model->find_all();

        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row[] = $nomor_urut . ".";
                $row[] = $record->NAMA . '<br>' . $record->PNS_NIP;
                $row[] = $record->SK_NOMOR . '<br>' . getIndonesiaFormat($record->SK_TANGGAL);
                $row[] = '<b>' . $record->jenis_penghargaan_text . '</b><br>' . $record->INSTITUSI;
                $row[] = $record->NAMA_UNOR_FULL;

                $btn_actions = array();
                if ($record->id_arsip)
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' class='btn btn-sm btn-warning modal-custom-global'><i class='fa fa-eye'></i> </a>";
                if ($this->auth->has_permission($this->permissionEdit)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/Riwayatpenghargaan/edit/" . $record->PNS_ID . "/" . $record->ID . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-pencil'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionDelete)) {
                    $btn_actions[] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-penghargaan'><i class='fa fa-trash-o'></i> </a>";
                }
                $btn_actions[] = "<a href='" . base_url() . "admin/kepegawaian/pegawai/profilen/" . $record->ref . "' class='btn btn-sm btn-info' target='_BLANK'><i class='glyphicon glyphicon-user'></i> </a>";
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
        $advanced_search_filters = $_GET;
        if ($advanced_search_filters) {
            $filters = $advanced_search_filters;

            if ($filters['nip']) {
                $this->riwayat_penghargaan_model->where('NIP_BARU', $filters['nip']);
            }
            if ($filters['filter_unit_kerja']) {
                $this->riwayat_penghargaan_model->where('UNOR_INDUK_ID', $filters['filter_unit_kerja']);
            }
            if ($filters['ID_JENIS_PENGHARGAAN']) {
                $this->riwayat_penghargaan_model->where('ID_JENIS_PENGHARGAAN', $filters['ID_JENIS_PENGHARGAAN']);
            }
            if ($filters['SK_NOMOR']) {
                $this->riwayat_penghargaan_model->where('SK_NOMOR', $filters['SK_NOMOR']);
            }
            if ($filters['filter_tahun']) {
                $this->riwayat_penghargaan_model->where('extract(year from "SK_TANGGAL") ' . $filters['operator_tahun'] . $filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']) {
                $this->riwayat_penghargaan_model->where('extract(year from "SK_TANGGAL")' . $filters['operator_tahun_selesai'] . $filters['filter_tahun_selesai']);
            }
            if ($filters['pengelolasdm'] != "") {
                $this->pegawai_model->where('coalesce(pengelola_sdm_kawasan_id,1)', $filters['pengelolasdm']);
            }
            if ($filters['active'] == 1) {
                $this->pegawai_model->where('terminated_date is null');
            }
            $records = $this->riwayat_penghargaan_model->find_detil();
            return download_excel($records, "Data Penghargaan Pegawai");
        }
    }
}
