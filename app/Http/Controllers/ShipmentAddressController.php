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
        $userId = Auth::id();

        $address = Address::where('id', $id)
            ->where('customer_id', $userId)
            ->first();

        if (!$address) {
            return ApiResponse::error('العنوان غير موجود أو لا يخص هذا المستخدم', 404);
        }

        $address->delete();

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'تم حذف العنوان بنجاح',
        ], 200);
    }


    public function show($id)
    {
        $userId = Auth::id();

        $address = Address::where('id', $id)
            ->where('customer_id', $userId)
            ->first();

        if (!$address) {
            return ApiResponse::error('العنوان غير موجود أو لا يخص هذا المستخدم', 404);
        }

        return ApiResponse::success([
            'address' => $address,
        ], 'تم جلب العنوان بنجاح', 200);
    }


    public function setAsDefaultAddress($id)
    {
        $userId = Auth::id();

        $address = Address::where('id', $id)
            ->where('customer_id', $userId)
            ->first();

        if (!$address) {
            return ApiResponse::error('العنوان غير موجود أو لا يخص هذا المستخدم', 404);
        }

        // لو هو بالفعل الافتراضي، لا حاجة للتغيير
        if ($address->is_default) {
            return ApiResponse::success([
                'status' => 'success',
                'message' => 'هذا العنوان هو العنوان الافتراضي بالفعل',
            ]);
        }

        // إزالة العنوان الافتراضي الحالي إن وجد
        Address::where('customer_id', $userId)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // تعيين العنوان الجديد كافتراضي
        $address->update(['is_default' => true]);

        return ApiResponse::success([
            'status' => 'success',
            'message' => 'تم تعيين العنوان كافتراضي بنجاح',
        ]);
    }


    public function defaultAddress()
    {
        $address = Address::where('customer_id', Auth::user()->id)->where('is_default', true)->first();

        if ($address) {
            return ApiResponse::success([
                'address' => $address,
            ], 'تم جلب العنوان الافتراضي بنجاح', 200);
        }

        return ApiResponse::success([
            [],
        ], 'لايوجد لديك عنوان افتراضي', 200);
    }
}
