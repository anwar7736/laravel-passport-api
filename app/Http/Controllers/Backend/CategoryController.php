<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::latest()->get());
    }

    public function create()
    {
        //no need
    }


    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        Category::create($request->all());
        return response()->json(['message' => 'Category has been created!']);
    }


    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }


    public function edit($id)
    {
        //no need
    }


    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$id,
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        Category::whereId($id)->update($request->all());
        return response()->json(['message' => 'Category has been updated!']);
    }


    public function destroy($id)
    {
        Category::findOrFail($id)->destroy($id);
        return response()->json(['message' => 'Category has been deleted!']);
    }
}
