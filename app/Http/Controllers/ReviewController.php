<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Chef;


class ReviewController extends Controller
{

    public function  index($chefId)
    {
        $chef = Chef::findOrFail($chefId);

        $reviews = $chef->reviews()
            ->with(['customerWithUser'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'chef_id' => $chef->id,
            'total_reviews' => $chef->reviews()->count(),
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }
}
