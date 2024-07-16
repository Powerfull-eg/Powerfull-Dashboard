<?php

namespace App\Http\Controllers\Dashboard;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        return view('dashboard.index');
    }
}
