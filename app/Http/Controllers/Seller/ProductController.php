<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $seller = auth()->guard('seller')->user();
        
        $products = Product::where('seller_id', $seller->id)
            ->with(['category', 'primaryImage'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->latest()
            ->paginate(20);
        
        $categories = Category::active()->get();
        
        return view('seller.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $seller = auth()->guard('seller')->user();

        // Handle primary image
        $primaryImagePath = $request->file('primary_image')->store('products', 'public');

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $primaryImagePath,
            'status' => $request->status ?? 'active',
            'weight' => $request->weight,
            'meta_title' => $request->meta_title ?? $request->name,
            'meta_description' => $request->meta_description ?? Str::limit($request->description, 160),
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Save primary image to product_images
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $primaryImagePath,
            'is_primary' => true,
            'order' => 0,
        ]);

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false,
                    'order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $seller = auth()->guard('seller')->user();
        
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $categories = Category::active()->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $seller = auth()->guard('seller')->user();
        
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['primary_image', 'images']);
        
        // Handle primary image update
        if ($request->hasFile('primary_image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $primaryImagePath = $request->file('primary_image')->store('products', 'public');
            $data['image'] = $primaryImagePath;
            
            // Update or create primary image record
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                ['image_path' => $primaryImagePath, 'order' => 0]
            );
        }

        $product->update($data);

        // Handle additional images
        if ($request->hasFile('images')) {
            $currentOrder = $product->images()->max('order') ?? 0;
            
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false,
                    'order' => $currentOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $seller = auth()->guard('seller')->user();
        
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        // Delete all images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $seller = auth()->guard('seller')->user();
        
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        return back()->with('success', 'Product status updated.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        $seller = auth()->guard('seller')->user();
        
        if ($product->seller_id !== $seller->id || $image->product_id !== $product->id) {
            abort(403);
        }

        // Don't allow deleting primary image from this method
        if ($image->is_primary) {
            return back()->with('error', 'Cannot delete primary image.');
        }

        // Delete the file
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the database record
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}