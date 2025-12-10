<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil id kategori berdasarkan nama (pastikan CategorySeeder sudah dijalankan)
        $catIds = Category::pluck('id', 'name'); // ['Makanan'=>1, 'Paket'=>2, ...]

        $menus = [
    // Makanan
    [
        'category' => 'Makanan',
        'name'     => 'Bebek Goreng / Bakar Cobek',
        'price'    => 35000,
        'desc'     => 'Bebek goreng atau bakar dengan bumbu cobek khas, sambal terasi, dan lalapan.',
        'photo' => 'images/menus/BebekGorengBakarCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Ayam Goreng / Bakar Cobek',
        'price'    => 20000,
        'desc'     => 'Ayam goreng atau bakar dengan bumbu cobek pedas, sambal, dan lalapan.',
        'photo' => 'images/menus/AyamGorengBakarCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Lele Goreng / Bakar Cobek',
        'price'    => 15000,
        'desc'     => 'Ikan lele goreng atau bakar dengan sambal cobek dan lalapan segar.',
        'photo' => 'images/menus/LeleGorengBakarCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Nila Goreng / Bakar Cobek',
        'price'    => 20000,
        'desc'     => 'Ikan nila goreng atau bakar disajikan dengan sambal cobek dan lalapan.',
        'photo' => 'images/menus/NilaGorengBakarCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Ceker Goreng Cobek',
        'price'    => 10000,
        'desc'     => 'Ceker ayam goreng krispi dengan sambal cobek pedas.',
        'photo' => 'images/menus/CekerGorengCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Gurame Goreng / Bakar Cobek',
        'price'    => 55000,
        'desc'     => 'Ikan gurame utuh goreng atau bakar dengan sambal cobek dan lalapan.',
        'photo' => 'images/menus/GurameGorengBakarCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Udang Cobek',
        'price'    => 45000,
        'desc'     => 'Udang dimasak dengan bumbu sambal cobek pedas gurih.',
        'photo' => 'images/menus/UdangCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Cumi Cobek Bakar',
        'price'    => 50000,
        'desc'     => 'Cumi bakar dengan bumbu cobek, tekstur empuk dan kaya rasa.',
        'photo' => 'images/menus/CumiCobekBakar.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Lidah Sapi',
        'price'    => 60000,
        'desc'     => 'Lidah sapi empuk dengan bumbu khas, cocok disantap dengan nasi hangat.',
        'photo' => 'images/menus/LidahSapi.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Sayap Goreng Cobek',
        'price'    => 12000,
        'desc'     => 'Sayap ayam goreng krispi dengan sambal cobek pedas.',
        'photo' => 'images/menus/SayapGorengCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Ati Ampela Cobek',
        'price'    => 10000,
        'desc'     => 'Ati ampela ayam dimasak dengan bumbu sambal cobek.',
        'photo' => 'images/menus/AtiAmpelaCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Tahu Cobek (Porsi)',
        'price'    => 15000,
        'desc'     => 'Tahu goreng dengan sambal cobek dan bumbu khas.',
        'photo' => 'images/menus/TahuCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Tempe Cobek (Porsi)',
        'price'    => 15000,
        'desc'     => 'Tempe goreng disajikan dengan sambal cobek pedas gurih.',
        'photo' => 'images/menus/TempeCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Kulit Ayam Cobek',
        'price'    => 10000,
        'desc'     => 'Kulit ayam goreng krispi dengan sambal cobek.',
        'photo' => 'images/menus/KulitAyamCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Bakso Goreng Cobek',
        'price'    => 15000,
        'desc'     => 'Bakso goreng renyah dengan sambal cobek.',
        'photo' => 'images/menus/BaksoGorengCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Paru Goreng Cobek',
        'price'    => 15000,
        'desc'     => 'Paru sapi goreng garing dengan sambal cobek pedas.',
        'photo' => 'images/menus/ParuGorengCobek.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Telor Mata Sapi',
        'price'    => 6000,
        'desc'     => 'Telur mata sapi digoreng matang dengan sedikit bumbu.',
        'photo' => 'images/menus/TelorMataSapi.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Ikan Seluang',
        'price'    => 35000,
        'desc'     => 'Ikan seluang goreng garing dengan bumbu gurih dan sambal.',
        'photo' => 'images/menus/IkanSeluang.jpg',
    ],
    [
        'category' => 'Makanan',
        'name'     => 'Ayam Goreng / Bakar 1 Ekor',
        'price'    => 70000,
        'desc'     => 'Satu ekor ayam goreng atau bakar untuk porsi keluarga.',
        'photo' => 'images/menus/AyamGorengBakarSatuEkor.jpg',
    ],

    // Paket
    [
        'category' => 'Paket',
        'name'     => 'Ayam Bakar / Goreng + Nasi + Tahu + Tempe + Es Tehtawar',
        'price'    => 27000,
        'desc'     => 'Paket ayam bakar atau goreng dengan nasi, tahu, tempe, dan es teh tawar.',
        'photo' => 'images/menus/PaketAyamBakarGoreng.jpg',
    ],
    [
        'category' => 'Paket',
        'name'     => 'Bebek Bakar / Goreng + Nasi + Tahu + Tempe + Es Tehtawar',
        'price'    => 45000,
        'desc'     => 'Paket bebek bakar atau goreng dengan nasi, tahu, tempe, dan es teh tawar.',
        'photo' => 'images/menus/PaketBebekBakarGoreng.jpg',
    ],
    [
        'category' => 'Paket',
        'name'     => 'Lele Bakar / Goreng + Nasi + Tahu + Tempe + Es Tehtawar',
        'price'    => 20000,
        'desc'     => 'Paket lele bakar atau goreng dengan nasi, tahu, tempe, dan es teh tawar.',
        'photo' => 'images/menus/PaketLeleBakarGoreng.jpg',
    ],

    // Tambahan
    [
        'category' => 'Tambahan',
        'name'     => 'Jamur Enoki',
        'price'    => 25000,
        'desc'     => 'Jamur enoki segar dimasak dengan bumbu gurih.',
        'photo' => 'images/menus/JamurEnoki.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Terong Goreng',
        'price'    => 10000,
        'desc'     => 'Terong goreng dengan tekstur lembut dan bumbu gurih.',
        'photo' => 'images/menus/TerongGoreng.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Peteg',
        'price'    => 10000,
        'desc'     => 'Petai dimasak sederhana sebagai pelengkap makan.',
        'photo' => 'images/menus/Peteg.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Kol Cobek',
        'price'    => 12000,
        'desc'     => 'Kol goreng garing dengan bumbu sederhana.',
        'photo' => 'images/menus/KolCobek.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Cah Kangkung',
        'price'    => 15000,
        'desc'     => 'Tumis kangkung dengan bumbu bawang dan cabai.',
        'photo' => 'images/menus/CahKangkung.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Nasi Putih',
        'price'    => 7000,
        'desc'     => 'Nasi putih hangat sebagai pendamping lauk.',
        'photo' => 'images/menus/NasiPutih.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Nasi Uduk',
        'price'    => 10000,
        'desc'     => 'Nasi uduk gurih dengan aroma santan.',
        'photo' => 'images/menus/NasiUduk.jpg',
    ],
    [
        'category' => 'Tambahan',
        'name'     => 'Nasi Merah',
        'price'    => 12000,
        'desc'     => 'Nasi merah sehat sebagai alternatif nasi putih.',
        'photo' => 'images/menus/NasiMerah.jpg',
    ],

    // Minuman
    [
        'category' => 'Minuman',
        'name'     => 'Es / Hot Teh Manis',
        'price'    => 8000,
        'desc'     => 'Teh manis segar bisa disajikan panas atau dingin.',
        'photo' => 'images/menus/EsHotTehManis.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Es Teh Tawar',
        'price'    => 6000,
        'desc'     => 'Es teh tawar segar tanpa gula.',
        'photo' => 'images/menus/EsTehTawar.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Es / Hot Lemon Tea',
        'price'    => 13000,
        'desc'     => 'Teh lemon segar panas atau dingin dengan rasa asam manis.',
        'photo' => 'images/menus/EsHotLemonTea.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Es / Hot Jeruk',
        'price'    => 15000,
        'desc'     => 'Minuman jeruk segar bisa panas atau dingin.',
        'photo' => 'images/menus/EsHotJeruk.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Air Putih Es',
        'price'    => 3000,
        'desc'     => 'Air mineral dingin menyegarkan.',
        'photo' => 'images/menus/AirPutihEs.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Es / Hot Susu',
        'price'    => 15000,
        'desc'     => 'Susu hangat atau dingin dengan rasa lembut.',
        'photo' => 'images/menus/EsHotSusu.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Kopi Hitam',
        'price'    => 10000,
        'desc'     => 'Kopi hitam pekat untuk penikmat rasa kuat.',
        'photo' => 'images/menus/KopiHitam.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Dogan Susu',
        'price'    => 25000,
        'desc'     => 'Minuman dogan dengan campuran susu manis.',
        'photo' => 'images/menus/DoganSusu.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Dogan',
        'price'    => 16000,
        'desc'     => 'Varian minuman dogan segar.',
        'photo' => 'images/menus/Dogan.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Wedang Uwuh',
        'price'    => 15000,
        'desc'     => 'Wedang uwuh hangat dengan rempah tradisional.',
        'photo' => 'images/menus/WedangUwuh.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Teh Tarik',
        'price'    => 10000,
        'desc'     => 'Teh tarik susu dengan busa lembut.',
        'photo' => 'images/menus/TehTarik.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Jus Tomat',
        'price'    => 15000,
        'desc'     => 'Jus tomat segar kaya vitamin.',
        'photo' => 'images/menus/JusTomat.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Jus Pinang Muda',
        'price'    => 25000,
        'desc'     => 'Jus pinang muda dengan rasa unik dan segar.',
        'photo' => 'images/menus/JusPinangMuda.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Jus Bayam',
        'price'    => 25000,
        'desc'     => 'Jus bayam sehat untuk menambah energi.',
        'photo' => 'images/menus/JusBayam.jpg',
    ],
    [
        'category' => 'Minuman',
        'name'     => 'Jus Alpukat',
        'price'    => 25000,
        'desc'     => 'Jus alpukat kental dan lembut, bisa ditambah cokelat atau susu.',
        'photo' => 'images/menus/JusAlpukat.jpg',
    ],
        ];

        foreach ($menus as $m) {
            $categoryId = $catIds[$m['category']] ?? null;
            if (!$categoryId) {
                // Lewati jika kategori belum ada (harusnya ada dari CategorySeeder)
                continue;
            }

            Menu::UpdateOrCreate(
                ['category_id' => $categoryId, 'name' => $m['name']],
                [
                    'description' => $m['desc'],
                    'price'       => $m['price'],
                    'photo_path'   => $m['photo'],
                    'is_active'   => true,
                ]
            );
        }
    }
}

