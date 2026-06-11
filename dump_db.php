<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AgentCategory;
use App\Models\User;
use App\Models\UserWallet;

$data = [
    'categories' => AgentCategory::all(['name', 'unlock_balance'])->toArray(),
    'user_id' => auth('member')->id(), // This will be null in CLI
    'auth_user' => (auth('member')->check()) ? auth('member')->user()->username : 'not logged in',
];

file_put_contents('db_dump.json', json_encode($data, JSON_PRETTY_PRINT));
print "Done\n";
