<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    // Place any shared logic or helper methods here
    // For example, a method to return the authenticated user
    public function user()
    {
        return parent::user();
    }

    // You can also add shared authorize/rules logic if needed
    // public function authorize()
    // {
    //     return true;
    // }
}
