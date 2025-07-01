<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function created($data = null, $message = 'Created Successfully')
    {
        return self::success($data, $message, 201);
    }

    public static function deleted($message = 'Deleted Successfully')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], 200);
    }

    public static function error($message = 'An error occurred', $code = 400, $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function notFound($message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    public static function validationError($errors, $message = 'Validation Error')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    public static function withPagination($paginator, $dataKey = 'data', $message = 'Success')
    {
        return response()->json([
            // Indicates that the operation was successful
            'status' => true,

            // A message describing the result
            'message' => $message,

            // The actual paginated data items
            $dataKey => $paginator->items(),

            // Pagination metadata for client-side navigation and UI
            'pagination' => [
                'total' => $paginator->total(),                   // Total number of records across all pages
                'count' => $paginator->count(),                   // Number of records in the current page
                'per_page' => $paginator->perPage(),              // Number of items per page
                'current_page' => $paginator->currentPage(),      // Current page number
                'total_pages' => $paginator->lastPage(),          // Total number of pages
                'first' => $paginator->firstItem(),               // Index of the first item on the current page
                'last' => $paginator->lastItem(),                 // Index of the last item on the current page
                'next_page_url' => $paginator->nextPageUrl(),     // URL of the next page, if available
                'prev_page_url' => $paginator->previousPageUrl(), // URL of the previous page, if available
            ]
        ]);
    }
}
