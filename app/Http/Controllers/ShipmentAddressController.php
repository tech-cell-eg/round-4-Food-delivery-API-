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
            'post_code' => 'nullable',
            'address_text' => 'required',
            'street' => 'required',
            'appartment' => 'required',
            'lable' => 'required',
            'is_default' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }


        $address = Address::create([
            'customer_id' => Auth::user()->id,
            'post_code' => $request->post_code,
            'address_text' => $request->address_text,
            'street' => $request->street,
            'appartment' => $request->appartment,
            'lable' => $request->lable,
            'is_default' => $request->is_default,
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
