<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;

class DetectAbandonedCarts extends Command
{
    protected $signature = 'carts:detect-abandoned';
    protected $description = 'Marca carritos inactivos como abandonados';

    public function handle()
    {
        Cart::where('status', 'active')
            ->where('updated_at', '<=', now()->subMinutes(45))
            ->update(['status' => 'abandoned']);

        $this->info('✔ Carritos marcados como abandonados');
        return Command::SUCCESS;
    }
}
