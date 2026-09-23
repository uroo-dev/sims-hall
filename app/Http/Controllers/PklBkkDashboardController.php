<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PklBkkDashboardController extends Controller
{
    /**
     * Dashboard admin PKL & BKK (ringkasan statistik).
     */
    public function index(): View
    {
        return view('Admin.PklBkk.dashboard');
    }

    /**
     * Halaman Lowongan Kerja (daftar loker).
     */
    public function loker(): View
    {
        return view('Admin.bkk.index');
    }

    /**
     * Form tambah / edit Lowongan Kerja (?id=LKR-01 untuk edit).
     */
    public function lokerForm(Request $request): View
    {
        return view('Admin.bkk.update', [
            'lokerId' => $request->query('id'),
        ]);
    }

    /**
     * Halaman Data Pelamar & Tracer Study.
     */
    public function pelamar(): View
    {
        return view('Admin.bkk.pelamar');
    }

    /**
     * Halaman Tempat PKL (DUDI mitra).
     */
    public function tempat(): View
    {
        return view('Admin.PklBkk.temppkl');
    }

    /**
     * Halaman Rekap Jurnal & Absensi Siswa PKL.
     */
    public function jurnal(): View
    {
        return view('Admin.PklBkk.jurnal');
    }

    /**
     * Halaman Rekap Nilai Akhir PKL.
     */
    public function nilai(): View
    {
        return view('Admin.PklBkk.nilai');
    }
}
