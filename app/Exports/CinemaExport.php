<?php

namespace App\Exports;

use App\Models\Cinema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CinemaExport implements FromCollection, WithHeadings, WithMapping
{

    private $key = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cinema::all();
    }

    public function headings(): array
    {
        return ['No', 'Nama Bioskop', 'Lokasi'];
    }

    public function map($cinema): array
    {
        return [
            ++$this->key,
            $cinema->name,
            $cinema->location
        ];
    }
}
