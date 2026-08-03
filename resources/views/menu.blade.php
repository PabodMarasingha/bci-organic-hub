<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Build Your Own</h1>
        @foreach ($products as $product)
            <a href="{{ route('build', $product) }}" class="block border p-4 rounded mb-2 hover:bg-gray-50">
                {{ $product->name }} — from Rs. {{ $product->base_price }}
            </a>
        @endforeach
    </div>
</x-app-layout>