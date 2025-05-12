<?php

namespace App\Http\Controllers;

use App\Models\Alert;

class TopbarController extends Controller
{
    public function getNavbarAlerts()
    {
        $recentAlerts = Alert::latest()->limit(4)->get();
        return view('admin.topbar', compact('recentAlerts'));
    }
}
