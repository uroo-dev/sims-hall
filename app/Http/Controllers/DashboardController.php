<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role user.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()?->role === 'pelanggan') {
            return redirect()->route('customer.dashboard');
        }

        return view('Admin.dashboard');
    }
}
