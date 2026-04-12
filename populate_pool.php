<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pelanggan = \App\Models\Pelanggan::whereNull('ip_address')->get();
echo "Total pelanggan tanpa IP: " . $pelanggan->count() . "\n";

foreach ($pelanggan as $p) {
    $pool = $p->ipPool;
    if ($pool) {
        $ip = $pool->getAvailableIp();
        $p->update(['ip_address' => $ip]);
        \Illuminate\Support\Facades\DB::connection('radius')->table('radreply')
            ->where('username', $p->username)
            ->update(['attribute' => 'Framed-IP-Address', 'value' => $ip]);
        echo "{$p->username} -> {$ip}\n";
    } else {
        echo "{$p->username} -> tidak ada pool!\n";
    }
}
echo "Selesai!\n";
