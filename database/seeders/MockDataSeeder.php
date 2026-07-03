<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recipe;
use App\Models\CookingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MockDataSeeder extends Seeder
{
    public function run(): void
    {
        // Prevent foreign key check errors during clean up
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Remove existing mock users to avoid pollution
        User::where('email', 'like', 'customer%@cookspace.com')
            ->orWhere('email', 'like', 'cooker%@cookspace.com')
            ->delete();

        // Clear recipes and services linked to mock cookers
        // First get remaining/valid cooker ids to preserve non-mock data if any
        $mockCookerEmails = [];
        for ($i = 1; $i <= 20; $i++) {
            $mockCookerEmails[] = "cooker{$i}@cookspace.com";
        }
        $mockCookers = User::whereIn('email', $mockCookerEmails)->get();
        $mockCookerIds = $mockCookers->pluck('id')->toArray();

        Recipe::whereIn('cooker_id', $mockCookerIds)->delete();
        CookingService::whereIn('cooker_id', $mockCookerIds)->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create 20 Mock Customers
        $customerNames = [
            'Rian Hidayat', 'Aulia Putri', 'Fahmi Idris', 'Santi Wijaya', 'Bagus Prasetyo',
            'Citra Lestari', 'Diki Wahyudi', 'Elisa Fitri', 'Galih Saputra', 'Hana Natalia',
            'Irfan Hakim', 'Julia Perez', 'Kiki Amelia', 'Lukman Hakim', 'Nadia Syah',
            'Oki Setiana', 'Putri Ayu', 'Roni Dozer', 'Silvia Rosa', 'Taufik Hidayat'
        ];

        foreach ($customerNames as $index => $name) {
            $num = $index + 1;
            User::create([
                'name' => $name,
                'email' => "customer{$num}@cookspace.com",
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0812345678' . str_pad($num, 2, '0', STR_PAD_LEFT),
                'bio' => "Pencinta kuliner nusantara ke-{$num}. Suka berburu masakan khas daerah.",
            ]);
        }

        // 2. Create 20 Mock Cookers (Chefs)
        $cookerNames = [
            'Chef Budi Santoso', 'Chef Siti Aminah', 'Chef Agus Wijaya', 'Chef Dewi Lestari', 'Chef Eko Prasetyo',
            'Chef Rina Kartika', 'Chef Bambang Hermawan', 'Chef Sri Wahyuni', 'Chef Andi Siregar', 'Chef Megawati',
            'Chef Joko Widodo', 'Chef Yudi Pratama', 'Chef Nina Amalia', 'Chef Herman Syah', 'Chef Lusi Indah',
            'Chef Dedi Kusnadi', 'Chef Diana Putri', 'Chef Robby Sugara', 'Chef Wati Lestari', 'Chef Ahmad Dhani'
        ];

        $cookers = [];
        foreach ($cookerNames as $index => $name) {
            $num = $index + 1;
            $cooker = User::create([
                'name' => $name,
                'email' => "cooker{$num}@cookspace.com",
                'password' => Hash::make('password'),
                'role' => 'cooker',
                'phone' => '0856789012' . str_pad($num, 2, '0', STR_PAD_LEFT),
                'bio' => "Spesialis masakan rumah cita rasa tradisional. Berpengalaman lebih dari " . ($num + 2) . " tahun di dapur.",
            ]);
            $cookers[] = $cooker;
        }

        // 3. Create 20 Cooking Services (distributed among cookers)
        $serviceTemplates = [
            ['title' => 'Nasi Goreng Kampung Premium', 'price' => 25000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Nasi goreng tradisional dengan telur mata sapi, ayam suwir, kerupuk, dan bumbu rempah pilihan.'],
            ['title' => 'Soto Ayam Lamongan Asli', 'price' => 22000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Soto ayam dengan kuah kuning gurih segar, taburan koya khas Lamongan, soun, dan telur rebus.'],
            ['title' => 'Rendang Daging Minang', 'price' => 45000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Daging sapi pilihan yang dimasak dengan kelapa parut gongseng dan santan selama 4 jam penuh.'],
            ['title' => 'Bakso Sapi Solo Urat', 'price' => 20000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Bakso urat sapi asli Solo dengan mie kuning, bihun, tetelan sapi melimpah, dan kaldu panas segar.'],
            ['title' => 'Sate Ayam Madura Manis', 'price' => 28000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => '10 tusuk sate ayam dada filet empuk dibakar dengan saus kacang kental dan kecap manis legendaris.'],
            ['title' => 'Gado-Gado Betawi Ulek', 'price' => 18000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Sayuran rebus segar disiram bumbu kacang tanah giling dadakan dengan tingkat kepedasan sesuai request.'],
            ['title' => 'Ayam Bakar Taliwang Pedas', 'price' => 35000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Ayam pejantan bakar khas Lombok dengan bumbu pedas meresap gurih beraroma kencur.'],
            ['title' => 'Pempek Palembang Gabus', 'price' => 30000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Paket isi Kapal Selam dan Lenjer terbuat dari ikan gabus pilihan disajikan dengan kuah cuko kental pedas.'],
            ['title' => 'Nasi Kuning Tumpeng Mini', 'price' => 24000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Nasi kuning harum wangi kelapa dengan lauk ayam goreng, tempe orek, telur dadar iris, dan sambal bajak.'],
            ['title' => 'Gudeg Jogja Lengkap', 'price' => 32000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Nangka muda manis gurih, krecek pedas, telur bacem, ayam opor suwir, disajikan dengan nasi hangat.'],
            ['title' => 'Mie Ayam Bakso Wonogiri', 'price' => 17000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Mie kenyal buatan sendiri dengan potongan ayam kecap manis gurih dan 2 buah bakso halus.'],
            ['title' => 'Sop Buntut Sapi Istimewa', 'price' => 65000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Buntut sapi lokal impor empuk dalam kaldu bening gurih bertabur wortel, kentang, dan daun bawang.'],
            ['title' => 'Lontong Sayur Medan', 'price' => 19000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Lontong lembut dengan sayur labu siam gurih santan, tauco udang Medan khas, teri kacang, dan kerupuk merah.'],
            ['title' => 'Ayam Penyet Sambal Korek', 'price' => 22000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Ayam goreng ungkep bumbu kuning yang dipenyet kasar di atas sambal bawang korek super pedas.'],
            ['title' => 'Bebek Goreng Madura Hitam', 'price' => 38000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Bebek empuk bumbu ungkep gurih digoreng kering disiram bumbu hitam pekat khas Madura.'],
            ['title' => 'Laksa Tangerang Gurih', 'price' => 26000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Bihun tebal siram kuah kuning santan kental dengan kacang hijau kupas kuah gurih dan ayam potong bakar.'],
            ['title' => 'Tahu Campur Surabaya', 'price' => 21000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Irisan lontong, tahu goreng, daging sapi kikil kenyal, lentho singkong, disiram kuah petis wangi gurih.'],
            ['title' => 'Bubur Ayam Cianjur Lembut', 'price' => 15000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Bubur beras wangi disiram kuah kuning kaldu, bertabur cakwe, seledri, kedelai goreng, dan kerupuk.'],
            ['title' => 'Rawon Daging Klopo', 'price' => 35000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Sup daging sapi kuah hitam pekat kluwek khas Surabaya dengan taburan kelapa parut sangrai gurih.'],
            ['title' => 'Es Cendol Durian Segar', 'price' => 16000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Cendol kenyal pandan asli dengan air gula merah pekat, santan kelapa murni, dan 2 butir buah durian manis.']
        ];

        foreach ($serviceTemplates as $index => $tpl) {
            // Distribute evenly among 20 cookers
            $cooker = $cookers[$index % count($cookers)];
            
            CookingService::create([
                'cooker_id' => $cooker->id,
                'title' => $tpl['title'],
                'description' => $tpl['desc'],
                'price' => $tpl['price'],
                'is_available' => true,
                'category' => $tpl['category'],
                'is_halal' => $tpl['is_halal'],
                'image_path' => null,
            ]);
        }

        // 4. Create 20 Recipes (distributed among cookers)
        $recipeTemplates = [
            ['title' => 'Resep Rendang Daging Empuk', 'price' => 10000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Panduan rahasia memasak rendang yang meresap sempurna, empuk tanpa presto, dengan racikan kelapa gongseng khas.'],
            ['title' => 'Resep Sambal Bawang Awet', 'price' => 5000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Trik mengolah sambal bawang pedas gurih seperti Bu Rudy Surabaya, tahan disimpan berminggu-minggu.'],
            ['title' => 'Resep Nasi Uduk Betawi', 'price' => 8000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Cara memasak nasi uduk pulen dengan campuran santan kelapa tua, serai geprek, pandan, dan cengkeh wangi.'],
            ['title' => 'Resep Soto Betawi Kuah Susu', 'price' => 12000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Resep kuah soto kental gurih memadukan susu evaporasi dan kaldu sapi asli tanpa rasa enek.'],
            ['title' => 'Resep Kuno Ayam Betutu', 'price' => 15000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Panduan langkah bumbu genep komplit dibungkus pelepah pisang lalu dipanggang dengan bara sabut kelapa.'],
            ['title' => 'Resep Siomay Bandung Tenggiri', 'price' => 7000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Cara membuat siomay kenyal lezat berbahan dasar ikan tenggiri asli dengan saus kacang legit berminyak merah.'],
            ['title' => 'Resep Klepon Ketan Gula Merah', 'price' => 4000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Rahasia membuat klepon dari tepung ketan asli dengan isi gula merah lumer melimpah tanpa takut bocor.'],
            ['title' => 'Resep Kue Lumpur Kentang', 'price' => 6000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Kue tradisional dengan tekstur super lembut seperti puding dan aroma vanilla harum kismis di atasnya.'],
            ['title' => 'Resep Sambal Goreng Ati Kentang', 'price' => 8000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Bumbu rahasia sambal goreng ati sapi dan kentang dadu wangi jeruk purut pelengkap santapan Lebaran.'],
            ['title' => 'Resep Bakwan Jagung Crispy', 'price' => 3000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Rahasia adonan bakwan jagung manis yang renyah tahan lama walaupun sudah dingin seharian.'],
            ['title' => 'Resep Kolak Pisang Labu Legit', 'price' => 5000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Perpaduan pisang kepok merah dan labu kuning legit manis gula aren dan gurih santan murni.'],
            ['title' => 'Resep Serabi Solo Fluffy', 'price' => 9000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Cara membuat serabi solo gulung yang lembut berlubang-lubang dengan siraman areh santan manis gurih.'],
            ['title' => 'Resep Pempek Dos Sederhana', 'price' => 4000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Pempek ekonomis tanpa ikan yang sangat gurih berkat kaldu penyedap alami dan cuko asam manis.'],
            ['title' => 'Resep Opor Ayam Kampung', 'price' => 11000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Resep turun-temurun opor ayam kuah putih kekuningan gurih wangi ketumbar sangrai.'],
            ['title' => 'Resep Tempe Mendoan Asli', 'price' => 3000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Adonan tepung mendoan khas Banyumas yang setengah matang layu dengan harum irisan daun bawang.'],
            ['title' => 'Resep Martabak Terang Bulan', 'price' => 14000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Langkah-langkah membuat terang bulan teflon rumahan yang bersarang sempurna dan sangat empuk.'],
            ['title' => 'Resep Bubur Sumsum Lembut', 'price' => 4000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Bubur tepung beras yang sehalus sutra berpadu saus kinca gula merah wangi pandan murni.'],
            ['title' => 'Resep Ayam Goreng Lengkuas', 'price' => 9000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Ungkepan ayam bumbu kuning dengan kelapa/lengkuas parut melimpah yang digoreng kering kriuk.'],
            ['title' => 'Resep Sayur Lodeh Jawa Tengah', 'price' => 5000, 'category' => 'indonesia', 'is_halal' => true, 'desc' => 'Lodeh santan encer dengan campuran nangka muda, kacang panjang, labu siam, daun melinjo, dan ebi.'],
            ['title' => 'Resep Bolu Kukus Mekar', 'price' => 7000, 'category' => 'dessert', 'is_halal' => true, 'desc' => 'Trik mematangkan bolu kukus merekah cantik bertekstur kapas tanpa perlu air soda.']
        ];

        foreach ($recipeTemplates as $index => $tpl) {
            // Distribute among cookers (shifted index to make it interesting)
            $cooker = $cookers[($index + 3) % count($cookers)];
            
            Recipe::create([
                'cooker_id' => $cooker->id,
                'title' => $tpl['title'],
                'description' => $tpl['desc'],
                'ingredients' => "- Bahan Utama A\n- Bumbu Rempah B\n- Takaran Air C",
                'steps' => "1. Bersihkan bahan.\n2. Haluskan bumbu rempah.\n3. Masak dengan api sedang hingga matang meresap.",
                'price' => $tpl['price'],
                'is_published' => true,
                'category' => $tpl['category'],
                'is_halal' => $tpl['is_halal'],
                'image_path' => null,
            ]);
        }
    }
}
