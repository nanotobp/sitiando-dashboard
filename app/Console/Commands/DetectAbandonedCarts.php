public function handle()
{
    \App\Models\Cart::where('status', 'active')
        ->where('updated_at', '<=', now()->subMinutes(45))
        ->update(['status' => 'abandoned']);

    $this->info('✔ Carritos marcados como abandonados');
}
