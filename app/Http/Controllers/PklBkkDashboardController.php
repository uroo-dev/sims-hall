<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PklBkkDashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin PKL & BKK.
     */
    public function index(): View
    {
        return view('Admin.PklBkk.dashboard');
    }
}
