<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
$this->load->library('convert');
$convert = new convert();
$tab_riwayat = "tab_riwayat";
$tab_pane_personal_id = "tab_pane_personal_id";
?>

<?php

if (validation_errors()):
  ?>
  <div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
      <?php echo lang('pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
  </div>
  <?php
endif;
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
?>
<div class="row">
  <div class="col-md-12">
    <!-- /.box -->
    <form role="form" action="#" id="frmprofile">
      <input id='ID' type='hidden' class="form-control" name='ID' maxlength='25'
        value="<?php echo set_value('ID', isset($pegawai_bkn->ID) ? $pegawai_bkn->ID : ''); ?>" />
      <div class="box box-info">
        <div class="box-header no-border">
          <h3 class="profile-username text-center">
            <?php echo isset($pegawai_bkn->gelarDepan) ? $pegawai_bkn->GELAR_DEPAN : ''; ?>
            <?php echo isset($pegawai_bkn->nama) ? $pegawai_bkn->nama : ''; ?> <?php echo isset($pegawai_bkn->gelarBelakang) ? $pegawai_bkn->gelarBelakang : '';
                    ?>
          </h3>
          <div class="box-tools pull-right">
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <fieldset>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="col-sm-4">
                  NIP
                </div>
                <div class="form-group col-sm-8">
                  <b><?php echo isset($pegawai_bkn->nipBaru) ? $pegawai_bkn->nipBaru : ''; ?></b>
                  <?php echo $pegawai_bkn->nipBaru != $pegawai->NIP_BARU ? "<i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->NIP_BARU . "'></i>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-6">
              <div class="row">
                <div class="form-group col-sm-8">
                  TMT CPNS
                </div>
                <div class="form-group col-sm-4">
                  <b><?php echo isset($pegawai_bkn->tmtCpns) ? $pegawai_bkn->tmtCpns : '-'; ?></b>
                  <?php echo $pegawai_bkn->tmtCpns != $convert->fmtDate($pegawai->TMT_CPNS, "dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='tmtCpns' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $convert->fmtDate($pegawai->TMT_CPNS, "dd-mm-yyyy") . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-6">
              <div class="row">
                <div class="form-group col-sm-4">
                  SK CPNS
                </div>
                <div class="form-group  col-sm-8">
                  <b><?php echo isset($pegawai_bkn->nomorSkCpns) ? $pegawai_bkn->nomorSkCpns : '-'; ?></b>
                  <?php echo $pegawai_bkn->nomorSkCpns != $pegawai->NOMOR_SK_CPNS ? "<a href='#' class='update_profile' kode='nomorSkCpns' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->NOMOR_SK_CPNS . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="form-group col-sm-12">
              <div class="row">
                <div class="col-sm-4">
                  Status Kepegawaian
                </div>
                <div class="col-sm-8">
                  <?php $status_pns = isset($pegawai->STATUS_CPNS_PNS) ? $pegawai->STATUS_CPNS_PNS == "P" ? "PNS" : "CPNS" : ''; ?>
                  <b><?php echo isset($pegawai_bkn->statusPegawai) ? $pegawai_bkn->statusPegawai : '-'; ?></b>
                  <?php echo $pegawai_bkn->statusPegawai != $status_pns ? "<a href='#' class='update_profile' kode='statusPegawai' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $status_pns . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="form-group col-sm-12">
              <div class="row">
                <div class="col-sm-4">
                  Kartu Pegawai
                </div>
                <div class="col-sm-8">
                  <b><?php echo isset($pegawai_bkn->noSeriKarpeg) ? $pegawai_bkn->noSeriKarpeg : '-'; ?></b>
                  <?php echo $pegawai_bkn->noSeriKarpeg != $pegawai->KARTU_PEGAWAI ? "<a href='#' class='update_profile' kode='noSeriKarpeg' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->KARTU_PEGAWAI . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  No Surat Keterangan Dokter (CPNS)
                </div>
                <div class="form-group col-sm-8">
                  <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai_bkn->noSuratKeteranganDokter) ? trim($pegawai_bkn->noSuratKeteranganDokter) : '-'); ?></b>
                  <?php echo $pegawai_bkn->noSuratKeteranganDokter != $pegawai->NO_SURAT_DOKTER ? "<a href='#' class='update_profile' kode='noSuratKeteranganDokter' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->NO_SURAT_DOKTER . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  Tanggal Surat Keterangan Dokter
                </div>
                <div class="form-group  col-sm-8">
                  <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai_bkn->tglSuratKeteranganDokter) ? trim($pegawai_bkn->tglSuratKeteranganDokter) : '-'); ?></b>
                  <?php echo $pegawai_bkn->tglSuratKeteranganDokter != $convert->fmtDate($pegawai->TGL_SURAT_DOKTER, "dd-mm-yyyy") ? "<a href='#' class='update_profile' kode='tglSuratKeteranganDokter' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $convert->fmtDate($pegawai->TGL_SURAT_DOKTER, "dd-mm-yyyy") . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>

            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  No Surat Bebas Narkoba (CPNS)
                </div>
                <div class="form-group col-sm-8">
                  <b><?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai_bkn->noSuratKeteranganBebasNarkoba) ? trim($pegawai_bkn->noSuratKeteranganBebasNarkoba) : ''); ?></b>
                  <?php echo $pegawai_bkn->noSuratKeteranganBebasNarkoba != $pegawai->NO_BEBAS_NARKOBA ? "<a href='#' class='update_profile' kode='noSuratKeteranganBebasNarkoba' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->NO_BEBAS_NARKOBA . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  Tanggal No Surat Bebas Narkoba
                </div>
                <div class="form-group  col-sm-8">
                  <b>
                    <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai_bkn->tglSuratKeteranganBebasNarkoba) ? $pegawai_bkn->tglSuratKeteranganBebasNarkoba : '-'); ?>
                  </b>
                  <?php echo $pegawai_bkn->tglSuratKeteranganBebasNarkoba != $convert->fmtDate($pegawai->TGL_BEBAS_NARKOBA, "dd-mm-yyyy") && $pegawai->TGL_BEBAS_NARKOBA != "1970-01-01" ? "<a href='#' class='update_profile' kode='tglSuratKeteranganBebasNarkoba' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $convert->fmtDate($pegawai->TGL_BEBAS_NARKOBA, "dd-mm-yyyy") . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  No Catatan Kepolisian
                </div>
                <div class="form-group col-sm-8">
                  <b><?php echo set_value('skck', isset($pegawai_bkn->skck) ? trim($pegawai_bkn->skck) : ''); ?></b>
                  <?php echo $pegawai_bkn->skck != $pegawai->NO_CATATAN_POLISI ? "<a href='#' class='update_profile' kode='skck' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $pegawai->NO_CATATAN_POLISI . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
            <div class="control-group col-sm-12">
              <div class="row">
                <div class="form-group col-sm-4">
                  Tanggal Catatan Kepolisian
                </div>
                <div class="form-group  col-sm-8">
                  <b>
                    <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai_bkn->tglSkck) ? $pegawai_bkn->tglSkck : ''); ?>
                  </b>
                  <?php echo $pegawai_bkn->tglSkck != $convert->fmtDate($pegawai->TGL_CATATAN_POLISI, "dd-mm-yyyy") && $pegawai->TGL_CATATAN_POLISI != "1970-01-01" ? "<a href='#' class='update_profile' kode='tglSkck' nip='" . $pegawai_bkn->nipBaru . "'><i class='fa fa-check-circle text-danger' data-toggle='tooltip' title='Profile : " . $convert->fmtDate($pegawai->TGL_CATATAN_POLISI, "dd-mm-yyyy") . "'></i></a>" : "<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>"; ?>
                </div>
              </div>
            </div>
          </fieldset>
          <!-- /.table-responsive -->

          <!-- /.box-body -->

          <!-- /.box-footer -->

        </div>
      </div>
    </form>
  </div>
  <!-- /.col -->
