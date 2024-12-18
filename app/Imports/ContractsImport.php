<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ContractsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            dd($row);
            // Process each row, treating 'bank_number' as a string
            $contractData = [
                'amount' => $row['amount'],
                'bank_number' => (string)$row['bank_number'], // Cast bank_number to string
            ];
            // Your logic here
        }
    }

    public function headingRow(): int
    {
        return 1; // Adjust based on your Excel structure
    }
}
