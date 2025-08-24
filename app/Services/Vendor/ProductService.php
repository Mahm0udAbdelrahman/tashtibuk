<?php
namespace App\Services\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Size;
use App\Models\SubCategory;
use App\Traits\HasImage;
use Illuminate\Support\Facades\Auth;


class ProductService
{
    use HasImage;
    public function __construct(public Product $model)
    {}

    public function index($request)
    {
        $query = $this->model->query();

        $query->where(function ($q) use ($request) {
            if (! empty($request['category_id'])) {
                $q->orWhere('category_id', $request['category_id']);
            }

             if (! empty($request['name'])) {
                $q->orWhere(function ($sub) use ($request) {
                    $sub->where('name_en', 'LIKE', '%' . $request['name'] . '%')
                        ->orWhere('name_ar', 'LIKE', '%' . $request['name'] . '%');
                });
            }
            
            if (! empty($request['sub_category_id'])) {
                $q->orWhere('sub_category_id', $request['sub_category_id']);
            }
            
              if (!empty($request['sub_category_name'])) {
            $q->orWhereHas('subCategory', function ($subQ) use ($request) {
                $subQ->where('name_ar', 'LIKE', '%' . $request['sub_category_name'] . '%')
                     ->orWhere('name_en', 'LIKE', '%' . $request['sub_category_name'] . '%');
            });
        }
        });

      if (Auth::guard('vendor')->check()) {

            return $query->where('vendor_id', Auth::guard('vendor')->id())
                ->latest()
                ->paginate();
        }
        return $query->latest()->paginate();
    }

    

    public function store($data)
    {
      
        $data['vendor_id'] = auth('vendor')->user()->id;
        $product           = $this->model->create($data);

        foreach ($data['variants'] as $variant) {
            ProductColorSize::create([
                'product_id' => $product->id,
                'color'      => $variant['color'],
                'size_id'    => $variant['size_id'],
                'quantity'   => $variant['quantity'],
            ]);
        }

        if (! empty($data['images']) && is_array($data['images'])) {
            $imagesData = [];

            foreach ($data['images'] as $image) {
                $imagesData[] = [
                    'image' => $this->saveImage($image, 'product'),
                ];
            }

            $product->productImages()->createMany($imagesData);
        }

        return $product;
    }
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id , $data)
    {
           $product           = $this->model->findOrFail($id);
        $data['vendor_id'] = auth('vendor')->user()->id;

        $product->update([
            'vendor_id'       => $data['vendor_id'],
            'category_id'     => $data['category_id'] ?? $product->category_id,
            'sub_category_id' => $data['sub_category_id'] ?? $product->sub_category_id,
            'price'           => $data['price'] ?? $product->price,
            'name'            => $data['name'] ?? $product->name,
            'description'     => $data['description'] ?? $product->description,
        ]);

        $product->colorSizes()->delete();

        if (! empty($data['variants']) && is_array($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                ProductColorSize::create([
                    'product_id' => $product->id,
                    'color'      => $variant['color'],
                    'size_id'    => $variant['size_id'],
                    'quantity'   => $variant['quantity'],
                ]);
            }
        }

        if (! empty($data['images']) && is_array($data['images'])) {
            $product->productImages()->delete();

            $imagesData = [];
            foreach ($data['images'] as $image) {
                $imagesData[] = [
                    'image' => $this->saveImage($image, 'product'),
                ];
            }

            $product->productImages()->createMany($imagesData);
        }

        return $product->fresh();
    }

    public function destroy($id)
    {
        $product = $this->show($id);
        return $product->delete();
    }

    public function category()
    {
        return Category::all();
    }

    public function subCategory()
    {
        return SubCategory::all();
    }

    public function size()
    {
        return Size::all();
    }

}
