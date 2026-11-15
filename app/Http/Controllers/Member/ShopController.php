<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    protected function ensureBusiness()
    {
        if (auth()->user()->type !== 'business') {
            abort(403, 'Shop is available for business accounts only.');
        }
    }

    public function index()
    {
        $this->ensureBusiness();
        $shop = Shop::where('user_id', auth()->user()->id)->first();

        return view('user.shop.index')
            ->with('shop', $shop);
    }
    public function store(Request $request)
    {
        $this->ensureBusiness();
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'website' => 'required',
        ]);

        $slug = getShopCode($request->name);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->slug = $slug;
        $shop->user_id = auth()->user()->id;
        $shop->address = $request->address;
        $shop->phone = $request->phone;
        $shop->email = $request->email;
        $shop->save();
        if ($request->website) {
            $shortlink = new ShortLink();
            $shortlink->slug = $slug;
            $shortlink->shop_id = $shop->id;
            $shortlink->link = $request->website;
            $shortlink->user_id = auth()->user()->id;
            $shortlink->shortlink = route('shortlink', $slug);
            $shortlink->save();
            $shop->website = $shortlink->shortlink;
        } else {
            $shop->website = $request->website;
        }

        $shop->save();
        return redirect()->back()->with('success', 'Shop created successfully');
    }

    public function update(Request $request, $slug)
    {
        $this->ensureBusiness();
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'website' => 'required',
        ]);

        $shop = Shop::where('slug', $slug)->first();
        $shop->slug = $request->name;
        $shop->name = getShopCode($request->name);
        $shop->user_id = auth()->user()->id;
        $shop->address = $request->address;
        $shop->phone = $request->phone;
        $shop->email = $request->email;
        if ($request->website) {
            $findShortLink = ShortLink::where('shop_id', $shop->id)->first();
            if ($findShortLink) {
                $shortlink = $findShortLink;
            } else {
                $shortlink = new ShortLink();
            }
            $shortlink->slug = $slug;
            $shortlink->link = $request->website;
            $shortlink->user_id = auth()->user()->id;
            $shortlink->shortlink = route('shortlink', $slug);
            $shortlink->save();
            $shop->website = $shortlink->shortlink;
        } else {
            $shop->website = $request->website;
        }
        $shop->save();
        return redirect()->back()->with('success', 'Shop created successfully');
    }
    public function createProduct()
    {
        $this->ensureBusiness();
        $shop = Shop::where('user_id', auth()->id())->firstOrFail();
        return view('user.shop.product.create', [
            'shop' => $shop,
        ]);
    }
    public function storeProduct(Request $request)
    {
        $this->ensureBusiness();
        $request->validate([
            'name' => 'required',
            'original_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = getProductCode($request->name);
        $product->shop_id = auth()->user()->shop->id;
        $product->original_price = $request->original_price;
        $product->sale_price = $request->sale_price;
        $product->description = $request->description;
        $product->save();

        if ($request->hasFile('images')) {
            $sort = 0;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '-' . $sort . '.' . $extension;
                $file->move('uploads/products', $filename);
                $path = 'uploads/products/' . $filename;
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'sort_order' => $sort,
                ]);
                $sort++;
            }
        }

        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function editProduct($slug)
    {
        $this->ensureBusiness();
        $product = Product::where('slug', $slug)->firstOrFail();
        if ($product->shop_id != auth()->user()->shop->id) {
            abort(403);
        }
        return view('user.shop.product.edit', [
            'product' => $product,
        ]);
    }

    public function updateProduct(Request $request, $slug)
    {
        $this->ensureBusiness();
        $request->validate([
            'name' => 'required',
            'original_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $product = Product::where('slug', $slug)->first();

        if ($product->shop_id != auth()->user()->shop->id) {
            Session::flash('error', 'You are not authorized to update this product');
            return redirect()->back();
        }

        $product->name = $request->name;
        $product->shop_id = auth()->user()->shop->id;
        $product->original_price = $request->original_price;
        $product->sale_price = $request->sale_price;
        $product->description = $request->description;
        $product->save();

        if ($request->hasFile('images')) {
            $existingMax = (int) $product->images()->max('sort_order');
            $sort = $existingMax + 1;
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '-' . $sort . '.' . $extension;
                $file->move('uploads/products', $filename);
                $path = 'uploads/products/' . $filename;
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'sort_order' => $sort,
                ]);
                $sort++;
            }
        }
        Session::flash('success', 'Product updated successfully');
        return redirect()->back();
    }
}
