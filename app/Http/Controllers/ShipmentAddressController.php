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
            'lat' => 'required',
            'lon' => 'required',
            'class' => 'nullable',
            'type' => 'nullable',
            'place_rank' => 'nullable',
            'name' => 'required',
            'importance' => 'nullable',
            'display_name' => 'required',
            'address' => 'required',
            'is_default' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $address = Address::create([
            'customer_id' =>    Auth::id(),
            'lat' =>            $request->lat,
            'lon' =>            $request->lon,
            'class' =>          $request->class,
            'type' =>           $request->type,
            'place_rank' =>     $request->place_rank,
            'name' =>           $request->name,
            'importance' =>     $request->importance,
            'display_name' =>   $request->display_name,
            'address' =>        json_encode($request->address),
            'is_default' =>     $request->is_default ?? false,
        ]);

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'Address created successfully',
            'address' => $address,
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
