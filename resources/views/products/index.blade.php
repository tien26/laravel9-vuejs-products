<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Product List</h1>

        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <div class="flex space-x-4">
                <input type="text" name="search" placeholder="Search Product Name" class="border px-4 py-2" value="{{ request('search') }}">
                <input type="text" name="category" placeholder="Search Category Name" class="border px-4 py-2" value="{{ request('category') }}">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2">Search</button>
            </div>
        </form>

        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Category</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Price Buy</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $product->name }}</td>
                    <td class="border px-4 py-2">{{ $product->category->name }}</td>
                    <td class="border px-4 py-2">{{ $product->price }}</td>
                    <td class="border px-4 py-2">{{ $product->price_buy }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</body>

</html>