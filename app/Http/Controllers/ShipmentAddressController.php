<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipmentAddressController extends Controller
{
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
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Address created successfully',
            'address' => $address,
        ]);
    }
}
