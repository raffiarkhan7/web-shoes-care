<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        $penjual = User::updateOrCreate(
            ['email' => 'penjual@shaoscare.id'],
            [
                'name' => 'Dimas Anggara',
                'password' => 'password',
                'role' => User::ROLE_PENJUAL,
                'shop_name' => 'Shaos Clean Express',
                'phone' => '081200000002',
                'address' => 'Jl. Sudirman No. 88, Jakarta',
                'is_active' => true,
            ],
        );

        $penjual2 = User::updateOrCreate(
            ['email' => 'penjual2@shaoscare.id'],
            [
                'name' => 'Nadia Putri',
                'password' => 'password',
                'role' => User::ROLE_PENJUAL,
                'shop_name' => 'Sneaker Spa Studio',
                'phone' => '081200000003',
                'address' => 'Jl. Kemang Raya No. 12, Jakarta',
                'is_active' => true,
            ],
        );

        $pembeli = User::updateOrCreate(
            ['email' => 'pembeli@shaoscare.id'],
            [
                'name' => 'Raka Pratama',
                'password' => 'password',
                'role' => User::ROLE_PEMBELI,
                'phone' => '081200000004',
                'address' => 'Jl. Melati No. 21, Kel. Menteng, Jakarta Pusat',
                'is_active' => true,
            ],
        );

        $pembeli2 = User::updateOrCreate(
            ['email' => 'pembeli2@shaoscare.id'],
            [
                'name' => 'Sinta Dewi',
                'password' => 'password',
                'role' => User::ROLE_PEMBELI,
                'phone' => '081200000005',
                'address' => 'Jl. Anggrek No. 7, Kel. Kebayoran, Jakarta Selatan',
                'is_active' => true,
            ],
        );

        $services = [
            [
                'user_id' => $penjual->id,
                'name' => 'Deep Clean Premium',
                'category' => 'Deep Clean',
                'description' => 'Pembersihan menyeluruh hingga ke bagian dalam sepatu, menghilangkan noda membandel dan bau tak sedap.',
                'price' => 35000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual->id,
                'name' => 'Quick Clean',
                'category' => 'Express',
                'description' => 'Cuci cepat bagian upper dan sol untuk sepatu yang butuh bersih dalam waktu singkat.',
                'price' => 20000,
                'estimated_days' => 1,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual->id,
                'name' => 'Unyellowing White Sole',
                'category' => 'Restorasi',
                'description' => 'Mengembalikan warna putih sol sepatu yang menguning dengan treatment khusus dan aman.',
                'price' => 55000,
                'estimated_days' => 4,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual->id,
                'name' => 'Repaint & Restore',
                'category' => 'Restorasi',
                'description' => 'Cat ulang warna sepatu dan perbaikan minor agar tampil seperti baru kembali.',
                'price' => 150000,
                'estimated_days' => 7,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual2->id,
                'name' => 'Cuci + Semir',
                'category' => 'Perawatan',
                'description' => 'Paket cuci dan semir sepatu kulit maupun kanvas agar kilap dan bersih maksimal.',
                'price' => 45000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual2->id,
                'name' => 'Water Repellent',
                'category' => 'Proteksi',
                'description' => 'Lapisan pelindung anti air dan noda untuk menjaga sepatu tetap bersih lebih lama.',
                'price' => 40000,
                'estimated_days' => 2,
                'is_active' => true,
            ],
            [
                'user_id' => $penjual2->id,
                'name' => 'Deep Clean + Deodorizing',
                'category' => 'Deep Clean',
                'description' => 'Deep clean lengkap dengan treatment penghilang bau dan bakteri di bagian dalam sepatu.',
                'price' => 50000,
                'estimated_days' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['user_id' => $data['user_id'], 'name' => $data['name']],
                $data,
            );
        }

        $deepClean = Service::where('user_id', $penjual->id)->where('name', 'Deep Clean Premium')->first();
        $quickClean = Service::where('user_id', $penjual->id)->where('name', 'Quick Clean')->first();
        $unyell = Service::where('user_id', $penjual->id)->where('name', 'Unyellowing White Sole')->first();
        $cuciSemir = Service::where('user_id', $penjual2->id)->where('name', 'Cuci + Semir')->first();
        $repellent = Service::where('user_id', $penjual2->id)->where('name', 'Water Repellent')->first();

        $orders = [
            [
                'invoice_number' => 'SC-20261028-A1B2C',
                'user_id' => $pembeli->id,
                'penjual_id' => $penjual->id,
                'service_id' => $deepClean->id,
                'quantity' => 2,
                'total_price' => $deepClean->price * 2,
                'pickup_address' => $pembeli->address,
                'pickup_date' => now()->subDays(2)->toDateString(),
                'pickup_time' => '10:00 - 12:00',
                'pickup_method' => 'pickup',
                'notes' => 'Ada noda lumpur di bagian depan, mohon dibersihkan ekstra.',
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'status' => Order::STATUS_PROCESSING,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(5),
            ],
            [
                'invoice_number' => 'SC-20261029-D3E4F',
                'user_id' => $pembeli->id,
                'penjual_id' => $penjual2->id,
                'service_id' => $cuciSemir->id,
                'quantity' => 1,
                'total_price' => $cuciSemir->price,
                'pickup_address' => $pembeli->address,
                'pickup_date' => now()->addDay()->toDateString(),
                'pickup_time' => '13:00 - 15:00',
                'pickup_method' => 'pickup',
                'notes' => null,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'status' => Order::STATUS_CONFIRMED,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(3),
            ],
            [
                'invoice_number' => 'SC-20261029-G5H6I',
                'user_id' => $pembeli2->id,
                'penjual_id' => $penjual->id,
                'service_id' => $unyell->id,
                'quantity' => 1,
                'total_price' => $unyell->price,
                'pickup_address' => $pembeli2->address,
                'pickup_date' => now()->addDays(2)->toDateString(),
                'pickup_time' => '08:00 - 10:00',
                'pickup_method' => 'dropoff',
                'notes' => 'Sol putih menguning cukup parah.',
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'status' => Order::STATUS_PENDING,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'invoice_number' => 'SC-20261027-J7K8L',
                'user_id' => $pembeli2->id,
                'penjual_id' => $penjual2->id,
                'service_id' => $repellent->id,
                'quantity' => 3,
                'total_price' => $repellent->price * 3,
                'pickup_address' => $pembeli2->address,
                'pickup_date' => now()->subDays(4)->toDateString(),
                'pickup_time' => '15:00 - 17:00',
                'pickup_method' => 'pickup',
                'notes' => null,
                'payment_method' => 'transfer',
                'payment_status' => 'paid',
                'status' => Order::STATUS_COMPLETED,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(1),
            ],
            [
                'invoice_number' => 'SC-20261026-M9N0P',
                'user_id' => $pembeli->id,
                'penjual_id' => $penjual->id,
                'service_id' => $quickClean->id,
                'quantity' => 2,
                'total_price' => $quickClean->price * 2,
                'pickup_address' => $pembeli->address,
                'pickup_date' => now()->subDays(6)->toDateString(),
                'pickup_time' => '10:00 - 12:00',
                'pickup_method' => 'pickup',
                'notes' => null,
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'status' => Order::STATUS_COMPLETED,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(5),
            ],
        ];

        foreach ($orders as $order) {
            Order::updateOrCreate(['invoice_number' => $order['invoice_number']], $order);
        }
    }
}
