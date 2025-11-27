<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;



class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', session('keyword', ''));
        if ($request->has('keyword')) {
            session(['keyword' => $keyword]);
        }

        $tab = $request->query('tab', '');

        $keyword = $request->input('keyword', session('keyword', ''));

        if ($tab === '') {
            $tab = empty($keyword) ? (auth()->check() ? 'mylist' : 'recommended') : 'recommended';
        }

        if ($tab === 'mylist') {
            if (auth()->check()) {
                $products = auth()->user()->likes()->with('purchases')->ProductSearch($keyword)->get();
            } else {
                $products = collect();
            }

            return view('products', compact('products', 'tab', 'keyword'));
        }

        if ($tab === 'recommended') {

            $query = Product::with('purchases')->ProductSearch($keyword);

            if (auth()->check()) {
                $products = $query->where('user_id', '<>', auth()->id())->get();
            } else {
                $products = $query->get();
            }
        }

        return view('products', compact('products', 'tab', 'keyword'));
    }

    public function showDetail($item_id)
    {
        $product = Product::with([
            'categories',
            'condition',
            'comments.user.profile',
            'likedBy'
        ])->findOrFail($item_id);

        return view('detail', compact('product'));
    }


    public function showSellForm()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell_item', compact('categories', 'conditions'));
    }

    public function sellItem(ExhibitionRequest $request)
    {
        $filename = $request->file('product_image')->getClientOriginalName();
        $request->file('product_image')->storeAs('public/product_images', $filename);

        $product = Product::create([
            'product_image' => $filename,
            'condition_id' => $request->condition_id,
            'user_id' => auth()->id(),
            'name' => $request->input('name'),
            'brand' => $request->brand,
            'content' => $request->input('content'),
            'price' => $request->input('price'),
        ]);

        $product->categories()->sync($request->categories);

        return redirect('/');
    }
}
