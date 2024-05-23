<!-- <x-app-layout>


<div class="container mx-auto px-4 py-4">
    <h1 class="text-xl font-bold mb-4">{{ $product->product_name }}</h1>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ $product->price }}</p>
    <p><strong>How to Use:</strong> {{ $product->how_to_use }}</p>
    <p><strong>Size Description:</strong> {{ $product->desc_for_size }}</p>
    <p><strong>Tags:</strong> {{ $product->tags }}</p>
    <p><strong>Brand:</strong> {{ $product->brand->brand_name }}</p>
    <p><strong>Category:</strong> {{ $product->brandCategory->category_name }}</p>
    <p><strong>Active:</strong> {{ $product->is_active ? 'Yes' : 'No' }}</p>
    <p><strong>Quantity:</strong> {{ $product->productVariants->sum('quantity') }}</p>
    <div><strong>Colors:</strong> {{ implode(', ', $product->productVariants->pluck('color.color_name')->unique()->toArray()) }}</div>
    <div><strong>Sizes:</strong> {{ implode(', ', $product->productVariants->pluck('size.size')->unique()->toArray()) }}</div>
    <div><strong>Images:</strong>
        @foreach ($product->productImages as $image)
            <img src="{{ asset('storage/'.$image->image) }}" alt="Product Image" style="width: 100px;">
        @endforeach
    </div>
    <div><strong>Discounts:</strong>
    <ul>
    @foreach ($product->discounts as $discount)
        <li>{{ $discount->discount_name }} - {{ $discount->amount }}%</li>
    @endforeach
    </ul>
</div>
</div>
</x-app-layout> -->
