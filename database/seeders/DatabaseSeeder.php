<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketTier;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────

        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@eventku.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => '081200000001',
        ]);

        $organizer = User::create([
            'name'     => 'Event Organizer Demo',
            'email'    => 'organizer@eventku.com',
            'password' => Hash::make('password123'),
            'role'     => 'organizer',
            'phone'    => '081200000002',
        ]);

        $customer = User::create([
            'name'     => 'John Customer',
            'email'    => 'customer@eventku.com',
            'password' => Hash::make('password123'),
            'role'     => 'customer',
            'phone'    => '081200000003',
        ]);

        // ── Categories ───────────────────────────────────────

        $cats = collect([
            'Musik', 'Teknologi', 'Workshop', 'Seminar', 'Seni & Budaya',
            'Festival', 'Olahraga', 'Bisnis', 'Edukasi',
        ])->map(fn ($name) => Category::create(['name' => $name]));

        $catMap = $cats->keyBy('name');

        // ── Events ───────────────────────────────────────────

        $eventsData = [
            [
                'organizer'   => $organizer,
                'category'    => 'Musik',
                'title'       => 'Konser Musik Jakarta 2026',
                'description' => "Festival musik terbesar di Jakarta menampilkan artis-artis terbaik Indonesia dan mancanegara.\n\nLine Up:\n• Tulus\n• Pamungkas\n• Sal Priadi\n• & banyak lagi!\n\nDapatkan tiketmu sekarang sebelum kehabisan!",
                'venue_name'  => 'Jakarta Convention Center',
                'venue_address'=> 'Jl. Gatot Subroto, Jakarta Selatan',
                'city'        => 'Jakarta',
                'start_at'    => '2026-06-15 19:00:00',
                'end_at'      => '2026-06-15 23:00:00',
                'terms'       => 'Tiket yang sudah dibeli tidak dapat ditukar.\nDilarang membawa makanan dan minuman dari luar.',
                'refund_policy'=> 'Refund tersedia hingga 7 hari sebelum acara.',
                'tiers'       => [
                    ['name' => 'Festival', 'price' => 250000, 'quota' => 500, 'max_per_order' => 5, 'is_refundable' => true],
                    ['name' => 'VIP', 'price' => 750000, 'quota' => 100, 'max_per_order' => 3, 'is_refundable' => true],
                    ['name' => 'VVIP (Front Stage)', 'price' => 1500000, 'quota' => 50, 'max_per_order' => 2, 'is_refundable' => false],
                ],
            ],
            [
                'organizer'   => $organizer,
                'category'    => 'Teknologi',
                'title'       => 'Tech Summit Indonesia 2026',
                'description' => "Konferensi teknologi terbesar di Asia Tenggara.\n\nTopik: AI/ML, Web3, Cloud Computing, Cybersecurity.\nBertemu dengan founder startup unicorn dan tech leaders.",
                'venue_name'  => 'ICE BSD',
                'venue_address'=> 'BSD City, Tangerang Selatan',
                'city'        => 'Tangerang',
                'start_at'    => '2026-08-20 09:00:00',
                'end_at'      => '2026-08-21 17:00:00',
                'tiers'       => [
                    ['name' => 'Early Bird', 'price' => 150000, 'quota' => 200, 'max_per_order' => 5, 'is_refundable' => true],
                    ['name' => 'Regular', 'price' => 300000, 'quota' => 500, 'max_per_order' => 5, 'is_refundable' => true],
                    ['name' => 'Premium + Workshop', 'price' => 500000, 'quota' => 100, 'max_per_order' => 2, 'is_refundable' => false],
                ],
            ],
            [
                'organizer'   => $admin,
                'category'    => 'Workshop',
                'title'       => 'Workshop UI/UX Design Masterclass',
                'description' => "Workshop intensif 2 hari bersama senior designer dari Gojek, Tokopedia, dan Shopee.\n\nMateri:\n• Design Thinking\n• Figma Advanced\n• Prototyping & User Testing\n• Portfolio Review",
                'venue_name'  => 'Coworking Space Kemang',
                'venue_address'=> 'Jl. Kemang Raya No. 45, Jakarta',
                'city'        => 'Jakarta',
                'start_at'    => '2026-07-10 10:00:00',
                'end_at'      => '2026-07-11 17:00:00',
                'tiers'       => [
                    ['name' => 'Tiket Workshop', 'price' => 500000, 'quota' => 30, 'max_per_order' => 2, 'is_refundable' => true],
                ],
            ],
            [
                'organizer'   => $organizer,
                'category'    => 'Seni & Budaya',
                'title'       => 'Bali International Film Festival',
                'description' => "Festival film internasional di Bali menampilkan karya sineas dari 20+ negara.\nScreening, talkshow, dan networking night.",
                'venue_name'  => 'GWK Cultural Park',
                'venue_address'=> 'Jl. Raya Uluwatu, Badung, Bali',
                'city'        => 'Bali',
                'start_at'    => '2026-09-05 14:00:00',
                'end_at'      => '2026-09-07 22:00:00',
                'tiers'       => [
                    ['name' => 'Day Pass', 'price' => 100000, 'quota' => 1000, 'max_per_order' => 10, 'is_refundable' => true],
                    ['name' => 'Full Pass (3 hari)', 'price' => 250000, 'quota' => 300, 'max_per_order' => 5, 'is_refundable' => true],
                ],
            ],
            [
                'organizer'   => $admin,
                'category'    => 'Seminar',
                'title'       => 'Seminar Kewirausahaan Digital',
                'description' => "Belajar dari pengusaha sukses Indonesia tentang membangun bisnis digital yang sustainable.",
                'venue_name'  => 'Universitas Indonesia',
                'venue_address'=> 'Depok, Jawa Barat',
                'city'        => 'Depok',
                'is_online'   => false,
                'start_at'    => '2026-05-20 08:30:00',
                'end_at'      => '2026-05-20 16:00:00',
                'tiers'       => [
                    ['name' => 'Gratis (Mahasiswa)', 'price' => 0, 'quota' => 200, 'max_per_order' => 1, 'is_refundable' => false],
                    ['name' => 'Umum', 'price' => 50000, 'quota' => 100, 'max_per_order' => 3, 'is_refundable' => false],
                ],
            ],
            [
                'organizer'   => $organizer,
                'category'    => 'Festival',
                'title'       => 'Jakarta Food & Music Festival',
                'description' => "Gabungan kuliner dan musik terbaik Jakarta!\n50+ tenant food dan live music sepanjang malam.",
                'venue_name'  => 'Senayan Park',
                'venue_address'=> 'Jl. Asia Afrika, Jakarta',
                'city'        => 'Jakarta',
                'start_at'    => '2026-10-12 16:00:00',
                'end_at'      => '2026-10-12 23:59:00',
                'tiers'       => [
                    ['name' => 'Regular', 'price' => 75000, 'quota' => 2000, 'max_per_order' => 10, 'is_refundable' => true],
                    ['name' => 'VIP (Meja Khusus)', 'price' => 350000, 'quota' => 50, 'max_per_order' => 2, 'is_refundable' => false],
                ],
            ],
        ];

        foreach ($eventsData as $data) {
            $tiers = $data['tiers'];
            unset($data['tiers']);

            $event = Event::create([
                'organizer_id'   => $data['organizer']->id,
                'category_id'    => $catMap[$data['category']]->id,
                'title'          => $data['title'],
                'description'    => $data['description'],
                'venue_name'     => $data['venue_name'] ?? null,
                'venue_address'  => $data['venue_address'] ?? null,
                'city'           => $data['city'] ?? null,
                'is_online'      => $data['is_online'] ?? false,
                'start_at'       => $data['start_at'],
                'end_at'         => $data['end_at'],
                'status'         => 'published',
                'terms'          => $data['terms'] ?? null,
                'refund_policy'  => $data['refund_policy'] ?? null,
            ]);

            foreach ($tiers as $tier) {
                TicketTier::create([
                    'event_id'      => $event->id,
                    'name'          => $tier['name'],
                    'price'         => $tier['price'],
                    'quota'         => $tier['quota'],
                    'sold_count'    => 0,
                    'max_per_order' => $tier['max_per_order'],
                    'is_refundable' => $tier['is_refundable'],
                ]);
            }
        }

        // ── Promo Code ───────────────────────────────────────

        PromoCode::create([
            'code'        => 'WELCOME50',
            'type'        => 'percent',
            'value'       => 50,
            'start_at'    => '2026-01-01',
            'end_at'      => '2026-12-31',
            'usage_limit' => 100,
        ]);

        PromoCode::create([
            'code'        => 'DISKON25K',
            'type'        => 'fixed',
            'value'       => 25000,
            'start_at'    => '2026-01-01',
            'end_at'      => '2026-12-31',
            'usage_limit' => 50,
        ]);
    }
}
