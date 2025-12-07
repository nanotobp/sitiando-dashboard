<?php

namespace App\Exports;

use App\Models\AffiliatePayout;
use Maatwebsite\Excel\Concerns\FromCollection;

class PayoutExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AffiliatePayout::all();
    }
}
