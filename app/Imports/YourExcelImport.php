<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class YourExcelImport implements ToCollection
{
    /**
     * Tentukan baris awal untuk memulai impor data.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Mulai dari baris ke-2 untuk mengabaikan header
    }

    /**
     * Proses koleksi data yang diimpor.
     *
     * @param Collection $rows
     * @return array
     */
    public function collection(Collection $rows)
    {

    }
}
