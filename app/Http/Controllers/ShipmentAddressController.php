<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ShipmentAddressController extends Controller
{

    // All of my addresses
    public function index()
    {
        return ApiResponse::success([
            'addresses' => Address::where('customer_id', Auth::user()->id)->get()
        ], 'تم جلب جميع العناوين بنجاح', 200);
    }
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat'           => 'required|string',
            'lon'           => 'required|string',
            'name'          => 'required|string',
            'display_name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }
        if ($request->is_default) {
            $defaultAddress = Address::where('customer_id', Auth::user()->id)->where('is_default', true)->first();
            if ($defaultAddress) {
                $defaultAddress->is_default = false;
                $defaultAddress->save();
            }
        }

        $address = Address::create([
            'customer_id' =>    Auth::id(),
            'lat' =>            $request->lat,
            'lon' =>            $request->lon,
            'name' =>           $request->name,
            'display_name' =>   $request->display_name,
            'is_default' =>     $request->is_default ?? false,
        ]);

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'Address created successfully',
            'address' => $address,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lat'           => 'required|string',
            'lon'           => 'required|string',
            'name'          => 'required|string',
            'display_name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }
        if ($request->is_default) {
            $defaultAddress = Address::where('customer_id', Auth::user()->id)->where('is_default', true)->first();
            if ($defaultAddress) {
                $defaultAddress->is_default = false;
                $defaultAddress->save();
            }
        }

        $address = Address::find($id);
        $address->update([
            'customer_id' =>    Auth::id(),
            'lat' =>            $request->lat,
            'lon' =>            $request->lon,
            'name' =>           $request->name,
            'display_name' =>   $request->display_name,
            'is_default' =>     $request->is_default ?? false,
        ]);

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'address' => $address,
        ], 200);
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        $address->delete();

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'Address deleted successfully',
        ], 200);
    }

    public function show($id)
    {
        $address = Address::find($id);
        return ApiResponse::success([
            'address' => $address,
        ], 'تم جلب العنوان بنجاح', 200);
    }

    public function setAsDefaultAddress($id)
    {
        $address = Address::find($id);
        $defaultAddress = Address::where('customer_id', Auth::user()->id)->where('is_default', true)->first();
        if ($defaultAddress) {
            $defaultAddress->is_default = false;
            $defaultAddress->save();
        }

        $address->update([
            'is_default' => true,
        ]);

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'Address set as default successfully',
        ], 200);
    }


    /* default address */

    public function defaultAddress()
    {
        return ApiResponse::success([
            'address' => Address::where('customer_id', Auth::user()->id)->where('is_default', true)->first()
        ], 'تم جلب العنوان الافتراضي بنجاح', 200);
    }
}
