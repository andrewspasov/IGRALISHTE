@if($products->isEmpty())
    <div class="text-center py-10">
        <p class="text-lg text-gray-700">
            No products found.
        </p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($products as $product)
            <div class="border rounded-lg p-4 cursor-pointer relative overflow-hidden mb-5 hover:shadow-lg transition-shadow duration-300 ease-in-out">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-md font-semibold">{{ $product->product_name }}</h5>
                    <span class="text-sm bg-gray-200 rounded-full px-3 py-1">{{ $product->brand->name ?? 'No Brand' }}</span>
                </div>
                @if($product->productImages->isNotEmpty())
                    <img src="{{ asset('storage/'.$product->productImages->first()->image) }}" alt="{{ $product->product_name }}" class="w-full h-48 object-cover rounded-md">
                @else
                    <img src="{{ asset('path/to/default-image.jpg') }}" alt="Default Image" class="w-full h-48 object-cover rounded-md">
                @endif
                <div class="mt-4">
                    <p class="text-gray-600 text-sm">{{ Str::limit($product->description, 100) }}</p>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-lg font-bold">{{ $product->price }} $</span>
                    <a href="{{ route('products.showDetails', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-300">View details</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
