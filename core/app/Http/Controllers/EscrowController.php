<?php

namespace App\Http\Controllers;

use App\Models\Escrow;
use Illuminate\Support\Facades\Auth;

class EscrowController extends Controller
{
    public function release($id)
    {
        $escrow = Escrow::findOrFail($id);

        // Only buyer can confirm
        if ($escrow->buyer_id !== Auth::id() || $escrow->status !== 'funded') {
            return back()->with('error', 'Unauthorized or invalid action.');
        }

        // Mark as released
        $escrow->status = 'released';
        $escrow->save();

        // TODO: Credit seller's balance here

        // Release
        $ad = $escrow->ad;
        $ad->status = 'sold';
        $ad->save();

        return back()->with('success', 'Payment released to seller!');
    }

    public function cancel($id)
    {
        $escrow = Escrow::findOrFail($id);

        // Only buyer can confirm
        if ($escrow->buyer_id !== Auth::id() || $escrow->status !== 'pending') {
            return back()->with('error', 'Unauthorized or invalid action.');
        }

        // Mark as canceled
        $escrow->status = 'canceled';
        $escrow->save();

        // Release the ad
        $ad = $escrow->ad;
        $ad->status = 'available';
        $ad->save();

        return back()->with('success', 'Escrow canceled and ad reactivated.');
    }
}