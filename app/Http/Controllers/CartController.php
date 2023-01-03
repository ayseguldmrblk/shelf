<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\Author;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Publisher;
use App\Models\Report;
use App\Models\Sale;
use App\Models\User;

use Carbon\Carbon;

class CartController extends Controller
{
    public function getCart()
    {
        $carts = Cart::with('book')->where('user_id', auth()->user()->id)->get();

        return response()->json($carts, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $cart = new Cart;
        $cart->user_id=auth()->user()->id;
        $cart->book_id=$request->book_id;
        $cart->book_owner_id=Book::where('id', $request->book_id)->first()->donor;
        $cart->save();

    }

    public function delete($id)
    {
        $cart = Cart::where('id', $id);
        $cart->delete();
    }

    public function checkRight(){
            $days = Carbon::now()->subDays(30);
            $book_count=Book::where('donor', auth()->user()->id)->where('created_at', '>=', $days)->count();
            $user_orders= Order::where('user_id', auth()->user()->id)->where('created_at', '>=', $days)->pluck('id')->toArray();
            $sales = Sale::whereIn('order_id', $user_orders)->pluck('id')->toArray();
            $user_ordered_item_count = OrderDetail::whereIn('sale_id', $sales)->count();

            $book_right = ($book_count/5)-$user_ordered_item_count;
            if($book_right==0 && $book_count==0 && $user_ordered_item_count==0 ){
                $book_right=1;
            }
            return response()->json(['user_book_right'=>$book_right]);

    }
}
