<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\DiscountModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    protected $productModel;
    protected $discountModel;

    public function __construct()
    {
        helper(['number', 'form']);

        $this->productModel = new ProductModel();
        $this->discountModel = new DiscountModel();
    }

    public function index(): string
    {
        // Tanggal hari ini (Asia/Jakarta)
        $tanggalHariIni = Time::today('Asia/Jakarta')->toDateString();

        // Ambil diskon hari ini
        $discountHariIni = $this->discountModel
            ->where('tanggal', $tanggalHariIni)
            ->first();

        $data = [
            'products' => $this->productModel->findAll(),
            'discountHariIni' => $discountHariIni,
            'nominalDiscount' => $discountHariIni
                ? (float) $discountHariIni['nominal']
                : 0,
        ];

        return view('v_home', $data);
    }
}