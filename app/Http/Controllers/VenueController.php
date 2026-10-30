<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Court;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with('courts')->where('status', 'active');

        // Filter kata kunci (nama venue, kota, alamat, deskripsi)
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter Kota
        if ($request->filled('city') && $request->input('city') !== 'Semua') {
            $query->where('city', 'like', "%{$request->input('city')}%");
        }

        // Filter Jenis Lapangan (Surface Type)
        if ($request->filled('surface')) {
            $surfaces = is_array($request->input('surface')) ? $request->input('surface') : explode(',', $request->input('surface'));
            $query->whereHas('courts', function ($cq) use ($surfaces) {
                $cq->where(function ($sub) use ($surfaces) {
                    foreach ($surfaces as $s) {
                        $sub->orWhere('surface_type', 'like', "%" . trim($s) . "%");
                    }
                });
            });
        }

        // Filter Fasilitas
        if ($request->filled('facility')) {
            $facilities = is_array($request->input('facility')) ? $request->input('facility') : explode(',', $request->input('facility'));
            foreach ($facilities as $fac) {
                if (!empty(trim($fac))) {
                    $query->where('facilities', 'like', "%" . trim($fac) . "%");
                }
            }
        }

        $venues = $query->get();

        return view('venues.index', compact('venues'));
    }

    public function show($slug)
    {
        $venue = Tenant::with(['courts'])->where('slug', $slug)->firstOrFail();

        $activeBookings = \App\Models\Booking::where('tenant_id', $venue->id)
            ->where(function ($q) {
                $q->where('status', 'paid')
                  ->orWhere(function ($sub) {
                      $sub->where('status', 'pending')
                          ->where('created_at', '>=', \Carbon\Carbon::now()->subMinutes(15));
                  });
            })
            ->get(['court_id', 'booking_date', 'start_time', 'end_time', 'booking_type'])
            ->map(function ($b) {
                return [
                    'court_id' => $b->court_id,
                    'date' => \Carbon\Carbon::parse($b->booking_date)->toDateString(),
                    'start_time' => substr($b->start_time, 0, 5),
                    'end_time' => substr($b->end_time, 0, 5),
                    'time' => substr($b->start_time, 0, 5) . ' - ' . substr($b->end_time, 0, 5),
                    'booking_type' => $b->booking_type,
                ];
            });

        return view('venues.show', compact('venue', 'activeBookings'));
    }
}
