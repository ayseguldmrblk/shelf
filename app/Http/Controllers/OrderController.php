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

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = auth()->user();

        if($user->is_manager==0)
        {
            // $days = Carbon::now()->subDays(30);
            // $book_count=Book::where('user_id', $user->id)->where('created_at', '>=', $days)->count();
            // $user_orders= Order::where('user_id', $user_id)->where('created_at', '>=', $days)->pluck('id')->toArray();
            // $sales = Sale::whereIn('order_id', $user_orders)->pluck('id')->toArray();
            // $user_ordered_item_count = OrderDetail::whereIn('sale_id', $sales)->count();

            // if($user_ordered_item_count<($book_count/5)){

            // }
        }

        $order = New Order;
        $order->user_id = $user->id;
        $order->address = json_encode(Address::where('id', $request->address_id)->first());
        $order->save();

        $carts = Cart::where('user_id', $user->id)->get();
        $donor_products = array();

        foreach($carts as $cart){
            if(array_key_exists($cart->book_owner_id,$donor_products)){
                array_push($donor_products[$cart->book_owner_id], $cart->book_id);
            }else{
                $donor_products[$cart->book_owner_id]=array();
                array_push($donor_products[$cart->book_owner_id], $cart->book_id);
            }

        }

        foreach($donor_products as $key=>$value){

            $sale = new Sale;
            $sale->order_id = $order->id;
            $sale->sender_id = $key;
            $sale->shipping_key = rand(10000000000,99999999999);
            $sale->save();
            $donor_items = $donor_products[$key];

            foreach($donor_items as $item){
                $order_detail = new OrderDetail;
                $order_detail->sale_id = $sale->id;

                $book = Book::where('id', $item)->first();
                $book->available=0;
                $book->save();

                $order_detail->book = json_encode($book);
                $order_detail->save();


            }
        }

        Cart::where('user_id', $user->id)->delete();

    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        foreach($orders as $order){
            $sale_ids=Sale::where('order_id', $order->id)->pluck('id')->toArray();
            $order->books=OrderDetail::whereIn('sale_id', $sale_ids)->get('book');
        }

        return response()->json($orders, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function sales()
    {
        $sales = Sale::with('books')->where('sender_id', auth()->user()->id)->get();


        return response()->json($sales, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
}
