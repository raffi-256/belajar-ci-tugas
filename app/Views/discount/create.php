<!DOCTYPE html>
<html>
<head>
    <title>Tambah Diskon</title>
</head>
<body>

<h2>Tambah Diskon</h2>

<form action="<?= site_url('discount/store') ?>" method="post">
    <?= csrf_field() ?>

    <div>
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>
    </div>

    <div>
        <label>Nominal</label>
        <input type="number" name="nominal" required>
    </div>

    <button type="submit">Simpan</button>
</form>

</body>
</html>