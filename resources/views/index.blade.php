@extends('layouts.layout')

@section('content')

    <!-- Header Section -->
    <header class="mt-2 flex flex-col items-center">
        <!-- Beer Mug Logo -->
        <div class="text-6xl my-3">🍺</div> <!-- Beer Mug Logo -->
        <h1 style="font-family: 'Quintessential', serif;" class="text-5xl font-bold text-yellow-500 pt-3">
            Bierkasse
        </h1>
    </header>

    <!-- Login Link at Bottom Right -->
    <div class="w-full max-w-6xl flex justify-end p-4">
        <a href="{{ route('journal') }}" class="text-yellow-500 font-bold hover:underline">
            Edit
        </a>
    </div>

    @include('layouts.alerts')

    <div class="w-full max-w-6xl">
        <!-- Table -->
        <div class="overflow-x-auto">
            <form method="post" action="{{ route('addJournalEntry') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Payment</th>
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-left">Total</th>
                            <th class="py-3 px-4 text-left">Notes</th>
                            <th class="py-3 px-4 text-left">Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Form Row -->
                        <tr class="border-t border-gray-600">
                            <td class="py-3 px-4">
                                <input name="name" type="text" placeholder="Enter name" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                            <td class="py-3 px-4">
                                <input name="date" type="date" required value="<?php echo date("Y-m-d"); ?>" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                            <td class="py-3 px-4">
                                <select name="method" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                    <option value="Cash">Cash</option>
                                    <option value="Debt">Debt</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <div id="product-container" class="space-y-2">
                                    <!-- Initial Product Row -->
                                    <div class="flex space-x-2 product-row">
                                        <select name="products[]" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                                            <option style="display:none;"></option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->name }}|{{ $product->price }}">{{ $product->name }} (€{{ $product->price }})</option>
                                            @endforeach
                                        </select>
                                        <input name="amounts[]" type="number" min="1" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-gray-100 px-2 rounded remove-row hidden">-</button>
                                    </div>
                                </div>
                                <button type="button" onclick="addProductRow()" class="mt-2 bg-yellow-600 hover:bg-yellow-500 text-gray-900 font-bold py-1 px-4 rounded">+</button>
                            </td>
                            <td class="py-3 px-4">
                                <input id="total" type="text" value="0" disabled class="bg-zinc-700 text-gray-400 w-full p-2 rounded">
                            </td>
                            <td class="py-3 px-4">
                                <input name="notes" type="text" placeholder="Enter notes" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">Submit</button>
                            </td>

                        </tr>
                        @foreach ($journalEntries as $entry)
                        <tr class="border-t border-gray-600">
                            <td class="py-3 px-4">{{ $entry->name }}</td>
                            <td class="py-3 px-4">{{ $entry->date }}</td>
                            <td class="py-3 px-4">{{ $entry->method }}</td>
                            <td class="py-3 px-4">{{ $entry->amount }}x {{ $entry->product->name }}</td>
                            <td class="py-3 px-4">&euro; {{ $entry->total }}</td>
                            <td class="py-3 px-4">{{ $entry->notes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        {{ $journalEntries->links() }}

    </div>

    <script>
        const products = @json($products); // Convert PHP products array to JavaScript

        function addProductRow() {
            const container = document.getElementById('product-container');

            // Create a new product row
            const newRow = document.createElement('div');
            newRow.classList.add('flex', 'space-x-2', 'product-row');

            // Construct product select options dynamically
            let productOptions = '<option style="display:none;"></option>';
            products.forEach(product => {
                productOptions += `<option value="${product.name}|${product.price}">${product.name} (€${product.price})</option>`;
            });

            newRow.innerHTML = `
                    <select name="products[]" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                        ${productOptions}
                    </select>
                    <input name="amounts[]" type="number" min="1" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
                    <button type="button" class="bg-red-600 hover:bg-red-700 text-gray-100 px-2 rounded remove-row">-</button>
                `;

            container.appendChild(newRow);

            // Add event listener to the remove button
            newRow.querySelector('.remove-row').addEventListener('click', function() {
                newRow.remove();
                updateTotal();
            });
        }

        function updateTotal() {
            var total = document.getElementById('total');
            var newTotal = 0.0;
            var products = document.getElementsByName('products[]');
            var amounts = document.getElementsByName('amounts[]');

            products.forEach((product, index) => {
                var productPrice = product.value.split('|')[1];
                var productAmount = amounts[index].value;
                newTotal = newTotal + (Number(productPrice) * Number(productAmount));
            });
            total.value = newTotal;
        }
    </script>

@endsection