</div>

<script type="text/javascript">
  $('body').on('click', '.update_profile', function () {
    var kode = $(this).attr("kode");
    var valnip = $(this).attr("nip");
    let el = $(this); // Simpan referensi elemen yang diklik
    swal({
      title: "Anda Yakin?",
      text: "Update data " + kode + "!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      confirmButtonText: 'Ya!',
      cancelButtonText: "Tidak, Batalkan!",
      closeOnConfirm: false,
      closeOnCancel: false
    },
      function (isConfirm) {
        if (isConfirm) {
          var post_data = "kode=" + kode + "&nip=" + valnip;
          $.ajax({
            url: "<?php echo base_url() ?>pegawai/bkn/save_data_utama",
            type: "POST",
            data: post_data,
            dataType: "json",
            timeout: 180000,
            success: function (result) {
              if (result.success) {
                // Perbarui tampilan elemen dengan nilai baru
                // el.closest(".form-group").find("b").text(response.new_value);

                // Ganti ikon menjadi hijau (data sudah sama)
                el.replaceWith("<i class='fa fa-check-circle text-success' data-toggle='tooltip' title='Data sama'></i>");

                swal("Perhatian!", result.msg, "success");
              } else {
                swal("Perhatian!", result.msg, "error");
              }
            },
            error: function (error) {
              alert(error);
            }
          });

        } else {
          swal("Batal", "", "error");
        }
      });
  });
</script>