<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DRH <?= $pegawai->NAMA ?></title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/default/css/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/dist/css/skins/_all-skins.min.css">
    <script src="<?php echo base_url(); ?>themes/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>

    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
    <style>
        tr.noborder {
            border: none !important;
        }
    </style>
</head>
<?php
function isDateFormat($date)
{
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
        return true;
    } else {
        return false;
    }
}

function getIndonesiaFormat($date)
{
    if (isDateFormat($date)) {
        $dt = DateTime::createFromFormat('Y-m-j', $date);
        $bulans = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
        return $dt->format('j') . " " . $bulans[$dt->format('m') - 1] . " " . $dt->format('Y');
    }
    return "-";
}

function rupiah($angka)
{
    if ($angka) {
        $hasil_rupiah = "Rp" . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
    return "-";
}
?>

<body>
    <section class="content">
        <div class="row">
            <div class="col-md-offset-1 col-lg-offset-2 col-md-10 col-lg-8 mx-auto">
                <div class="box box-danger" style="stroke: 1;">
                    <h3 style="text-align: center;">Daftar Riwayat Hidup</h3>
                    <div class="box-body">

                        <div class="col-md-3 card pull-left" style="margin-top:3.25rem">
                            <img src="<?php echo END_POINT_PHOTO . $pegawai->username_intra ?>" style="margin:5px" width="150" height="200">
                        </div>

                        <fieldset>
                            <div class="box-header no-border">
                                <h3 class="box-title" class="">Data Pribadi</h3>
                            </div>
                            <table class="table table-bordered table-responsive table-striped">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;"> 1 </td>
                                        <td class="text-capitalize text-nowrap" width="25%">Nama lengkap pegawai</td>
                                        <td width="10px">: </td>
                                        <?php $glr_depan = $pegawai->GELAR_DEPAN ? $pegawai->GELAR_DEPAN . " " : "" ?>
                                        <?php $glr_blk = $pegawai->GELAR_BELAKANG ? ", " . $pegawai->GELAR_BELAKANG : "" ?>
                                        <td><?php echo $glr_depan . $pegawai->NAMA . $glr_blk ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 2 </td>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td><?= $pegawai->NIP_BARU ?>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 3 </td>
                                        <td class="text-capitalize">Tempat / tanggal lahir</td>
                                        <td>: </td>
                                        <td>
                                            <?= $pegawai->TEMPAT_LAHIR_NAMA ?>/<?= getIndonesiaFormat($pegawai->TGL_LAHIR) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 4 </td>
                                        <td class="text-capitalize">Jenis kelamin</td>
                                        <td>:</td>
                                        <td><?= $pegawai->JENIS_KELAMIN == 'M' ? 'Pria' : 'Wanita' ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 5 </td>
                                        <td class="text-capitalize">Status Perkawinan</td>
                                        <td width : 2px>: </td>
                                        <td><?= $pegawai->KAWIN_TEXT ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">6 </td>
                                        <td class="text-capitalize">Agama</td>
                                        <td width : 2px>: </td>
                                        <td><?= $pegawai->AGAMA_TEXT ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 7 </td>
                                        <td class="text-capitalize">pendidikan terakhir</td>
                                        <td width : 2px>: </td>
                                        <td><?= $pegawai->NAMA_PENDIDIKAN ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">8 </td>
                                        <td class="text-capitalize">Status kepegawaian</td>
                                        <td>: </td>
                                        <td><?php echo $pegawai->STATUS_CPNS_PNS == 'C' ? 'CPNS' : 'PNS' ?> <br>
                                            Tmt: <?php echo $pegawai->STATUS_CPNS_PNS == 'C' ? getIndonesiaFormat($pegawai->TMT_CPNS) : getIndonesiaFormat($pegawai->TMT_PNS) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">9 </td>
                                        <td>Kedudukan Hukum</td>
                                        <td>: </td>
                                        <td><?php echo $pegawai->KEDUDUKAN_HUKUM ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">10 </td>
                                        <td>Masa Kerja</td>
                                        <td>: </td>
                                        <td><?php echo $masa_kerja_kp->mk_tahun_baru ?> Tahun <?php echo $masa_kerja_kp->mk_bulan_baru ?> Bulan </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 11 </td>
                                        <td class="text-capitalize">Pangkat / golongan</td>
                                        <td>: </td>
                                        <td><?= $pegawai->PANGKAT_TEXT ?> <?= $pegawai->GOL_TEXT ?> <br>
                                            Tmt: <?= getIndonesiaFormat($pegawai->TMT_GOLONGAN) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 12 </td>
                                        <td class="text-capitalize">jabatan saat ini</td>
                                        <td>: </td>
                                        <td>
                                            <?php
                                            $jabatan = $pegawai->JABATAN_NAMA;
                                            if ($pegawai->ID_JENIS_JABATAN == 1)
                                                $jabatan .= " " . $pegawai->SATUAN_KERJA_NAMA; ?>
                                            <?= $jabatan ?><br>
                                            Tmt: <?= getIndonesiaFormat($pegawai->TMT_JABATAN) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 13 </td>
                                        <td class="text-capitalize">unit kerja</td>
                                        <td>: </td>
                                        <td><?= $pegawai->SATUAN_KERJA_INDUK_NAMA ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 14 </td>
                                        <td class="text-capitalize">alamat</td>
                                        <td width : 2px>: </td>
                                        <td><?= $pegawai->ALAMAT ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;"> 15 </td>
                                        <td class="text-capitalize">email</td>
                                        <td>: </td>
                                        <td><?= $pegawai->EMAIL ?></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-body">
                        <h4 style="text-align: center;">Riwayat Pendidikan</h4>
                        <div class="box-header no-border">
                            <h3 class="box-title">Pendidikan Formal</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Level</th>
                                        <th>Nama Sekolah/Universitas</th>
                                        <th>Tipe</th>
                                        <th>Ijazah</th>
                                        <th>Tanggal Lulus</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_pendidikan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_pendidikan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_pendidikan[$i]->tk_pendidikan_text ?></td>
                                                <td><?= $rwt_pendidikan[$i]->NAMA_SEKOLAH ?></td>
                                                <td class="text-center">-</td>
                                                <td class="text-center"><?= $rwt_pendidikan[$i]->NOMOR_IJASAH ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_pendidikan[$i]->TANGGAL_LULUS) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Diklat Fungsional</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jenis Diklat</th>
                                        <th>Nama Diklat</th>
                                        <th>Tanggal</th>
                                        <th>Tahun</th>
                                        <th>Institusi <BR> Penyelenggara</th>
                                        <th>Nomor<BR> Sertifikat</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_diklat_fungsional) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_diklat_fungsional); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_diklat_fungsional[$i]->JENIS_DIKLAT ?></td>
                                                <td><?= $rwt_diklat_fungsional[$i]->NAMA_KURSUS ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_diklat_fungsional[$i]->TANGGAL_KURSUS) ?></td>
                                                <td class="text-center"><?= $rwt_diklat_fungsional[$i]->TAHUN ?></td>
                                                <td><?= $rwt_diklat_fungsional[$i]->INSTITUSI_PENYELENGGARA ?></td>
                                                <td class="text-center"><?= $rwt_diklat_fungsional[$i]->NOMOR_SERTIPIKAT ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="7">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Diklat Struktural</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jenis Diklat</th>
                                        <th>Nama Diklat</th>
                                        <th>Tanggal</th>
                                        <th>Tahun</th>
                                        <th>Institusi <BR> Penyelenggara</th>
                                        <th>Nomor<BR> Sertifikat</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_diklat_struktural) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_diklat_struktural); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_diklat_struktural[$i]->JENIS_DIKLAT ?></td>
                                                <td class="text-center"><?= $rwt_diklat_struktural[$i]->NAMA_KURSUS ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="7">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Tugas Belajar</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>NOMOR/<br>TANGGAL SK</th>
                                        <th>TINGKAT<br>PENDIDIKAN</th>
                                        <th>UNIVERSITAS</th>
                                        <th>FAKULTAS/<br> PROGRAM STUDI</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_tubel) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_tubel); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_tubel[$i]->NOMOR_SK; ?> <br> Tanggal <?= getIndonesiaFormat($rwt_tubel[$i]->TANGGAL_SK); ?></td>
                                                <td class="text-center"><?= $rwt_tubel[$i]->TINGKAT_PENDIDIKAN ?></td>
                                                <td><?= $rwt_tubel[$i]->UNIVERSITAS ?></td>
                                                <td><?= $rwt_tubel[$i]->FAKULTAS ?><br><?= $rwt_tubel[$i]->PROGRAM_STUDI ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Kursus/Pelatihan</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Tipe Kursus</th>
                                        <th>Jenis Kursus</th>
                                        <th>Nama</th>
                                        <th>Lama</th>
                                        <th>Tanggal</th>
                                        <th>No Sertifikat</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_kursus) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_kursus); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_kursus[$i]->TIPE_KURSUS ?></td>
                                                <td><?= $rwt_kursus[$i]->JENIS_KURSUS ?></td>
                                                <td><?= $rwt_kursus[$i]->NAMA_KURSUS ?></td>
                                                <td><?= $rwt_kursus[$i]->LAMA_KURSUS ?> Jam</td>
                                                <td><?= getIndonesiaFormat($rwt_kursus[$i]->TANGGAL_KURSUS) ?></td>
                                                <td><?= $rwt_kursus[$i]->NO_SERTIFIKAT ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="7">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-body">
                        <h4 style="text-align: center;">Ruang Riwayat Pekerjaan</h4>
                        <div class="box-header no-border">
                            <h3 class="box-title">Kepangkatan</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Pangkat Golongan</th>
                                        <th>Jenis Kenaikan Pangkat</th>
                                        <th>TMT</th>
                                        <th>No. SK</th>
                                        <th>Tanggal SK</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_kepangkatan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_kepangkatan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_kepangkatan[$i]->PANGKAT ?> <?= $rwt_kepangkatan[$i]->GOLONGAN ?></td>
                                                <td class="text-center"><?= $rwt_kepangkatan[$i]->jenis_kp_text ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_kepangkatan[$i]->TMT_GOLONGAN) ?></td>
                                                <td class="text-center"><?= $rwt_kepangkatan[$i]->SK_NOMOR ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_kepangkatan[$i]->SK_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Kenaikan Gaji Berkala</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                    <tr>
                                        <th width='15px'>No</th>
                                        <th>TMT KGB</th>
                                        <th width='10%'>No SK / Tanggal SK</th>
                                        <th>Gol. Ruang</th>
                                        <th>Masa Kerja</th>
                                        <th>Gaji Pokok</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_kgb) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_kgb); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_kgb[$i]->tmt_sk) ?></td>
                                                <td class="text-center"><?= $rwt_kgb[$i]->no_sk ?><br><?= getIndonesiaFormat($rwt_kgb[$i]->tgl_sk) ?></td>
                                                <td class="text-center"><?= $rwt_kgb[$i]->golongan_name ?></td>
                                                <td class="text-center"><?= $rwt_kgb[$i]->n_masakerja_thn ?> Tahun <?= $rwt_kgb[$i]->n_masakerja_bln ?> Bulan</td>
                                                <td class="text-center"><?= rupiah($rwt_kgb[$i]->n_gapok) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Jabatan Pelaksana</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>TMT</th>
                                        <th>No. SK / Tgl SK</th>
                                        <th>No. SK / Tgl Berhenti</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_jabatan_pelaksana) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_jabatan_pelaksana); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_jabatan_pelaksana[$i]->NAMA_JABATAN ?></td>
                                                <td><?= $rwt_jabatan_pelaksana[$i]->NAMA_SATKER ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_jabatan_pelaksana[$i]->TMT_JABATAN) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_pelaksana[$i]->NOMOR_SK ?>/<?= getIndonesiaFormat($rwt_jabatan_pelaksana[$i]->TANGGAL_SK) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_pelaksana[$i]->SK_PEMBERHENTIAN_NOMOR ?>/<?= getIndonesiaFormat($rwt_jabatan_pelaksana[$i]->SK_PEMBERHENTIAN_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Jabatan Fungsional</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>TMT</th>
                                        <th>No. SK / Tgl SK</th>
                                        <th>No. SK / Tgl Berhenti</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_jabatan_fungsional) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_jabatan_fungsional); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_jabatan_fungsional[$i]->NAMA_JABATAN ?></td>
                                                <td><?= $rwt_jabatan_fungsional[$i]->NAMA_SATKER ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_jabatan_fungsional[$i]->TMT_JABATAN) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_fungsional[$i]->NOMOR_SK ?>/<?= getIndonesiaFormat($rwt_jabatan_fungsional[$i]->TANGGAL_SK) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_fungsional[$i]->SK_PEMBERHENTIAN_NOMOR ?>/<?= getIndonesiaFormat($rwt_jabatan_fungsional[$i]->SK_PEMBERHENTIAN_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Penugasan</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>TMT</th>
                                        <th>No. SK / Tgl SK</th>
                                        <th>No. SK / Tgl Berhenti</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_jabatan_penugasan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_jabatan_penugasan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <?php
                                                if ($rwt_jabatan_penugasan[$i]->ID_JENIS_JABATAN == 1) {
                                                    $jabatan = $rwt_jabatan_penugasan[$i]->status_jabatan . " " . $rwt_jabatan_penugasan[$i]->NAMA_JABATAN;
                                                } else {
                                                    $jabatan = $rwt_jabatan_penugasan[$i]->NAMA_JABATAN . " pada";
                                                }

                                                $jabatan .= " " . $rwt_jabatan_penugasan[$i]->NAMA_UNOR;
                                                ?>
                                                <td><?= $jabatan ?></td>
                                                <td><?= $rwt_jabatan_penugasan[$i]->NAMA_SATKER ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_jabatan_penugasan[$i]->TMT_JABATAN) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_penugasan[$i]->NOMOR_SK ?>/<?= getIndonesiaFormat($rwt_jabatan_penugasan[$i]->TANGGAL_SK) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_penugasan[$i]->SK_PEMBERHENTIAN_NOMOR ?>/<?= getIndonesiaFormat($rwt_jabatan_penugasan[$i]->SK_PEMBERHENTIAN_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Jabatan Struktural</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Jabatan</th>
                                        <th>Unit Kerja</th>
                                        <th>TMT</th>
                                        <th>No. SK / Tgl SK</th>
                                        <th>No. SK / Tgl Berhenti</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_jabatan_struktural) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_jabatan_struktural); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_jabatan_struktural[$i]->NAMA_JABATAN ?></td>
                                                <td><?= $rwt_jabatan_struktural[$i]->NAMA_SATKER ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_jabatan_struktural[$i]->TMT_JABATAN) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_struktural[$i]->NOMOR_SK ?>/<?= getIndonesiaFormat($rwt_jabatan_struktural[$i]->TANGGAL_SK) ?></td>
                                                <td class="text-center"><?= $rwt_jabatan_struktural[$i]->SK_PEMBERHENTIAN_NOMOR ?>/<?= getIndonesiaFormat($rwt_jabatan_fungsional[$i]->SK_PEMBERHENTIAN_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Pindah Unit Kerja</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Unit Kerja</th>
                                        <th>Satuan Kerja</th>
                                        <th width='100px'>NO SK</th>
                                        <th width='100px'>SK TANGGAL</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_pindah_unit) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_pindah_unit); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_pindah_unit[$i]->NAMA_UNOR_BARU ?></td>
                                                <td><?= $rwt_pindah_unit[$i]->NAMA_SATUAN_KERJA ?></td>
                                                <td class="text-center"><?= $rwt_pindah_unit[$i]->SK_NOMOR ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_pindah_unit[$i]->SK_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Sebelum Jadi PNS</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th>No</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Sebagai</th>
                                        <th width='200px'>Periode</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_pekerjaan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_pekerjaan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_pekerjaan[$i]->NAMA_PERUSAHAAN ?></td>
                                                <td><?= $rwt_pekerjaan[$i]->SEBAGAI ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_pekerjaan[$i]->DARI_TANGGAL) ?> - <?= getIndonesiaFormat($rwt_pekerjaan[$i]->SAMPAI_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-body">
                        <h4 style="text-align: center;">Ruang Riwayat Aktivitas</h4>
                        <div class="box-header no-border">
                            <h3 class="box-title">Aktivitas Ilmiah</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Aktivitas</th>
                                        <th>Lokasi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_aktivitas_ilmiah) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_aktivitas_ilmiah); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_aktivitas_ilmiah[$i]->starting_date) ?> - <?= getIndonesiaFormat($rwt_aktivitas_ilmiah[$i]->ending_date) ?></td>
                                                <td><?= $rwt_aktivitas_ilmiah[$i]->scientific_activity_name ?></td>
                                                <td><?= $rwt_aktivitas_ilmiah[$i]->location ?></td>
                                                <td class="text-center"><?= $rwt_aktivitas_ilmiah[$i]->explanation ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>

                        <div class="box-header no-border">
                            <h3 class="box-title">Karya Tulis Ilmiah</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th width='300px'>Judul</th>
                                        <th>Tahun</th>
                                        <th>Skala</th>
                                        <th>Penerbit</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_karyatulis_ilmiah) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_karyatulis_ilmiah); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_karyatulis_ilmiah[$i]->paper_title ?></td>
                                                <td class="text-center"><?= $rwt_karyatulis_ilmiah[$i]->publish_year ?></td>
                                                <td class="text-center"><?= $rwt_karyatulis_ilmiah[$i]->scale ?></td>
                                                <td class="text-center"><?= $rwt_karyatulis_ilmiah[$i]->publisher_detail ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Penilaian Prestasi Kerja</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Tahun</th>
                                        <th>Nilai PPK</th>
                                        <th>Nilai SKP</th>
                                        <th>Nilai Perilaku</th>
                                        <th width='30%'>Jabatan</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_ppk) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_ppk); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= $rwt_ppk[$i]->TAHUN ?></td>
                                                <td class="text-center"><?= round($rwt_ppk[$i]->NILAI_PPK, 2) ?></td>
                                                <td class="text-center"><?= round($rwt_ppk[$i]->NILAI_SKP, 2) ?></td>
                                                <td class="text-center"><?= round($rwt_ppk[$i]->NILAI_PERILAKU, 2) ?></td>
                                                <td><?= $rwt_ppk[$i]->JABATAN_NAMA ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Penghargaan</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Penghargaan</th>
                                        <th>Keterangan</th>
                                        <th width='100px'>No Sertifikat</th>
                                        <th width='100px'>Tanggal Sertifikat</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_penghargaan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_penghargaan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_penghargaan[$i]->jenis_penghargaan_text ?></td>
                                                <td><?= $rwt_penghargaan[$i]->KETERANGAN ?></td>
                                                <td class="text-center"><?= $rwt_penghargaan[$i]->SK_NOMOR ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_penghargaan[$i]->SK_TANGGAL) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Hukuman Disiplin</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Jenis Hukuman</th>
                                        <th>Nomor SK</th>
                                        <th width='100px'>Tanggal SK</th>
                                        <th width='100px'>Tanggal Mulai</th>
                                        <th width='100px'>Masa Tahun</th>
                                        <th width='100px'>Masa Bulan</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_hukdis) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_hukdis); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $rwt_hukdis[$i]->NAMA_HUKUMAN ?></td>
                                                <td class="text-center"><?= $rwt_hukdis[$i]->SK_NOMOR ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_hukdis[$i]->SK_TANGGAL) ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_hukdis[$i]->TANGGAL_MULAI_HUKUMAN) ?></td>
                                                <td class="text-center"><?= $rwt_hukdis[$i]->MASA_TAHUN ?></td>
                                                <td class="text-center"><?= $rwt_hukdis[$i]->MASA_BULAN ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="7">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">CLTN</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Jenis</th>
                                        <th>Nomor SK</th>
                                        <th width='100px'>Tanggal SK</th>
                                        <th width='100px'>Tanggal</th>
                                        <th width='100px'>Status</th>
                                    </tr>
                                </thead>
                                <?php if ($rwt_cltn) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($rwt_cltn); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= $rwt_cltn[$i]->cltn_id == '01' ? "Baru" : "Perpanjangan" ?></td>
                                                <td class="text-center"><?= $rwt_cltn[$i]->nomor_sk ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_cltn[$i]->tanggal_sk) ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($rwt_cltn[$i]->tanggal_awal) ?> - <?= getIndonesiaFormat($rwt_cltn[$i]->tanggal_akhir) ?></td>
                                                <td class="text-center"><?= $rwt_hukdis[$i]->status == 1 ? 'Aktif CLTN' : 'Selesai' ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="6">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="box box-danger">
                    <div class="box-body">
                        <h4 style="text-align: center;">Data Keluarga</h4>
                        <div class="box-header no-border">
                            <h3 class="box-title">Orang Tua</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Hubungan</th>
                                        <th>Nama</th>
                                        <th>Tanggal Lahir</th>
                                    </tr>
                                </thead>
                                <?php if ($orang_tua) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($orang_tua); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= $orang_tua[$i]->HUBUNGAN == 1 ? 'Ayah' : 'Ibu' ?></td>
                                                <td><?= $orang_tua[$i]->NAMA ?></td>
                                                <td class="text-center"><?= getIndonesiaFormat($orang_tua[$i]->TANGGAL_LAHIR) ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="4">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Pasangan</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Hubungan</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>PNS</th>
                                    </tr>
                                </thead>
                                <?php if ($pasangan) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($pasangan); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td class="text-center"><?= $pasangan[$i]->HUBUNGAN == '1' ? 'Istri' : 'Suami' ?></td>
                                                <td><?= $pasangan[$i]->NAMA ?></td>
                                                <td class="text-center"><?= $pasangan[$i]->STATUS == 1 ? 'Menikah' : 'Cerai' ?></td>
                                                <td class="text-center"><?= $pasangan[$i]->PNS == 1 ? 'Iya' : 'Bukan' ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                        <div class="box-header no-border">
                            <h3 class="box-title">Anak</h3>
                        </div>

                        <fieldset>
                            <table class="table table-bordered mx-0 my-0 table-striped">
                                <thead>
                                    <tr class="table-danger">
                                        <th width='15px'>No</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat, Tanggal Lahir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <?php if ($anaks) { ?>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($anaks); $i++) { ?>
                                            <tr>
                                                <td class="text-center"><?= $i + 1 ?></td>
                                                <td><?= $anaks[$i]->NAMA ?></td>
                                                <td class="text-center"><?= $anaks[$i]->JENIS_KELAMIN == 'M' ? 'Pria' : 'Wanita' ?></td>
                                                <td class="text-center"><?= $anaks[$i]->TEMPAT_LAHIR ?>, <?= getIndonesiaFormat($anaks[$i]->TANGGAL_LAHIR) ?></td>
                                                <td class="text-center"><?= $anaks[$i]->STATUS_ANAK == 1 ? 'Kandung' : ($anaks[$i]->STATUS_ANAK == 2 ?'Angkat':'Tiri') ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                <?php } else { ?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">-- tidak ada data -- </td>
                                        </tr>
                                    <?php } ?>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div>
                    <fieldset style="margin: 5px;">
                        <p>Demikian daftar riwayat hidup ini saya buat dengan sesungguhnya dan apabila dikemudian hari terdapat keterangan yang tidak benar saya bersedia dituntut di muka pengadilan serta bersedia menerima segala tindakan yang diambil oleh pemerintah.</p>
                        <table class="borderless" width="100%" style="border: none;">
                            <tbody>
                                <tr class="noborder">
                                    <td width="60%">
                                    </td>
                                    <td width="40%" align="center">
                                        ............................, <?= getIndonesiaFormat(date('Y-m-d')) ?>
                                        <br><br><br><br>
                                    </td>
                                </tr>
                                <tr class="noborder">
                                    <td width="60%">
                                    </td>
                                    <td width="40%" align="center">
                                        <b>(<u><?= $glr_depan . $pegawai->NAMA . $glr_blk ?></u>)</b>
                                    </td>
                                </tr>
                                <tr class="noborder">
                                    <td width="60%">
                                    </td>
                                    <td width="40%" align="center"><b><?= $pegawai->NIP_BARU ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </div>

            </div>
        </div>
    </section>
</body>
<script type="text/javascript">
    window.onload = function() {
        window.print();
    }
</script>

</html>