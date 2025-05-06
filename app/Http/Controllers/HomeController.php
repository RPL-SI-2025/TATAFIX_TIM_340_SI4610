<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 3 layanan terbaru untuk ditampilkan di homepage
        $services = Service::with('category')
                    ->where('availbility', true)
                    ->latest()
                    ->take(3)
                    ->get();
                    
        return view('pages.home', compact('services'));
    }
}
