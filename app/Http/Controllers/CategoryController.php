<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Book;

use Illuminate\Support\Facades\Storage;
use Image;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::withCount('books')->get();

        return response()->json($categories, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $category = new Category;
        $category->name=$request->name;
        if($request->has('image')){
            $category->image = $this->uploadImage($request->image);
        }
        $category->save();
    }

    public function delete($id)
    {
        $category = Category::where('id', $id);
        $category->delete();
        Book::where('category', $id)->delete();
    }

    public function uploadImage($file)
    {
        $realImage = base64_decode($file);
        $dir = public_path('img');

        $newFileName = 'cat' . rand(10000000000, 99999999999) . date("YmdHis") . "." . "webp";
        $newFullPath = $dir."/".$newFileName;

        Image::make(file_get_contents($file))->save($newFullPath);

        return 'img/'.$newFileName;
    }
}
