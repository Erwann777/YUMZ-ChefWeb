<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$chef = App\Models\User::where('email', 'chef1@cookspace.test')->first();
if ($chef) {
    echo "Chef password hash: " . $chef->password . "\n";
    try {
        $check = Illuminate\Support\Facades\Hash::check('password', $chef->password);
        echo "Check result: " . ($check ? "TRUE" : "FALSE") . "\n";
    } catch (\Exception $e) {
        echo "Check error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Chef not found!\n";
}
