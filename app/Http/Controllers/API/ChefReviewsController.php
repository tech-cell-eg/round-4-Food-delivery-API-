<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

use App\Http\Resources\ReviewResource;
use App\Models\Chef;


class ChefReviewsController extends Controller
{

    public function  index($chefId)
    {
        $chef = Chef::findOrFail($chefId);

        $reviews = $chef->reviews()
            ->with(['customerWithUser'])
            ->orderBy('created_at', 'desc')
            ->get();
        return ApiResponse::success([
            'chef_id' => $chef->id,
            'total_reviews' => $chef->reviews()->count(),
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }
}
