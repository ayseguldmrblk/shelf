<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::get();

        return response()->json($categories, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function add(Request $request)
    {
        $category = new Category;
        $category->name=$request->name;
        $category->save();
    }

    public function delete($id)
    {
        $category = Category::where('id', $id);
        $category->delete();
    }
}
