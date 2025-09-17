<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Reservation;

class ReservationsController extends Controller
{
    public function index()
    {
        $status = request('status');
        $query = Reservation::with(['book','user'])->latest('queued_at');
        if ($status && in_array($status, ['active','fulfilled','cancelled'])) {
            $query->where('status', $status);
        }
        $reservations = $query->paginate(20)->appends(request()->query());
        return view('admin.reservations.index', compact('reservations','status'));
    }
}


