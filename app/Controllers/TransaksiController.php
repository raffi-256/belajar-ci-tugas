<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\RajaOngkirService;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\DiscountModel;
use CodeIgniter\I18n\Time;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $discountModel;

    public function __construct()
    {
        helper(['number', 'form']);

        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->discountModel = new DiscountModel();
    }

    /**
     * Mengambil nominal diskon berdasarkan tanggal hari ini.
     */
    private function getDiskonHariIni(): int
    {
        $tanggalHariIni = Time::now('Asia/Jakarta')->toDateString();

        $discount = $this->discountModel
            ->where('tanggal', $tanggalHariIni)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$discount) {
            return 0;
        }

        return max(0, (int) $discount['nominal']);
    }

    /**
     * Menghitung harga dan subtotal setelah diskon.
     */
    private function hitungKeranjangDiskon(
        array $cartItems,
        int $nominalDiskon
    ): array {
        $items = [];
        $total = 0;

        foreach ($cartItems as $rowid => $item) {
            $hargaAsli = (int) $item['price'];
            $jumlah = (int) $item['qty'];

            // Diskon tidak boleh melebihi harga produk.
            $diskonPerItem = min($nominalDiskon, $hargaAsli);

            $hargaSetelahDiskon = max(
                0,
                $hargaAsli - $diskonPerItem
            );

            $subtotalAsli = $hargaAsli * $jumlah;
            $subtotalSetelahDiskon = $hargaSetelahDiskon * $jumlah;

            $item['harga_asli'] = $hargaAsli;
            $item['subtotal_asli'] = $subtotalAsli;
            $item['diskon_per_item'] = $diskonPerItem;
            $item['harga_diskon'] = $hargaSetelahDiskon;
            $item['subtotal_diskon'] = $subtotalSetelahDiskon;

            $items[$rowid] = $item;
            $total += $subtotalSetelahDiskon;
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    /**
     * Halaman keranjang.
     */
    public function index()
    {
        $nominalDiskon = $this->getDiskonHariIni();

        $hasil = $this->hitungKeranjangDiskon(
            $this->cart->contents(),
            $nominalDiskon
        );

        $data = [
            'items' => $hasil['items'],
            'total' => $hasil['total'],
            'diskonHariIni' => $nominalDiskon,
        ];

        return view('v_keranjang', $data);
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function cart_add()
    {
        $this->cart->insert([
            'id' => $this->request->getPost('id'),
            'qty' => 1,
            'price' => (int) $this->request->getPost('harga'),
            'name' => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto'),
            ],
        ]);

        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. 
            <a href="' . base_url('keranjang') . '">Lihat</a>'
        );

        return redirect()->to(base_url('/'));
    }

    /**
     * Memperbarui jumlah produk.
     */
    public function cart_edit()
    {
        $i = 1;

        foreach ($this->cart->contents() as $item) {
            $qty = (int) $this->request->getPost('qty' . $i++);

            if ($qty < 1) {
                $qty = 1;
            }

            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty' => $qty,
            ]);
        }

        session()->setFlashdata(
            'success',
            'Keranjang berhasil diperbarui'
        );

        return redirect()->to(base_url('keranjang'));
    }

    /**
     * Menghapus satu produk dari keranjang.
     */
    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    /**
     * Mengosongkan seluruh keranjang.
     */
    public function cart_clear()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );

        return redirect()->to(base_url('keranjang'));
    }

    /**
     * Halaman checkout.
     */
    public function checkout()
    {
        $cartItems = $this->cart->contents();

        if (empty($cartItems)) {
            return redirect()
                ->to(base_url('keranjang'))
                ->with('error', 'Keranjang masih kosong');
        }

        $nominalDiskon = $this->getDiskonHariIni();

        $hasil = $this->hitungKeranjangDiskon(
            $cartItems,
            $nominalDiskon
        );

        $data = [
            'items' => $hasil['items'],
            'total' => $hasil['total'],
            'diskonHariIni' => $nominalDiskon,
        ];

        return view('v_checkout', $data);
    }

    /**
     * Pencarian kelurahan atau tujuan.
     */
    public function destinations()
    {
        $search = trim((string) $this->request->getGet('q'));

        if ($search === '') {
            return $this->response->setJSON([
                'results' => [],
            ]);
        }

        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id'],
                'text' => $item['label'],
            ];
        }

        return $this->response->setJSON([
            'results' => $results,
        ]);
    }

    /**
     * Mengambil biaya ongkir.
     */
    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = '1000';
        $courier = 'jne';

        if (empty($destination)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'message' => 'Tujuan pengiriman belum dipilih',
                ]);
        }

        $service = new RajaOngkirService();

        $response = $service->getCost(
            $origin,
            $destination,
            $weight,
            $courier
        );

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service' => $item['service'],
                'description' => $item['description'],
                'cost' => $item['cost'],
                'etd' => $item['etd'],
            ];
        }

        return $this->response->setJSON($results);
    }

    /**
     * Menyimpan pesanan.
     */
    public function buy()
    {
        $cartItems = $this->cart->contents();

        if (empty($cartItems)) {
            return redirect()
                ->to(base_url('keranjang'))
                ->with('error', 'Keranjang masih kosong');
        }

        $nominalDiskon = $this->getDiskonHariIni();

        $hasil = $this->hitungKeranjangDiskon(
            $cartItems,
            $nominalDiskon
        );

        $itemsSetelahDiskon = $hasil['items'];
        $subtotal = $hasil['total'];

        $ongkir = max(
            0,
            (int) $this->request->getPost('ongkir')
        );

        $username = session()->get('username');

        if (empty($username)) {
            return redirect()
                ->to(base_url('login'))
                ->with('error', 'Silakan login kembali');
        }

        $alamat = trim(
            (string) $this->request->getPost('alamat')
        );

        if ($alamat === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Alamat harus diisi');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        $transaction = [
            'username' => $username,
            'alamat' => $alamat,
            'ongkir' => $ongkir,
            'total_harga' => $subtotal + $ongkir,
            'status' => 0,
        ];

        if (!$this->transactionModel->insert($transaction)) {
            $db->transRollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi');
        }

        $transactionId = $this->transactionModel->getInsertID();

        foreach ($itemsSetelahDiskon as $item) {
            $detail = [
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'jumlah' => (int) $item['qty'],

                // Nominal diskon untuk satu produk.
                'diskon' => (int) $item['diskon_per_item'],

                // Subtotal sudah memakai harga setelah diskon.
                'subtotal_harga' => (int) $item['subtotal_diskon'],
            ];

            if (!$this->transactionDetailModel->insert($detail)) {
                $db->transRollback();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Gagal menyimpan detail transaksi'
                    );
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi');
        }

        $db->transCommit();

        // Menghapus keranjang setelah transaksi berhasil.
        $this->cart->destroy();

        return redirect()
            ->to(base_url('history'))
            ->with('success', 'Pesanan berhasil dibuat');
    }

    /**
     * Riwayat transaksi pengguna.
     */
    public function history()
    {
        $username = session()->get('username');

        $transactions = $this->transactionModel
            ->where('username', $username)
            ->orderBy('id', 'DESC')
            ->findAll();

        $transactionIds = array_column(
            $transactions,
            'id'
        );

        if (empty($transactionIds)) {
            $products = [];
        } else {
            $products = $this->transactionDetailModel
                ->getProductsByTransactionIds($transactionIds);
        }

        $data = [
            'username' => $username,
            'transactions' => $transactions,
            'products' => $products,
        ];

        return view('v_history', $data);
    }
}