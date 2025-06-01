<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of services with search and filter functionality
     */
    public function index(Request $request)
    {
        $query = Service::with('category', 'provider')->where('availbility', true);

        // Search by service title
        if ($request->filled('search')) {
            $query->where('title_service', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating_avg', '>=', $request->rating);
        }

        // Sort results
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        // Validate sort parameters to prevent SQL injection
        $allowedSortFields = ['title_service', 'base_price', 'rating_avg', 'created_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $allowedSortOrders = ['asc', 'desc'];
        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $services = $query->paginate(10);
        $categories = Category::all();

        return view('pages.services.index', compact('services', 'categories'));
    }

    /**
     * Display the specified service with details
     */
    public function show(Service $service)
    {
        // Load the service with its relationships
        $service->load(['category', 'provider', 'reviews']);
        
        // Get related services in the same category
        $relatedServices = Service::where('category_id', $service->category_id)
            ->where('service_id', '!=', $service->service_id)
            ->where('availbility', true)
            ->take(4)
            ->get();
            
        return view('pages.services.show', compact('service', 'relatedServices'));
    }

    /**
     * Get popular services for homepage or widgets
     */
    public function getPopularServices()
    {
        $popularServices = Service::where('availbility', true)
            ->orderBy('rating_avg', 'desc')
            ->take(6)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $popularServices
        ]);
    }

    /**
     * Get services by category
     */
    public function getServicesByCategory(Category $category)
    {
        $services = Service::where('category_id', $category->id)
            ->where('availbility', true)
            ->paginate(10);
            
        return view('pages.services.by-category', compact('services', 'category'));
    }
}
