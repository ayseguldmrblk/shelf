<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Review;

class ReviewController extends Controller
{
    public function reviews($id)
    {
        $reviews = Review::where('user_id', $id)->get();

        return response()->json($reviews, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }


    public function add(Request $request)
    {
        $review = new Review;
        $review->user_id = $request->user_id;
        $review->buyer_id = auth()->user()->id;
        $review->point = $request->point;
        $review->review = $request->review;
        $review->save();
        return response()->json($review, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        Review::where('id',$id)->delete();
    }
}
