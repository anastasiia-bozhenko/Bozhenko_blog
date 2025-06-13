<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost; // Переконайтеся, що модель BlogPost існує
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Отримує список блог-постів з пов'язаними даними користувача та категорії.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();
        return response()->json(['data' => $posts]);
    }
}
