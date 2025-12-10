<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // identitas order
            $table->string('order_code')->unique();     // kode acak untuk tracking (mis. 8-12 char)
            $table->string('table_number');             // nomor meja dari QR (tanpa tabel meja)

            // data guest (diisi di Payment Screen)
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_note')->nullable();
            // ringkasan biaya
            $table->decimal('subtotal', 12, 0)->default(0);
            $table->decimal('other_fees', 12, 0)->default(0);
            $table->decimal('total', 12, 0)->default(0);

            // status pembayaran & waktu
            $table->enum('status', ['unpaid','paid','expired','cancelled'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();

            // batas waktu bayar; set saat membuat order = now()+10 menit
            $table->timestamp('expires_at')->nullable();

            // informasi QR/payment sederhana (opsional)
            $table->string('payment_ref')->nullable();    // ref dari gateway
            $table->string('qr_string')->nullable();      // payload untuk digenerate QR
            $table->string('qr_image_url')->nullable();   // jika disimpan sebagai file

            $table->timestamps();

            $table->index(['status','expires_at']);
            $table->index('created_at');
            $table->index('table_number');
        });

        // --- OPTIONAL: Auto-cancel/expire untuk MySQL via EVENT scheduler ---
        // Akan menandai order 'unpaid' menjadi 'expired' jika melewati expires_at.
        // Pastikan MySQL event scheduler aktif: SET GLOBAL event_scheduler = ON;
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::unprepared("
                    CREATE EVENT IF NOT EXISTS auto_expire_orders
                    ON SCHEDULE EVERY 1 MINUTE
                    DO
                        UPDATE orders
                        SET status = 'expired'
                        WHERE status = 'unpaid'
                          AND expires_at IS NOT NULL
                          AND expires_at < NOW();
                ");
            }
        } catch (\Throwable $e) {
            // Jika bukan MySQL atau tidak punya izin EVENT, cukup diabaikan.
        }
    }

    public function down(): void
    {
        // drop event bila MySQL
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::unprepared("DROP EVENT IF EXISTS auto_expire_orders;");
            }
        } catch (\Throwable $e) {}
        Schema::dropIfExists('orders');
    }
};
