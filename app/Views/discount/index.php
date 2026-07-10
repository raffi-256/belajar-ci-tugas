<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>
    </div>
<?php endif; ?>

<h4 class="mb-3">Diskon</h4>

<div class="mb-3">
    <button
        type="button"
        class="btn btn-primary btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#modalTambahDiskon">

        Tambah Data
    </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover datatable">
        <thead>
            <tr>
                <th width="70">No</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th width="170">Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($discount)) : ?>

                <?php $no = 1; ?>

                <?php foreach ($discount as $d) : ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td><?= esc($d['tanggal']) ?></td>

                        <td><?= esc($d['nominal']) ?></td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalUbah<?= $d['id'] ?>">

                                Ubah
                            </button>

                            <form
                                action="<?= base_url('discount/delete/' . $d['id']) ?>"
                                method="post"
                                class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                <?= csrf_field() ?>

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm">

                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else : ?>
                <tr>
                    <td colspan="4" class="text-center">
                        Belum ada data diskon.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Diskon -->
<div
    class="modal fade"
    id="modalTambahDiskon"
    tabindex="-1"
    aria-labelledby="modalTambahDiskonLabel"
    aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <form
                action="<?= base_url('discount/store') ?>"
                method="post">

                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDiskonLabel">
                        Tambah Data
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="tanggalTambah" class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggalTambah"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="nominalTambah" class="form-label">
                            Nominal
                        </label>

                        <input
                            type="number"
                            name="nominal"
                            id="nominalTambah"
                            class="form-control"
                            min="1"
                            placeholder="Masukkan nominal diskon"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Modal Ubah Diskon -->
<?php if (!empty($discount)) : ?>

    <?php foreach ($discount as $d) : ?>

        <div
            class="modal fade"
            id="modalUbah<?= $d['id'] ?>"
            tabindex="-1"
            aria-labelledby="modalUbahLabel<?= $d['id'] ?>"
            aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <form
                        action="<?= base_url('discount/update/' . $d['id']) ?>"
                        method="post">

                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="modalUbahLabel<?= $d['id'] ?>">

                                Ubah Data
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label
                                    for="tanggalUbah<?= $d['id'] ?>"
                                    class="form-label">

                                    Tanggal
                                </label>

                                <input
                                    type="date"
                                    name="tanggal"
                                    id="tanggalUbah<?= $d['id'] ?>"
                                    class="form-control"
                                    value="<?= esc($d['tanggal']) ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label
                                    for="nominalUbah<?= $d['id'] ?>"
                                    class="form-label">

                                    Nominal
                                </label>

                                <input
                                    type="number"
                                    name="nominal"
                                    id="nominalUbah<?= $d['id'] ?>"
                                    class="form-control"
                                    value="<?= esc($d['nominal']) ?>"
                                    min="1"
                                    required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Close
                            </button>

                            <button
                                type="submit"
                                class="btn btn-success">

                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php endif; ?>

<?= $this->endSection() ?>