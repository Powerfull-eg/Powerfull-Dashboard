<?php

namespace App\Http\Controllers\Website;

class HomeController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        return redirect("/dashboard");
        // return view('website.index');
    }
}
