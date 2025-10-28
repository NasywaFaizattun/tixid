<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScheduleExport implements FromCollection, WithMapping, WithHeadings
{

    private $key = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Schedule::all();
    }

    public function headings(): array
    {
        return ['No', 'Nama Bioskop', 'Judul Film', 'Jam Tayang', 'Harga'];
    }

    public function map($schedule): array
    {
        return [
            ++$this->key,
            $schedule['cinema']['name'],
            $schedule['movie']['title'],
            implode(',', $schedule->hours),
            "Rp " . number_format($schedule->price, 0, ',', '.')
        ];
    }
}
