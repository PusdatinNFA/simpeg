<?php
$this->load->library('convert');
$convert = new convert();
function getIndonesiaFormat($date)
{
    if ($date) {
        $dt = DateTime::createFromFormat('Y-m-j', $date);
        if (!$dt) {
            $dt = DateTime::createFromFormat('Y-m-j H:i:s', $date);
        }
        if ($dt) {
            $bulans = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
            return $dt->format('j') . " " . $bulans[$dt->format('m') - 1] . " " . $dt->format('Y');
        }
    }
    return "-";
}
?>
<div class="tab-pane" id="<?php echo $TAB_ID; ?>">
    <div class="callout callout-info">
        <h4>Perhatian</h4>
        <p>Jika ada kesalahan data, silahkan laporkan ke bagian kepegawaian kawasan anda</p>
    </div>
    <br>
    <br>
    <table class="table table-bordered mx-0 my-0 table-striped">
        <thead>
            <tr class="table-danger">
                <td>Informasi</td>
                <td>Keterangan</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>KP Terakhir</td>
                <td><?= getIndonesiaFormat($last_kp->tmt_sk) ?></td>
            </tr>
            <?php if ($JABATAN_PROFIL->ID_JENIS_JABATAN == '2') { ?>
                <tr>
                    <td>Jumlah AK Terakhir</td>
                    <td><?= $last_ak ? $last_ak->ak_akhir_total : '-' ?></td>
                </tr>
            <?php } else { ?>
                <!-- <tr>
                    <td>Estimasi KP Selanjutnya</td>
                    <td><?= getIndonesiaFormat($kgb->tmt_gol_valid) ?></td>
                </tr> -->
            <?php } ?>

            <tr>
                <td>KGB Terakhir</td>
                <td><?= getIndonesiaFormat($last_kgb->tmt_sk) ?></td>
            </tr>
            <?php $date1 = $last_kgb->tmt_sk;
            $date2 = $kgb->tmt_gol_valid;

            $diff = abs(strtotime($date2) - strtotime($date1));

            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            // $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $terlewat = (($years > 2) || ($years = 2 && $months > 0))? 1: 0;
            ?>
            <tr>
                <td>KGB Selanjutnya</td>
                <?php if (trim($kgb->statuskedua) != 'KGB HABIS') { ?>
                    <td><?= getIndonesiaFormat($kgb->tmt_gol_valid); if ($terlewat) echo "   <label class='label label-danger'>KGB Tidak Lengkap</label>" ?></td>
                <?php } else echo ('<td>KGB HABIS</td>') ?>
            </tr>
            <tr>
                <td>Batas Usia Pensiun</td>
                <td><?= $pensiun->PENSIUN ?></td>
            </tr>
            <tr>
                <td>Estimasi Tanggal Pensiun</td>
                <td><?= getIndonesiaFormat($pensiun->tahunpensiun) ?></td>
            </tr>
        </tbody>
    </table>

</div>