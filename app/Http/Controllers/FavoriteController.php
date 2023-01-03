<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Favorite;
use App\Models\Book;


class FavoriteController extends Controller
{
    public function getFavorites()
    {
        $carts = Favorite::where('user_id', auth()->user()->id)->get();

        return response()->json($carts, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $cart = new Favorite;
        $cart->user_id=auth()->user()->id;
        $cart->book_id=$request->book_id;
        $cart->save();

        return response()->json($cart, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $cart = Favorite::where('id', $id);
        $cart->delete();
    }
}
