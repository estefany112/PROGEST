<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();
        return view('admin.dashboard', compact('role'));
    }
}
