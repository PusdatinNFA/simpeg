<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class Kpo extends Admin_Controller
{
    protected $permissionSinkron   = 'Integrasi.Kpo.Sync';
    protected $permissionView   = 'Integrasi.Kpo.View';
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
        Template::set_view("integrasi/kpo");
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
        $tanggal        = $filters['filter_tahun'] . "-" . $filters['filter_bulan'] . "-01";
        $data_return = $this->cache->get('list_kp_' . $tanggal);
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListKP", array('periode' => $tanggal));
            $this->cache->write($data_return, 'list_kp_' . $tanggal, 43200);
        }
        $dataKp = json_decode($data_return);
        $dataKp = json_decode($dataKp);
        $records = $dataKp->data;
        if ($nip) {
            $records = array_filter($records, function ($var) use ($nip) {
                return $var->nipBaru == $nip;
            });
        }
        if ($nama) {
            $records = array_filter($records, function ($var) use ($nama) {
                return strpos(strtolower($var->nama), $nama) !== false;
            });
        }
        if ($filters['statusUsulan']) {
            $records = array_filter($records, function ($var) use ($filters) {
                return $var->statusUsulan == $filters['statusUsulan'];
            });
        }
        $total = $dataKp->count;
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
                $dataLokal = $this->riwayat_kepangkatan_model->find_by(array('PNS_NIP' => $record->nipBaru, 'ID_GOLONGAN' => $record->golonganBaruId, 'JENIS_RIWAYAT' => 'KP'));
                $row = array();
                // $row[]  = "<input name='id_data[]' class='checkbox-sk' type='checkbox' value='" . $record->id . "'>";
                $row[]  = $nomor_urut;
                $row[]  = $record->tmtKp;
                $row[]  = $record->nama;
                $row[]  = $record->nipBaru;
                $row[]  = $record->golonganBaruId;
                // $row[]  = '';//$data->masa_kerja_golongan_baru_tahun . " Tahun " . $data->masa_kerja_golongan_baru_bulan . " bulan";
                // $row[]  = '';// rupiah($data->gaji_pokok_baru);
                $btn_actions = array();
                if ($record->path_ttd_sk) {
                    $btn_actions[] = "<a href='#' kode='{$record->nipBaru}' next={$record->golonganBaruId} class='btn btn-sm btn-warning btn-sync' data-toggle='tooltip' title='Sync data' tooltip='Sync data' ><i class='fa fa-refresh'></i> </a>";
                }
                if ($dataLokal) {
                    if ($dataLokal->id_arsip != "") {
                        $btn_actions[] = "<a href='" . base_url() . "admin/arsip/arsip_digital/viewdoc2/" . $dataLokal->id_arsip . "' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                    }
                    if ($this->auth->has_permission($this->permissionEdit)) {
                        $btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkepangkatan/edit/" . $dataLokal->PNS_ID . "/" . $dataLokal->ID . "' data='{$dataLokal->PNS_ID}' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                    }
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

    public function get_last_kpo()
    {
        $data_return = Modules::run("pegawai/Bkn/getdata_sk_kpo");
        if (sizeof($data_return) > 0 && is_array($data_return)) {
            $response['success'] = true;
            $response['msg'] = "Data ditemukan";
            $output = $this->load->view('integrasi/last_kpo', array('data_kpo' => $data_return), true);
            $response['konten'] = $output;
        } else {
            $response['success'] = false;
            $response['msg'] = "Data tidak ada";
            $response['konten'] = "";
        }
        echo json_encode($response);
        die();
    }

    public function sync_last_kpo()
    {
        $this->auth->restrict($this->permissionSinkron);
        $data_return = Modules::run("pegawai/Bkn/getdata_sk_kpo");
        $result = array(
            'success' => true,
            'message' => 'Sinkronisasi berhasil'
        );
        $error = array();
        foreach ($data_return as $row) {
            if ($row) {
                $this->sync($row, true);
            } else {
                $error[] = "data with id {$row->id} not found";
            }
        }
        if (sizeof($error) > 0) {
            $result['success'] = false;
            $result['message'] = implode(",", $error);
        } else {
            $this->cache->delete("data_kpo_sk");
        }
        echo json_encode($result);
        die();
    }

    public function sync_hist_kpo()
    {
        $this->auth->restrict($this->permissionSinkron);
        $from = $this->input->post("from");
        $to = $this->input->post("to");
        $ids = $this->input->post("ids");
        // $data_return = Modules::run("pegawai/Bkn/getdata_sk_kpo_hist",$from, $to);
        $ids = explode(",", $ids);
        $result = array(
            'success' => true,
            'message' => 'Sinkronisasi berhasil'
        );
        $error = array();
        foreach ($ids as $id) {
            $row = $this->cache->get("data_kpo_sk_{$id}");
            if ($row) {
                $this->sync($row, true);
            } else {
                $error[] = "data with id {$id} not found";
            }
        }
        if (sizeof($error) > 0) {
            $result['success'] = false;
            $result['message'] = implode(",", $error);
        }
        echo json_encode($result);
        die();
    }

    private function set_pangkat_aktif($nip)
    {
        $golongan_aktifs = $this->riwayat_kepangkatan_model->limit(1)->find_aktif($nip);
        if ($golongan_aktifs[0]->ID_GOLONGAN != "") {
			$dataupdate = array();
			$dataupdate['GOL_ID']           = $golongan_aktifs[0]->ID_GOLONGAN;
			$dataupdate['TMT_GOLONGAN']     = $golongan_aktifs[0]->TMT_GOLONGAN;
			$dataupdate['MK_TAHUN']         = $golongan_aktifs[0]->MK_GOLONGAN_TAHUN;
			$dataupdate['MK_BULAN']         = $golongan_aktifs[0]->MK_GOLONGAN_BULAN;
			if (strtotime($golongan_aktifs[0]->TMT_GOLONGAN) > strtotime('now')) {
				$data_insert = array(
					'nip'  =>  $nip,
					'data'  => json_encode($dataupdate),
					'tanggal' => $golongan_aktifs[0]->TMT_GOLONGAN
				);
				$this->db->insert('hris.schedule_update_pegawai', $data_insert);
				log_activity($this->auth->user_id(), 'Insert Schedule Update  NIP : ' . $nip . ' data : ' . json_encode($data_insert) . ' ' . $this->input->ip_address(), 'Home');
			} else {
				$this->pegawai_model->skip_validation(true);
				$this->pegawai_model->update_where("NIP_BARU", $nip, $dataupdate);
			}
		}
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

        $this->set_pangkat_aktif($row->nipBaru);
    }

    public function getGapok($golongan_id, $mk)
    {
        $this->load->model('pegawai/riwayat_kgb_model');
        $result = $this->riwayat_kgb_model->getWage2($golongan_id, $mk);
        return $result ? $result->BASIC : null;
    }
}
