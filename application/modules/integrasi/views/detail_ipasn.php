<div class='box box-warning' id="form-skp-add">
    <div class="box-body">
            <div class="col-md-12">
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">NIP</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->nip_baru ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->nama ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Jenis Jabatan</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->jenis_jabatan ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Jenjang Jabatan</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->jenjang_jabatan ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Kualifikasi</label>
                            <input disabled="" type="text" class="form-control" value="Riwayat Pendidikan Terakhir (nilai maks 25)">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label class="form-label">Skor</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->kualifikasi ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea disabled="" class="form-control"><?= $data->keterangan_kualifikasi ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Kompetensi</label>
                            <input disabled="" type="text" class="form-control" value="Riwayat Pengembangan Kompetensi (nilai maks 40)">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label class="form-label">Skor</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->kompetensi ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea rows="6" disabled="" class="form-control"><?= $data->keterangan_kompetensi ?> </textarea>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Kinerja</label>
                            <input disabled="" type="text" class="form-control" value="Hasil Penilaian Kinerja (nilai maks 30)">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label class="form-label">Skor</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->kinerja ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea disabled="" class="form-control"><?= $data->keterangan_kinerja ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Disiplin</label>
                            <input disabled="" type="text" class="form-control" value="Riwayat Hukum Disiplin (nilai maks 5)">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label class="form-label">Skor</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->disiplin ?>">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea disabled="" class="form-control"><?= $data->keterangan_disiplin ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Sub Total</label>
                            <input disabled="" type="text" class="form-control" value="Nilai maks 100">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label class="form-label">Skor</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->subtotal ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-10 col-12">
                        <div class="form-group">
                            <label class="form-label">Kondisi Data</label>
                            <input disabled="" type="text" class="form-control" value="<?= $data->created_at ?>">
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>