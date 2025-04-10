<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider')->where('availbility', true);

        if ($request->has('search')) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->has('rating')) {
            $query->where('rating_avg', '>=', $request->rating);
        }

        $services = $query->paginate(10);
        $categories = Category::all();

        return view('services.index', compact('services','categories'));
    }
}
