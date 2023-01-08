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

use Illuminate\Support\Facades\Storage;
use Image;

class BookController extends Controller
{

    public function getBooks(Request $request)
    {
        $books = Book::where('available', 1)->join('authors', 'books.author', '=', 'authors.id')->join('categories', 'books.category', '=', 'categories.id');
        if($request->has('author') && $request->author!=""){
            $authors = explode(',',$request->author);
            $books->whereIn('books.author', $authors);
        }

        if($request->has('category') && $request->category!=""){
            $category = explode(',',$request->category);
            $books->whereIn('books.category', $category);
        }

        if($request->has('name') && $request->name!=""){
            $books->where('books.name', 'like', '%'.$request->name.'%');
        }

        if($request->has('shipment_type') && $request->shipment_type!=""){
            $books->where('books.shipment_type', $request->shipment_type);
        }

        $books = $books->get(['books.*', 'authors.name as author', 'categories.name as category']);

        return response()->json($books, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function getBook($id)
    {
        $book = Book::where('books.id', $id)->join('authors', 'books.author', '=', 'authors.id')
        ->join('categories', 'books.category', '=', 'categories.id')
        ->first(['books.*', 'authors.name as author', 'categories.name as category']);

        return response()->json($book, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $book = new Book;
        $book->name = $request->name;
        $book->donor = $request->donor;
        $book->abstract = $request->abstract;
        $book->author = $request->author;
        $book->category = $request->category;
        $book->shipment_type = $request->shipment_type;
        $book->page_count = $request->page_count;

        if($request->image1!=""){
            $book->image1= $this->uploadImage($request->image1);
        }
        if($request->image2!=""){
            $book->image2= $this->uploadImage($request->image2);
        }
        if($request->image3!=""){
            $book->image3= $this->uploadImage($request->image3);
        }

        $book->save();
        $book = Book::where('books.id', $book->id)
        ->join('authors', 'books.author', '=', 'authors.id')
        ->join('categories', 'books.category', '=', 'categories.id')
        ->first(['books.*', 'authors.name as author', 'categories.name as category']);
        return response()->json($book, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $book = Book::where('id', $id)->delete();
    }

    public function update($id, Request $request)
    {
        $book = Book::where('id', $id)->first();
        $book->name = $request->name;
        $book->donor = $request->donor;
        $book->abstract = $request->abstract;
        $book->author = $request->author;
        $book->category = $request->category;
        $book->shipment_type = $request->shipment_type;
        $book->page_count = $request->page_count;
        if($request->image1!=""){
            $book->image1= $this->uploadImage($request->image1);
        }
        if($request->image2!=""){
            $book->image2= $this->uploadImage($request->image2);
        }
        if($request->image3!=""){
            $book->image3= $this->uploadImage($request->image3);
        }
        $book->save();

        $book = Book::where('books.id', $book->id)
        ->join('authors', 'books.author', '=', 'authors.id')
        ->join('categories', 'books.category', '=', 'categories.id')
        ->first(['books.*', 'authors.name as author', 'categories.name as category']);
        return response()->json($book, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }


    public function uploadImage($file)
    {
        $realImage = base64_decode($file);
        $dir = public_path('img');

        $newFileName = rand(10000000000, 99999999999) . date("YmdHis") . "." . "webp";
        $newFullPath = $dir."/".$newFileName;

        Image::make(file_get_contents($file))->save($newFullPath);

        return 'img/'.$newFileName;
    }
}
