<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class Pengadaan extends Admin_Controller
{
    protected $permissionSinkron   = 'Integrasi.Pengadaan.Sync';
    protected $permissionView   = 'Integrasi.Pengadaan.View';
    protected $permissionEdit   = 'RiwayatKepangkatan.Kepegawaian.Edit';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict($this->permissionView);
        $this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->load->model('pegawai/pegawai_model');
    }

    /**
     * Display a list of jabatan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Sinkronisasi Hasil KPO");
        Template::set_view("integrasi/pengadaan");
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
        $nip = "";
        $nama = "";
        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $advanced_search_filters  = $this->input->post("advanced_search_filters");
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as  $filter) {
                $filters[$filter['name']] = $filter["value"];
            }
            if ($filters['nama'] != "") {
                $nama = strtolower($filters['nama']);
            }
            if ($filters['nip'] != "") {
                $nip = $filters['nip'];
            }
        }
        $tahun        = $filters['filter_tahun'];
        $data_return = $this->cache->get('list_pengadaan_' . $tahun);
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListPengadaan", $tahun);
            $this->cache->write($data_return, 'list_pengadaan_' . $tahun, 43200);
        }
        $data = json_decode($data_return);
        $data = json_decode($data);
        $records = $data->data ? $data->data : array();
        if ($nip) {
            $records = array_filter($records, function ($var) use ($nip) {
                return $var->nip == $nip;
            });
        }
        if ($nama) {
            $records = array_filter($records, function ($var) use ($nama) {
                return strpos(strtolower($var->nama), $nama) !== false;
            });
        }
        $total = $data->count ? $data->count : 0;
        if ($nip || $nama || $filters['statusUsulan']) {
            $total = sizeof($records);
        }
        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();


        $datas = array_chunk($records, $length);
        $index = intdiv($start, $length);
        $records = $datas[$index];

        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)) :
            foreach ($records as $record) {
                $dataLokal = $this->pegawai_model->find_by("NIP_BARU", $record->nip);
                $row = array();
                $row[]  = "<input name='id_data[]' class='checkbox-sk' type='checkbox' value='" . $record->nip . "'>";
                $row[]  = $nomor_urut;
                $row[]  = $dataLokal->NAMA . '<br>' . $record->nip;
                $row[]  = $record->no_peserta;
                $row[]  = $record->periode;
                $row[]  = $record->no_sk . '<br> Tanggal : ' . dateFromTime($record->tgl_sk, 'd-m-Y');
                $btn_actions = array();
                if ($record->path_ttd_sk) {
                    $btn_actions[] = "<a href='#' kode='{$record->nip}' class='btn btn-sm btn-warning btn-sync' data-toggle='tooltip' title='Sync data' tooltip='Sync data' ><i class='fa fa-refresh'></i> </a>";
                }
                if ($dataLokal) {
                    $btn_actions[] = "<a href='" . base_url() . "admin/kepegawaian/pegawai/profilen/" . $dataLokal->ref . "' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>";
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
        $from = null;
        $to = null;
        $this->nip = null;
        $this->tmt = null;
        if ($advanced_search_filters) {
            $filters = $advanced_search_filters;
            if ($filters['from'] != "") {
                $from = $filters['from'];
            }
            if ($filters['to'] != "") {
                $to = $filters['to'];
            }
            if ($filters['nip'] != "") {
                $this->nip = $filters['nip'];
            }
            if ($filters['tmt'] != "") {
                $this->tmt = $filters['tmt'];
            }
        }
        $records = Modules::run("pegawai/Bkn/getdata_sk_kpo_hist", $from, $to);
        if ($this->nip) {
            $records = array_filter($records, function ($var) {
                return ($var->nipBaru == $this->nip);
            });
        }
        if ($this->tmt) {
            $records = array_filter($records, function ($var) {
                return ($var->tmtGolongan == $this->tmt);
            });
        }
        if (sizeof($records) > 0)
            return download_excel($records, "Data KPO");
        else
            die();
    }
    private function sync($row = null, $last = false)
    {
        $golongan = $this->riwayat_kepangkatan_model->find_by(array('PNS_NIP' => $row->nipBaru, 'ID_GOLONGAN' => $row->golongan->id, 'JENIS_RIWAYAT' => 'KP'));

        $data = array();
        if (sizeof($golongan) <= 0) {
            $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $row->nipBaru);
            $data["PNS_NIP"] = $row->nipBaru;
            $data["PNS_ID"] = $pegawai_lokal->PNS_ID;
            $data["PNS_NAMA"] = $pegawai_lokal->NAMA;
        }

        if ($last) {
            $dataupdate = array();
            $dataupdate["PANGKAT_TERAKHIR"] = 0;
            $this->riwayat_kepangkatan_model->skip_validation(true);
            $this->riwayat_kepangkatan_model->update_where("PNS_NIP", $row->nipBaru, $dataupdate);
            $data["PANGKAT_TERAKHIR"]    = 1;
        }
        $data["ID_GOLONGAN"] = $row->golongan->id ? $row->golongan->id : null;
        $data["PANGKAT"] = $row->golongan->namaPangkat;
        $data["NOMOR_BKN"] = $row->notaPertek;
        $data["MK_GOLONGAN_TAHUN"] = $row->masaKerjaGolonganBulan ? intdiv($row->masaKerjaGolonganBulan, 12) : 0;
        $data["MK_GOLONGAN_BULAN"] = $row->masaKerjaGolonganBulan ? (int)$row->masaKerjaGolonganBulan % 12 : 0;
        $data["SK_NOMOR"] = $golongan->SK_NOMOR ? $golongan->SK_NOMOR : $row->noSk;
        $data["SK_TANGGAL"] = $golongan->SK_TANGGAL ? $golongan->SK_TANGGAL : date("Y-m-d", strtotime($row->tglSk));
        $data["KODE_JENIS_KP"] = $row->jenisKp->id ? $row->jenisKp->id : null;
        $data["GOLONGAN"] = $row->golongan->nama ? $row->golongan->nama : null;

        $data["TMT_GOLONGAN"]     = date("Y-m-d", strtotime($row->tmtGolongan));
        $data['JENIS_RIWAYAT'] = "KP";

        $data['JUMLAH_ANGKA_KREDIT_UTAMA'] = $row->golongan->fungKredututama;
        $data['JUMLAH_ANGKA_KREDIT_TAMBAHAN'] = $row->golongan->fungKreditTambahan;
        $data['BASIC'] = $this->getGapok($row->golongan->id, intdiv($row->masaKerjaGolonganBulan, 12));

        if (empty($data["MK_GOLONGAN_BULAN"])) {
            unset($data["MK_GOLONGAN_BULAN"]);
        }
        if (empty($data["MK_GOLONGAN_TAHUN"])) {
            unset($data["MK_GOLONGAN_TAHUN"]);
        }
        if (empty($data["TMT_GOLONGAN"])) {
            unset($data["TMT_GOLONGAN"]);
        }
        if (empty($data["SK_TANGGAL"])) {
            unset($data["SK_TANGGAL"]);
        }
        if (sizeof($golongan) <= 0) {
            $this->riwayat_kepangkatan_model->insert($data);
        } else {
            // die(json_encode($golongan));
            $this->riwayat_kepangkatan_model->update($golongan->ID, $data);
        }
        log_activity($this->auth->user_id(), "sinkron data rwt_golongan dari BKN NIP {$row->nipBaru} TMT {$row->tmtGolongan}  ", 'pegawai');
    }
}
