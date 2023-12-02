<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class Pemberhentian extends Admin_Controller
{
    protected $permissionView = 'Integrasi.Pemberhentian.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->restrict($this->permissionView);
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('arsip_digital/arsip_digital_model');
    }

    /**
     * Display a list of jabatan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Sinkronisasi Data Pemberhentian");
        Template::set_view("integrasi/pemberhentian");
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
        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $advanced_search_filters = $this->input->post("advanced_search_filters");
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as $filter) {
                $filters[$filter['name']] = $filter["value"];
            }
            $tgl_awal = $filters['tgl_awal'] ? $filters['tgl_awal'] : date('01-m-Y');
            $tgl_akhir = $filters['tgl_akhir'] ? $filters['tgl_akhir'] : date('d-m-Y');
        }
        $params = array(
            "tglAwal" => $tgl_awal,
            "tglAkhir" => $tgl_akhir,
        );

        $data_return = $this->cache->get("list_pemberhentian_$tgl_awal" ."_$tgl_akhir");
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListPemberhentian", $start, $length, $params);
            $result = json_decode($data_return);
            $result = json_decode($result);
            if ($result->code && $result->code == 1)
            $this->cache->write($data_return, "list_pemberhentian_$tgl_awal" ."_$tgl_akhir", 43200);
        }
        $result = json_decode($data_return);
        $result = json_decode($result);
        $records = $result->data;
        $total = $result->count;

        $datas = array_chunk($records, $length);
        $index = intdiv($start, $length);
        $records = $datas[$index];

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data'] = array();

        $this->load->helper('aplikasi');
        $nomor_urut = $start + 1;
        if (isset($records) && is_array($records) && count($records)):
            foreach ($records as $data) {
                $row = array();
                $pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU", $data->nipBaru);
                $arsip = $this->arsip_digital_model->find_by(array('NIP' => $data->nipBaru, 'ID_JENIS_ARSIP' => 58));
                $row[] = $nomor_urut;
                $row[] = $data->nama . '<br>' . $data->nipBaru;
                $row[] = '<label class="label label-success">' . $data->statusUsulanNama . '</label><br>' . $data->detailLayananNama;
                $row[] = getIndonesiaFormat(date("Y-m-d", strtotime($data->tmtPensiun)));
                $row[] = $data->skNomor . '<br>' . getIndonesiaFormat(date("Y-m-d", strtotime($data->skTgl)));;
                $btn_actions = array();
                if ($arsip) {
                    $btn_actions[] = "<a href='#' data-toggle='tooltip' title='SK Pensiun sudah ada' class='btn btn-sm btn-success'><i class='fa fa-check-circle'></i> </a>";
                }
                if ($pegawai_lokal) {
                    $btn_actions[] = "<a href='" . base_url() . "admin/kepegawaian/pegawai/profilen/" . $pegawai_lokal->ref . "' class='btn btn-sm btn-default' target='_BLANK'><i class='glyphicon glyphicon-user'></i> </a>";
                }
                if ($data->pathPertek) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/bkn/viewdoc?path=" . $data->pathPertek . "' data-toggle='tooltip' class='btn btn-sm btn-info' tooltip='Lihat dokumen' title='Lihat pertek' target='_blank'><span class='fa fa-eye'></span> Pertek</a>";
                }
                if ($data->pathSk) {
                    $btn_actions[] = "<a href='" . base_url() . "pegawai/bkn/viewdoc?path=" . $data->pathSk . "' data-toggle='tooltip' class='btn btn-sm btn-success' tooltip='Lihat dokumen' title='Lihat pertek' target='_blank'><span class='fa fa-eye'></span> SK</a>";
                }
                if ($data->statusUsulan == 32) {
                    $btn_actions[] = "<a href='#' kode='{$data->nipBaru}' class='btn btn-sm btn-warning btn-sync' data-toggle='tooltip' title='Sync data' tooltip='Sync data' ><i class='fa fa-refresh'></i> </a>";
                }
                $row[] = "<div class='btn-group'>" . implode(" ", $btn_actions) . "</div>";
                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }

    public function detail($tahun, $nip)
    {
        $data_return = $this->cache->get('nilai_ipasn_' . $tahun . '_' . $nip);
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListIpasn", 500, $nip, $tahun);
            $this->cache->write($data_return, 'nilai_ipasn_' . $tahun . '_' . $nip, 43200);
        }
        $result = json_decode($data_return);
        $records = $result->data->data;
        if ($nip) {
            $records = array_filter($records, function ($var) use ($nip) {
                return $var->nip_baru == $nip;
            });
        }
        Template::set_view("integrasi/detail_ipasn");
        Template::set("data", $records[0]);
        Template::render();
    }

    public function sinkron()
    {
        $nip = $this->input->post('nip');
        if ($nip) {
            try {
                $data_return = $this->cache->get('data_pemberhentian_' . $nip);
                if ($data_return == null) {
                    $params = array(
                        "by_nip" => $nip,
                        "by_nama" => null,
                        "by_tgl_usulan" => "",
                        "by_sub_layanan_id" => null,
                        "by_detail_layanan_id" => null,
                        "by_sk_signer_nip" => null,
                        "by_status_usulan" => null
                    );

                    $data_return = Modules::run("pegawai/Bkn/getListPemberhentianSiasn", 0, 1, $params);
                    $this->cache->write($data_return, 'data_pemberhentian_' . $nip, 43200);
                }

                $result = json_decode($data_return);
                $result = json_decode($result);

                $records = $result->data;
                $total = $result->meta->total;
                if ($total == 1) {
                    $data = $records[0];
                    $path_pertek = $data->path_pertek;
                    $no_sk = $data->sk_nomor;
                    $nama = $data->nama;
                    $data_usulan = json_decode($data->usulan_data);
                    $data_usulan = $data_usulan->data;
                    $path_sk = $data->path_sk;
                    $tmt_pensiun = date("Y-m-d", strtotime($data_usulan->tmt_pensiun));
                    $tmt_pemberhentian = date("Y-m-d", strtotime($data_usulan->tmt_pemberhentian));
                    Modules::run('pegawai/Bkn/saveBerkas', $nip, $no_sk, 'Pertek Pensiun a.n ' . $nama, 170, $path_pertek);
                    Modules::run('pegawai/Bkn/saveBerkas', $nip, $no_sk, "SK Pensiun a.n $nama TMT $data_usulan->tmt_pensiun", 58, $path_sk);

                    if ($data_usulan->is_kpp) {
                        $dataupdate = array();
                        $tmt_kpp = date("Y-m-d", strtotime($data_usulan->golongan_kpp_tmt));
                        $dataupdate['GOL_ID'] = $data_usulan->golongan_kpp_id;
                        $dataupdate['TMT_GOLONGAN'] = $tmt_kpp;
                        $dataupdate['MK_TAHUN'] = $data_usulan->mk_golongan_tahun;
                        $dataupdate['MK_BULAN'] = $data_usulan->mk_golongan_bulan;
                        if (strtotime($tmt_kpp) > strtotime('now')) {
                            $data_insert = array(
                                'nip' => $nip,
                                'data' => json_encode($dataupdate),
                                'tanggal' => $tmt_kpp
                            );
                            $this->db->insert('hris.schedule_update_pegawai', $data_insert);
                            log_activity($this->auth->user_id(), 'Insert Schedule Update  NIP : ' . $nip . ' data : ' . json_encode($data_insert) . ' ' . $this->input->ip_address(), 'Home');
                        } else {
                            $this->pegawai_model->skip_validation(true);
                            $this->pegawai_model->update_where("NIP_BARU", $nip, $dataupdate);
                        }
                    }


                    $data_update = array(
                        'KEDUDUKAN_HUKUM_ID' => 99,
                        'terminated_date' => $tmt_pemberhentian,
                        'TMT_PENSIUN' => $tmt_pensiun
                    );
                    if (strtotime($tmt_pensiun) > strtotime('now')) {
                        $data_insert = array(
                            'nip' => $nip,
                            'data' => json_encode($data_update),
                            'tanggal' => $tmt_pensiun
                        );
                        $this->db->insert('hris.schedule_update_pegawai', $data_insert);
                        log_activity(1, 'Insert Schedule Update  NIP : ' . $nip . ' data : ' . json_encode($data_insert) . ' ' . $this->input->ip_address(), 'Home');
                    } else {
                        $this->pegawai_model->update_where('NIP_BARU', $nip, $data_update);
                        log_activity(1, 'Setting Pensiun Pegawai  NIP : ' . $nip . ' tmt : ' . $tmt_pensiun . ' ' . $this->input->ip_address(), 'Home');
                    }

                    $arsip = $this->arsip_digital_model->find_by(array('NIP' => $nip, 'ID_JENIS_ARSIP' => 58));
                    // insert ke riwayat perubahan faktor gaji
                    $insert_faktor_gaji = [
                        'nip' => $nip,
                        'tmt' => $tmt_pensiun,
                        'table_name' => 'hris.pegawai',
                        'komponen_kode' => '11',
                        'id_arsip' => $arsip ? $arsip->ID : null,
                        'status' => $arsip ? 1 : 0
                    ];
                    $this->db->insert('hris.update_penggajian_pegawai', $insert_faktor_gaji);


                    $response['success'] = true;
                    $response['msg'] = "Sinkronisasi selesai";
                    echo json_encode($response);
                } else {
                    throw new Exception("Data tidak ditemukan", 1);
                }
            } catch (Exception $e) {
                $response['success'] = false;
                $response['msg'] = $e->getMessage();
                echo json_encode($response);
            }
        }
    }

    public function download()
    {
        $this->load->helper('aplikasi');
        $advanced_search_filters  = $_GET;
        if ($advanced_search_filters) {
            $filters = $advanced_search_filters;
            
            $tgl_awal = $filters['tgl_awal'] ? $filters['tgl_awal'] : date('01-m-Y');
            $tgl_akhir = $filters['tgl_akhir'] ? $filters['tgl_akhir'] : date('d-m-Y');
        }
        $params = array(
            "tglAwal" => $tgl_awal,
            "tglAkhir" => $tgl_akhir,
        );

        $data_return = $this->cache->get("list_pemberhentian_$tgl_awal" ."_$tgl_akhir");
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListPemberhentian", 0, 100, $params);
            $result = json_decode($data_return);
            $result = json_decode($result);
            if ($result->code && $result->code == 1)
            $this->cache->write($data_return, "list_pemberhentian_$tgl_awal" ."_$tgl_akhir", 43200);
        }
        $result = json_decode($data_return);
        $result = json_decode($result);
        $records = $result->data;
        if (sizeof($records) > 0)
            return download_excel($records, "Data Pensiun");
        else
            die();
    }
}
