<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use DateTimeImmutable;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $builder = $this->db->table('discount');

        /*
         * Mengosongkan tabel agar ketika seeder dijalankan ulang,
         * tidak terjadi duplikasi tanggal.
         */
        $builder->truncate();

        // Tanggal saat seeder dijalankan
        $tanggalAwal = new DateTimeImmutable('today');

        // 10 nominal diskon
        $daftarNominal = [
            100000,
            100000,
            200000,
            150000,
            250000,
            300000,
            300000,
            300000,
            300000,
            300000,
        ];

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $tanggalDiskon = $tanggalAwal->modify("+{$i} day");

            $data[] = [
                'tanggal'    => $tanggalDiskon->format('Y-m-d'),
                'nominal'    => $daftarNominal[$i],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
                'deleted_at' => null,
            ];
        }

        $builder->insertBatch($data);
    }
}