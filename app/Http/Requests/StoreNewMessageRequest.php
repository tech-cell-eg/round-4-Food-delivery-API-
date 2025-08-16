<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNewMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "receiver_id" => "required|int|exists:users,id",
            'content' => 'required_if:type,text|string|nullable',
            'type' => 'required|in:text,voice,image',
            'audio' => 'required_if:type,voice|file|mimes:mp3,wav,ogg,webm|max:10240',
            'image' => 'required_if:type,image|file|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'receiver_id.required' => 'معرف المستلم مطلوب',
            'receiver_id.exists' => 'المستخدم المحدد غير موجود',
            'content.required_if' => 'محتوى الرسالة مطلوب للرسائل النصية',
            'type.required' => 'نوع الرسالة مطلوب',
            'type.in' => 'نوع الرسالة يجب أن يكون نص أو صوت أو صورة',
            'audio.required_if' => 'ملف الصوت مطلوب للرسائل الصوتية',
            'audio.file' => 'يجب أن يكون الملف ملف صوت صحيح',
            'audio.mimes' => 'صيغة الملف يجب أن تكون mp3, wav, ogg, أو webm',
            'audio.max' => 'حجم ملف الصوت يجب أن لا يتجاوز 10 ميجابايت',
            'image.required_if' => 'ملف الصورة مطلوب للرسائل المصورة',
            'image.file' => 'يجب أن يكون الملف ملف صورة صحيح',
            'image.mimes' => 'صيغة الملف يجب أن تكون jpeg, jpg, png, gif, أو webp',
            'image.max' => 'حجم ملف الصورة يجب أن لا يتجاوز 5 ميجابايت',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            ApiResponse::validationError($errors)
        );
    }
}
