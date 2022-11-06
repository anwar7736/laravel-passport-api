<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Utils\Util;
use Validator;

class ProductController extends Controller
{
    public $util;

    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index()
    {
        return response()->json(Product::with('categories')->orderBy('id','desc')->get());
    }

    public function create()
    {
        //no need
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'unique:products'],
            'category_id' => ['required'],
            'price' => ['required'],
            'discount' => '',
            'image' => '',
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $data = $request->only(['name', 'category_id', 'price', 'discount']);

        if($request->hasFile('image'))
        {
            $data['image'] = $this->util->upload_image('products', $request->image);
        }

        $created = Product::create($data);

        if($created)
        {
            return response()->json(['message' => 'Product has been created!']);
        }


    }

    public function show($id)
    {
        return response()->json(Product::with('categories')->whereId($id)->get());
    }

    public function edit($id)
    {
        //no need
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'unique:products,name,'.$id],
            'category_id' => ['required'],
            'price' => ['required'],
            'discount' => '',
            'image' => '',
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $data = $request->only(['name', 'category_id', 'price', 'discount']);

        if($request->hasFile('image'))
        {
            $product = Product::findOrFail($id);
            if($product->image != null)
            {
                $this->util->delete_image('products', $request->image);
            }
            
            $data['image'] = $this->util->upload_image('products', $request->image);
        }

        $updated = Product::whereId($id)->update($data);

        if($updated)
        {
            return response()->json(['message' => 'Product has been updated!']);
        }
    }

    public function destroy($id)
    {

        $product = Product::findOrFail($id);
        if($product->image)
        {
            $this->util->delete_image('products', $product->image);
        }
        
        $product->destroy($id);
        return response()->json(['message' => 'Product has been deleted!']);
    }
}
