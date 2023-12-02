<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * AngkaKredit controller
 */
class Ipasn extends Admin_Controller
{
    protected $permissionView   = 'Integrasi.Ipasn.View';

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
    }

    /**
     * Display a list of jabatan data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        $this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', "Sinkronisasi IPASN");
        Template::set_view("integrasi/ipasn");
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
        $tahun = 2022;
        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $advanced_search_filters  = $this->input->post("advanced_search_filters");
        if ($advanced_search_filters) {
            $filters = array();
            foreach ($advanced_search_filters as  $filter) {
                $filters[$filter['name']] = $filter["value"];
            }
            if ($filters['nip'] != "") {
                $nip = $filters['nip'];
            }
            if ($filters['tahun'] != "") {
                $tahun = $filters['tahun'];
            }
            if ($filters['nama'] != "") {
                $nama = $filters['nama'];
            }
        }
        $params = array(
            "tahun" => $tahun,
            "nip" => $nip,
            "nama" => $nama
        );
        $data_return = Modules::run("pegawai/Bkn/getListIpasn", $length, $start, $params);
        $data = json_decode($data_return);
        $dataKp = json_decode($data);
        $records = $dataKp->data->data;
        
        $total = $dataKp->data->meta->total;
        if ($nip) {
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
                $data = $record;
                $row = array();
                $row[]  = $nomor_urut;
                $row[]  = $data->nama;
                $row[]  = $data->nip_baru;
                $row[]  = $data->jenis_jabatan;
                $row[]  = $data->kualifikasi;
                $row[]  = $data->kompetensi;
                $row[]  = $data->kinerja;
                $row[]  = $data->disiplin;
                $row[]  = $data->subtotal;
                $btn_actions = array();
                $btn_actions[] = "<a href='" . base_url() . "integrasi/ipasn/detail/" . $data->tahun . '/' . $data->nip_baru . "' data-toggle='tooltip' title='Detail data' tooltip='Detail data' class='btn btn-sm btn-success modal-custom-global'><i class='fa fa-search'></i> </a>";
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
        $params = array(
            "tahun" => $tahun,
            "nip" => $nip
        );
        $data_return = Modules::run("pegawai/Bkn/getListIpasn", 10, 0, $params);
        $data = json_decode($data_return);
        $dataKp = json_decode($data);
        $records = $dataKp->data->data;
        if ($nip) {
            $records = array_filter($records, function ($var) use ($nip) {
                return $var->nip_baru == $nip;
            });
        }
        Template::set_view("integrasi/detail_ipasn");
        Template::set("data", $records[0]);
        Template::render();
    }

    public function download()
    {
        $this->load->helper('aplikasi');
        $advanced_search_filters  = $_GET;
        $from = null;
        $to = null;
        if ($advanced_search_filters) {
            $filters = $advanced_search_filters;
            if ($filters['nip'] != "") {
                $nip = $filters['nip'];
            }
            if ($filters['tahun'] != "") {
                $tahun = $filters['tahun'];
            }
        }
        $data_return = $this->cache->get('nilai_ipasn_' . $tahun . '_' . $nip);
        if ($data_return == null) {
            $data_return = Modules::run("pegawai/Bkn/getListIpasn", 500, $nip, $tahun);
            $this->cache->write($data_return, 'nilai_ipasn_' . $tahun . '_' . $nip, 43200);
        }
        $dataKp = json_decode($data_return);
        $records = $dataKp->data->data;
        if (sizeof($records) > 0)
            return download_excel($records, "Data IP ASN");
        else
            die();
    }
}
