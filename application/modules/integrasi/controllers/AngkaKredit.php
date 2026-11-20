<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class AngkaKredit extends Admin_Controller
{
    protected $permissionUploadBkn   = 'Riwayatangkakredit.Kepegawaian.UploadBkn';
    protected $permissionEdit   = 'Riwayatangkakredit.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatangkakredit.Kepegawaian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->auth->restrict($this->permissionUploadBkn);
        $this->load->model('pegawai/riwayat_angka_kredit_model');
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
        Template::set('toolbar_title', "Integrasi Angka Kredit");
        Template::set_view("integrasi/angka_kredit");
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
                $this->db->where('satker_id', $filters['filter_unit_kerja']);
            }
            if ($filters['filter_tahun']){
                $this->db->where('tahunMulaiPenilaian'.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('tahunSelesaiPenilaian'.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }
        $this->db->stop_cache();

        $total = $this->riwayat_angka_kredit_model->countUnsyncBkn();
        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->riwayat_angka_kredit_model->limit($length, $start);
        $records = $this->riwayat_angka_kredit_model->getUnsyncBkn();
        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $row = array();
                if (($record->isAngkaKreditPertama || $record->rwJabatanId) && $record->bulanMulaiPenilaian && $record->tahunMulaiPenilaian
                    && $record->bulanSelesaiPenilaian  && $record->tahunSelesaiPenilaian && $record->nomorSk && $record->tanggalSk
                )
                    $row[]  = "<input name='id_data[]' class='checkbox-ak' type='checkbox' value='" . $record->id_tabel . "'>";
                else $row[] = "";
                $row[]  = $nomor_urut;
                $row[]  = $record->nama . "<br>" . $record->nip;
                $row[]  = $record->nomorSk . "<br><i>TANGGAL : " . $record->tanggalSk . "</i>";
                $row[]  = $record->namaJabatan;
                $row[]  = getBulanIndonesia($record->bulanMulaiPenilaian) . " " . $record->tahunMulaiPenilaian . "<br>s.d.<br>" . getBulanIndonesia($record->bulanSelesaiPenilaian) . " " . $record->tahunSelesaiPenilaian;
                $row[]  = $record->kreditBaruTotal;
                $row[]  = $record->satker;
                $btn_actions = array();
                if ($record->id_arsip != "") {
                    $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $record->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if ($this->auth->has_permission($this->permissionEdit)) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_angka_kredit/edit/" . $record->id_tabel . "' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-pencil'></i> </a>";
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
                $this->db->where('tahunMulaiPenilaian'.$filters['operator_tahun'].$filters['filter_tahun']);
            }
            if ($filters['filter_tahun_selesai']){
                $this->db->where('tahunSelesaiPenilaian'.$filters['operator_tahun_selesai'].$filters['filter_tahun_selesai']);
            }
        }

        $records = $this->riwayat_angka_kredit_model->getUnsyncBkn();
        return download_excel($records, "Data Angka Kredit");
    }
}
