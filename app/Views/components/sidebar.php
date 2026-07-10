<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <?php
    // Mengambil bagian pertama dari URL.
    // Contoh:
    // /produk              menghasilkan "produk"
    // /pembelian/detail/1 menghasilkan "pembelian"
    $menuAktif = service('uri')->getSegment(1);

    $role = strtolower((string) session()->get('role'));
    ?>

    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Home -->
        <li class="nav-item">
            <a
                class="nav-link <?= empty($menuAktif) ? '' : 'collapsed' ?>"
                href="<?= base_url('/') ?>">

                <i class="bi bi-grid"></i>
                <span>Home</span>
            </a>
        </li>

        <!-- Keranjang -->
        <li class="nav-item">
            <a
                class="nav-link <?= $menuAktif === 'keranjang' ? '' : 'collapsed' ?>"
                href="<?= base_url('keranjang') ?>">

                <i class="bi bi-cart-check"></i>
                <span>Keranjang</span>
            </a>
        </li>

        <?php if ($role === 'admin') : ?>

            <!-- Produk -->
            <li class="nav-item">
                <a
                    class="nav-link <?= $menuAktif === 'produk' ? '' : 'collapsed' ?>"
                    href="<?= base_url('produk') ?>">

                    <i class="bi bi-box-seam"></i>
                    <span>Produk</span>
                </a>
            </li>

            <!-- Diskon -->
            <li class="nav-item">
                <a
                    class="nav-link <?= $menuAktif === 'discount' ? '' : 'collapsed' ?>"
                    href="<?= base_url('discount') ?>">

                    <i class="bi bi-percent"></i>
                    <span>Diskon</span>
                </a>
            </li>

            <!-- Pembelian -->
            <li class="nav-item">
                <a
                    class="nav-link <?= $menuAktif === 'pembelian' ? '' : 'collapsed' ?>"
                    href="<?= base_url('pembelian') ?>">

                    <i class="bi bi-receipt"></i>
                    <span>Pembelian</span>
                </a>
            </li>

        <?php endif; ?>

        <!-- History -->
        <li class="nav-item">
            <a
                class="nav-link <?= $menuAktif === 'history' ? '' : 'collapsed' ?>"
                href="<?= base_url('history') ?>">

                <i class="bi bi-clock-history"></i>
                <span>History</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item">
            <a
                class="nav-link <?= $menuAktif === 'profile' ? '' : 'collapsed' ?>"
                href="<?= base_url('profile') ?>">

                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- FAQ -->
        <li class="nav-item">
            <a
                class="nav-link <?= $menuAktif === 'faq' ? '' : 'collapsed' ?>"
                href="<?= base_url('faq') ?>">

                <i class="bi bi-question-circle"></i>
                <span>FAQ</span>
            </a>
        </li>

        <!-- Contact -->
        <li class="nav-item">
            <a
                class="nav-link <?= $menuAktif === 'contact' ? '' : 'collapsed' ?>"
                href="<?= base_url('contact') ?>">

                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li>

    </ul>

</aside>
<!-- End Sidebar -->