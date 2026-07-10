<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discount';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Data yang boleh disimpan ke database
    protected $allowedFields = [
        'tanggal',
        'nominal',
    ];

    // Tetap gunakan jika tabel discount memiliki kolom deleted_at
    protected $useSoftDeletes = true;

    // Tetap gunakan jika tabel memiliki created_at dan updated_at
    protected $useTimestamps = true;
    protected $dateFormat     = 'datetime';
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    protected $validationRules = [
        'tanggal' => 'required|valid_date[Y-m-d]',
        'nominal' => 'required|integer|greater_than[0]',
    ];

    protected $validationMessages = [
        'tanggal' => [
            'required'   => 'Tanggal diskon harus diisi.',
            'valid_date' => 'Format tanggal diskon tidak benar.',
        ],
        'nominal' => [
            'required'     => 'Nominal diskon harus diisi.',
            'integer'      => 'Nominal diskon harus berupa angka.',
            'greater_than' => 'Nominal diskon harus lebih dari 0.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Mengambil diskon berdasarkan tanggal tertentu.
     */
    public function getDiscountByDate(string $tanggal): ?array
    {
        $discount = $this
            ->where('tanggal', $tanggal)
            ->orderBy('id', 'DESC')
            ->first();

        return $discount ?: null;
    }

    /**
     * Mengambil diskon yang berlaku hari ini.
     */
    public function getDiscountToday(): ?array
    {
        $tanggalHariIni = date('Y-m-d');

        return $this->getDiscountByDate($tanggalHariIni);
    }

    /**
     * Mengambil nominal diskon hari ini.
     */
    public function getNominalDiscountToday(): int
    {
        $discount = $this->getDiscountToday();

        if (empty($discount)) {
            return 0;
        }

        return max(0, (int) $discount['nominal']);
    }
}