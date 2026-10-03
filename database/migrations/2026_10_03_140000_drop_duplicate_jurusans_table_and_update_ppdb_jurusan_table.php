<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Lepas foreign key constraint dari tabel produk_unggulans jika mengarah ke tabel jurusans
        if (Schema::hasTable('produk_unggulans')) {
            if (DB::getDriverName() !== 'sqlite') {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAM
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'produk_unggulans'
                      AND CONSTRAINT_NAME = 'produk_unggulans_jurusan_id_foreign'
                ");
                if (! empty($foreignKeys)) {
                    Schema::table('produk_unggulans', function (Blueprint $table) {
                        $table->dropForeign('produk_unggulans_jurusan_id_foreign');
                    });
                }
            }
        }

        // 2. Hapus tabel duplikat 'jurusans' (simpan tabel 'jurusan')
        Schema::dropIfExists('jurusans');

        // Bersihkan riwayat migrasi create_jurusans_table jika ada
        DB::table('migrations')->where('migration', '2026_09_27_112509_create_jurusans_table')->delete();

        // 3. Pada tabel ppdb_jurusan: hapus kolom nama_jurusan dan gunakan relasi jurusan_id ke tabel 'jurusan' (jurusanID)
        if (Schema::hasTable('ppdb_jurusan')) {
            if (! Schema::hasColumn('ppdb_jurusan', 'jurusan_id')) {
                Schema::table('ppdb_jurusan', function (Blueprint $table) {
                    $table->unsignedBigInteger('jurusan_id')->nullable()->after('id');
                });
            }

            // Migrasikan data yang sudah ada dari nama_jurusan ke jurusan_id
            if (Schema::hasColumn('ppdb_jurusan', 'nama_jurusan')) {
                $ppdbJurusans = DB::table('ppdb_jurusan')->get();
                foreach ($ppdbJurusans as $row) {
                    if (! empty($row->nama_jurusan)) {
                        $jurusan = DB::table('jurusan')->where('nama', $row->nama_jurusan)->first();
                        if (! $jurusan) {
                            $jurusanId = DB::table('jurusan')->insertGetId([
                                'nama' => $row->nama_jurusan,
                                'deskripsi' => 'Kompetensi keahlian '.$row->nama_jurusan.' di SMK Negeri 2 Karanganyar.',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            $jurusanId = $jurusan->jurusanID;
                        }
                        DB::table('ppdb_jurusan')->where('id', $row->id)->update(['jurusan_id' => $jurusanId]);
                    }
                }

                // Hapus kolom nama_jurusan
                Schema::table('ppdb_jurusan', function (Blueprint $table) {
                    $table->dropColumn('nama_jurusan');
                });
            }

            // Tambahkan foreign key constraint ke tabel 'jurusan' (jurusanID)
            Schema::table('ppdb_jurusan', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->unsignedBigInteger('jurusan_id')->nullable(false)->change();
                }
                $table->foreign('jurusan_id')->references('jurusanID')->on('jurusan')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ppdb_jurusan')) {
            Schema::table('ppdb_jurusan', function (Blueprint $table) {
                $table->dropForeign(['jurusan_id']);
                $table->string('nama_jurusan', 100)->nullable()->after('id');
            });

            $ppdbJurusans = DB::table('ppdb_jurusan')
                ->join('jurusan', 'ppdb_jurusan.jurusan_id', '=', 'jurusan.jurusanID')
                ->select('ppdb_jurusan.id', 'jurusan.nama')
                ->get();

            foreach ($ppdbJurusans as $row) {
                DB::table('ppdb_jurusan')->where('id', $row->id)->update(['nama_jurusan' => $row->nama]);
            }

            Schema::table('ppdb_jurusan', function (Blueprint $table) {
                $table->dropColumn('jurusan_id');
            });
        }

        if (! Schema::hasTable('jurusans')) {
            Schema::create('jurusans', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 250);
                $table->text('deskripsi')->nullable();
                $table->string('dokumentasi')->nullable();
                $table->string('logo')->nullable();
                $table->timestamps();
            });
        }
    }
};
