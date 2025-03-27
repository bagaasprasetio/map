<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class YourExcelImport implements ToCollection
{

    /**
     * Proses koleksi data yang diimpor.
     *
     * @param Collection $rows
     * @return array
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            return [
                //'no'                => $row['No'],
                'UM1'        => $row['UM 1'],
                'UM2'        => $row['UM 2'],
                'UM3'        => $row['UM 3'],
                'UM4'        => $row['UM 4'],
            ];
        }
    }
}
