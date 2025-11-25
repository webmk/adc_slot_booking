<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $allCentres = \App\Models\AdcCentre::count();
        $allDates = \App\Models\AdcDate::count();
        $bookings = \App\Models\Booking::count();
        return view('admin.dashboard', compact('allCentres', 'allDates', 'bookings'));
    }
}
