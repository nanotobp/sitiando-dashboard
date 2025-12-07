<?php

namespace App\Exports;

use App\Models\AffiliateCommission;
use Maatwebsite\Excel\Concerns\FromCollection;

class CommissionsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AffiliateCommission::all();
    }
}
