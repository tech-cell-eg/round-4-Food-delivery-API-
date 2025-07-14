<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use App\Models\Chef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::with(['chef.user'])
            ->latest()
            ->paginate(10);

        $couponsCount = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        $expiredCoupons = Coupon::where('expires_at', '<', Carbon::now())->count();
        $validCoupons = Coupon::where('is_active', true)
            ->where('expires_at', '>=', Carbon::now())
            ->count();

        // Stats
        foreach ($coupons as $coupon) {
            $coupon->is_expired = $coupon->isExpired();

            $coupon->usage_count = $coupon->orders()->count();

            $coupon->total_discounts = $coupon->orders()->sum('discount');
        }

        return view('dashboard.pages.coupons.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chefs = Chef::with('user')->get();
        return view('dashboard.pages.coupons.create', compact('chefs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewCouponRequest $request)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::create([
                'code' => $request->code,
                'chef_id' => $request->chef_id,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'description' => $request->description,
                'expires_at' => $request->expires_at,
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating coupon: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = Coupon::with('chef.user')->findOrFail($id);
        $chefs = Chef::with('user')->get();

        return view('dashboard.pages.coupons.edit', compact('coupon', 'chefs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::findOrFail($id);

            $coupon->update([
                'code' => $request->code,
                'chef_id' => $request->chef_id,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'description' => $request->description,
                'expires_at' => $request->expires_at,
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating coupon: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::findOrFail($id);

            $ordersCount = $coupon->orders()->count();
            if ($ordersCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete coupon. It is being used in ' . $ordersCount . ' orders.');
            }

            $coupon->delete();

            DB::commit();

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting coupon: ' . $e->getMessage());
        }
    }

    /**
     * Toggle coupon status (active/inactive).
     */
    public function toggleStatus(string $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->update([
                'is_active' => ! $coupon->is_active
            ]);

            $status = $coupon->is_active ? 'activated' : 'deactivated';
            return redirect()->back()
                ->with('success', 'Coupon ' . $status . ' successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating coupon status.');
        }
    }
}
