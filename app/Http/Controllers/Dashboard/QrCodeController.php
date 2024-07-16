<?php

namespace App\Http\Controllers\Dashboard;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.qr-code.index');
    }
}
