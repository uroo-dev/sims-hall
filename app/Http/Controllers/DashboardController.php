<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfiguration;
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

        $isSuperAdmin = in_array($request->user()?->role, ['super_admin', 'super_duper_admin'], true);
        $paymentConfig = $isSuperAdmin ? PaymentConfiguration::current() : null;

        return view('Admin.dashboard', compact('paymentConfig', 'isSuperAdmin'));
    }
}
