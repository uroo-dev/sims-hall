<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'judul' => 'Sound System Standar',
                'deskripsi' => 'Sound system standar 2 speaker aktif untuk acara rapat atau seminar kecil.',
            ],
            [
                'judul' => 'Sound System Medium',
                'deskripsi' => 'Sound system profesional dengan subwoofer dan mixer audio untuk resepsi atau konser.',
            ],
            [
                'judul' => 'Mic 2',
                'deskripsi' => '2 buah wireless microphone UHF dengan penerima sinyal stabil.',
            ],
            [
                'judul' => 'Mic 4',
                'deskripsi' => '4 buah wireless microphone UHF + clip-on mic untuk narasumber dan MC.',
            ],
            [
                'judul' => '100 Kursi',
                'deskripsi' => '100 unit kursi susun besi ergonomis.',
            ],
            [
                'judul' => '500 Kursi + Cover',
                'deskripsi' => '500 unit kursi susun berbalut cover kain putih rapi dan pita elegan.',
            ],
            [
                'judul' => 'Proyektor 1',
                'deskripsi' => '1 unit proyektor 3500 ANSI Lumens beserta screen manual 70 inch.',
            ],
            [
                'judul' => 'Proyektor 2',
                'deskripsi' => '2 unit proyektor laser high resolution beserta motorized wide screen kanan-kiri.',
            ],
            [
                'judul' => 'Wifi 1080mbps',
                'deskripsi' => 'Akses internet dedicated fiber optik kecepatan tinggi untuk streaming & live report.',
            ],
            [
                'judul' => 'Podium',
                'deskripsi' => 'Podium kayu jati eksklusif sekolah dengan mikrofon gooseneck terpasang.',
            ],
            [
                'judul' => 'Parkir Luas (Gratis)',
                'deskripsi' => 'Area parkir kendaraan beraspal dan tertata rapi muat hingga 50 mobil & 200 motor.',
            ],
            [
                'judul' => 'Staff Keamanan',
                'deskripsi' => 'Petugas keamanan (satpam) dan tim kebersihan standby penuh selama durasi acara.',
            ],
            [
                'judul' => 'AC Central Aula',
                'deskripsi' => 'Pendingin ruangan AC central dan standing floor untuk kenyamanan seluruh tamu undangan.',
            ],
            [
                'judul' => 'Panggung Utama & Lighting',
                'deskripsi' => 'Panggung permanen aula beserta spotlight lighting panggung untuk pertunjukan.',
            ],
        ];

        foreach ($facilities as $data) {
            Facility::firstOrCreate(
                ['judul' => $data['judul']],
                ['deskripsi' => $data['deskripsi']]
            );
        }
    }
}
