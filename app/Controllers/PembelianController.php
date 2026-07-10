<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class PembelianController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form']);

        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel =
            new TransactionDetailModel();
    }

    /**
     * Memastikan halaman hanya dapat diakses admin.
     */
    private function bukanAdmin(): bool
    {
        $role = strtolower(
            (string) session()->get('role')
        );

        return $role !== 'admin';
    }

    /**
     * Menampilkan seluruh transaksi pembelian.
     */
    public function index()
    {
        if ($this->bukanAdmin()) {
            return redirect()
                ->to(base_url('/'))
                ->with(
                    'error',
                    'Menu pembelian hanya dapat diakses admin.'
                );
        }

        $transactions = $this->transactionModel
            ->orderBy('id', 'DESC')
            ->findAll();

        $data = [
            'transactions' => $transactions,
        ];

        return view('v_pembelian', $data);
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function detail($id)
    {
        if ($this->bukanAdmin()) {
            return redirect()
                ->to(base_url('/'))
                ->with(
                    'error',
                    'Menu pembelian hanya dapat diakses admin.'
                );
        }

        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return redirect()
                ->to(base_url('pembelian'))
                ->with(
                    'error',
                    'Data pembelian tidak ditemukan.'
                );
        }

        $products = $this->transactionDetailModel
            ->getProductsByTransactionIds([$id]);

        $data = [
            'transaction' => $transaction,
            'products' => $products,
        ];

        return view('v_pembelian_detail', $data);
    }

    /**
     * Mengubah status transaksi.
     */
    public function updateStatus($id)
    {
        if ($this->bukanAdmin()) {
            return redirect()
                ->to(base_url('/'))
                ->with(
                    'error',
                    'Menu pembelian hanya dapat diakses admin.'
                );
        }

        $transaction = $this->transactionModel->find($id);

        if (!$transaction) {
            return redirect()
                ->to(base_url('pembelian'))
                ->with(
                    'error',
                    'Data pembelian tidak ditemukan.'
                );
        }

        $status = (int) $this->request->getPost('status');

        if (!in_array($status, [0, 1], true)) {
            return redirect()
                ->to(base_url('pembelian'))
                ->with(
                    'error',
                    'Status pesanan tidak valid.'
                );
        }

        $berhasil = $this->transactionModel->update(
            $id,
            [
                'status' => $status,
            ]
        );

        if (!$berhasil) {
            return redirect()
                ->to(base_url('pembelian'))
                ->with(
                    'error',
                    'Status pesanan gagal diperbarui.'
                );
        }

        return redirect()
            ->to(base_url('pembelian'))
            ->with(
                'success',
                'Status pesanan berhasil diperbarui.'
            );
    }
}