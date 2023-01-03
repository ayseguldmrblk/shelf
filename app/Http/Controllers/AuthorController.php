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

class AuthorController extends Controller
{
    public function getAuthors()
    {
        $authors = Author::get();

        return response()->json($authors, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $author = new Author;
        $author->name=$request->name;
        $author->save();

        return response()->json($author, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $author = Author::where('id', $id);
        $author->delete();
    }
}
