<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Customer;
use App\Product;
use App\Order;
use App\Payment;
use App\Followup;
use App\Services\RepeatOrderService;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@telur.test'],
            ['name' => 'Admin Utama', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        $staf = User::firstOrCreate(
            ['email' => 'staf@telur.test'],
            ['name' => 'Staf Toko', 'password' => Hash::make('password'), 'role' => 'staf']
        );

        // 2. Buat Produk
        $p1 = Product::create(['nama' => 'Telur Ayam Negeri', 'satuan' => 'kg', 'harga' => 28000]);
        $p2 = Product::create(['nama' => 'Telur Ayam Kampung', 'satuan' => 'butir', 'harga' => 2500]);
        $p3 = Product::create(['nama' => 'Telur Puyuh', 'satuan' => 'peti', 'harga' => 35000]);

        // 3. Buat Pelanggan
        $c1 = Customer::create([
            'nama' => 'Warung Bu Tini',
            'kategori' => 'warung',
            'no_wa' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'status' => 'aktif',
            'tgl_daftar' => now()->subMonths(2)
        ]);
        $c2 = Customer::create([
            'nama' => 'Katering Sehat',
            'kategori' => 'katering',
            'no_wa' => '089876543210',
            'alamat' => 'Jl. Sudirman No. 5',
            'status' => 'aktif',
            'tgl_daftar' => now()->subMonths(1)
        ]);
        $c3 = Customer::create([
            'nama' => 'Reseller Mas Budi',
            'kategori' => 'reseller',
            'no_wa' => '0855555555',
            'alamat' => 'Perumahan Indah Blok C',
            'status' => 'aktif',
            'tgl_daftar' => now()->subWeeks(3)
        ]);

        $service = app(RepeatOrderService::class);

        // ==========================================
        // SKENARIO 1: Warung Bu Tini (Pola Teratur - Aktif)
        // Beli Telur Ayam Negeri setiap 10 hari sekali
        // ==========================================
        $dates1 = [now()->subDays(30), now()->subDays(20), now()->subDays(10)];
        foreach ($dates1 as $date) {
            $order = Order::create([
                'customer_id' => $c1->id,
                'user_id' => $staf->id,
                'tgl_order' => $date,
                'tgl_kirim' => $date->copy()->addDay(),
                'status' => 'selesai',
                'metode_bayar' => 'tunai',
                'total' => 5 * 28000 // Beli 5 kg
            ]);
            $order->items()->create([
                'product_id' => $p1->id,
                'jumlah' => 5,
                'harga_satuan' => 28000,
                'subtotal' => 140000
            ]);
            // Hitung repeat order
            $service->recalculateForOrder($order);
        }

        // ==========================================
        // SKENARIO 2: Katering Sehat (Pola Terlambat / Perlu Follow-up)
        // Beli Telur Ayam Negeri setiap 15 hari sekali, tapi sudah lewat 5 hari dari prediksi
        // ==========================================
        $dates2 = [now()->subDays(40), now()->subDays(25), now()->subDays(10)];
        foreach ($dates2 as $date) {
            $order = Order::create([
                'customer_id' => $c2->id,
                'user_id' => $admin->id,
                'tgl_order' => $date,
                'tgl_kirim' => $date,
                'status' => 'selesai',
                'metode_bayar' => 'tempo', // Tempo untuk uji piutang
                'total' => 10 * 28000 // Beli 10 kg
            ]);
            $order->items()->create([
                'product_id' => $p1->id,
                'jumlah' => 10,
                'harga_satuan' => 28000,
                'subtotal' => 280000
            ]);
            $service->recalculateForOrder($order);
        }

        // Tambahkan Follow-up untuk Katering Sehat
        Followup::create([
            'customer_id' => $c2->id,
            'user_id' => $admin->id,
            'tgl_followup' => now(),
            'jenis' => 'whatsapp',
            'catatan' => 'Ditanya via WA, katanya sedang menunggu pencairan dana.',
            'hasil' => 'tunda'
        ]);

        // ==========================================
        // SKENARIO 3: Reseller Mas Budi (Uji Piutang & Baru 2 Pesanan)
        // ==========================================
        $o3 = Order::create([
            'customer_id' => $c3->id,
            'user_id' => $staf->id,
            'tgl_order' => now()->subDays(5),
            'tgl_kirim' => now()->subDays(5),
            'status' => 'selesai',
            'metode_bayar' => 'tempo',
            'total' => 2 * 35000 // 2 peti puyuh
        ]);
        $o3->items()->create([
            'product_id' => $p3->id,
            'jumlah' => 2,
            'harga_satuan' => 35000,
            'subtotal' => 70000
        ]);
        // Bayar DP 50.000, sisa piutang 20.000
        Payment::create([
            'order_id' => $o3->id,
            'tgl_bayar' => now()->subDays(4),
            'jumlah' => 50000,
            'keterangan' => 'DP Tunai'
        ]);
        $service->recalculateForOrder($o3);

        $o4 = Order::create([
            'customer_id' => $c3->id,
            'user_id' => $staf->id,
            'tgl_order' => now()->subDays(2),
            'tgl_kirim' => now()->subDays(2),
            'status' => 'dikirim',
            'metode_bayar' => 'tempo',
            'total' => 3 * 35000
        ]);
        $o4->items()->create([
            'product_id' => $p3->id,
            'jumlah' => 3,
            'harga_satuan' => 35000,
            'subtotal' => 105000
        ]);
        // Belum dibayar sama sekali (piutang 105.000)
        $service->recalculateForOrder($o4);

        // Update status repeat order (menandai yang terlambat)
        $this->command->info('Data dummy berhasil dibuat!');
    }
}