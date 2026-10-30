<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\SaasPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $venues = Tenant::with('courts')->where('status', 'active')->get();
        $packages = SaasPackage::all();

        return view('landing', compact('venues', 'packages'));
    }
}
