@extends('layouts.layout')

@section('content')



    <!-- Login Link at Bottom Right -->
    <div class="w-full max-w-6xl flex flex-row justify-between space-x-4 p-4">
        <div class="flex flex-row items-center space-x-4">
            <div class="text-2xl">🍺</div>
            <div style="font-family: 'Quintessential', serif;" class="text-xl font-bold text-yellow-500">Bierkasse</div>
        </div>
        <div class="flex-initial flex items-center">
            <form method="post" action="{{ route('changeLocale') }}">
                {{ csrf_field() }}
                <button type="submit" name="language" value="ru" class="text-yellow-500 font-bold hover:underline px-2">RU</button>
                <button type="submit" name="language" value="en" class="text-yellow-500 font-bold hover:underline px-2">EN</button>
            </form>
        </div>
    </div>
    <div class="w-full max-w-6xl flex flex-row justify-between space-x-4 p-4">
        <div class="flex-initial text-l text-green-500">{{ __('messages.balance') }} : {{$balance}}</div>
        <div class="flex-initial text-l text-red-500 underline"><a href="/my-debt">{{ __('messages.debt') }} : {{$debt}}</a></div>
    </div>
    @include('layouts.alerts')

    <div class="w-full max-w-6xl" id="content">
        <!-- Table -->
        <div class="overflow-x-auto">
            <form method="post" action="{{ route('addJournalEntry') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <input type="checkbox" style="display: none" name="isMobile" checked>
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg text-l">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th id="total" class="py-3 px-4 text-2xl text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Form Row -->
                        <tr class="border-t border-gray-600">
                            <td class="py-3 px-4">
                                <input name="name" id="autocomplete-name" type="text" placeholder="{{ __('messages.enter_name') }}" value="{{$clientName}}" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4">
                                <input name="date" type="date" required value="<?php echo date("Y-m-d"); ?>" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4">
                                <select name="method" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                    <option value="Cash">{{ __('messages.cash') }}</option>
                                    <option value="Debt">{{ __('messages.debt') }}</option>
                                    <option value="Deposit">{{ __('messages.deposit') }}</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4">
                                <div id="product-container" class="space-y-2">
                                    @foreach ($preselectedProds as $preselectedProd)
                                    <!-- Initial Product Row -->
                                    <div class="flex space-x-2 product-row">
                                        <select name="products[]" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                                            <option style="display:none;"></option>
                                            <option value="{{ $preselectedProd['name'] }} | {{$preselectedProd['price']}}" selected>
                                                {{ $preselectedProd['name'] }} (€{{ number_format($preselectedProd['price'], 2) }}) [{{ $preselectedProd['quantity'] }}]
                                            </option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->name }}|{{ $product->price }}">
                                                {{ $product->name }} (€{{ number_format($product->price, 2) }}) [{{ $product->quantity }}]
                                            </option>
                                            @endforeach
                                        </select>
                                        <input name="amounts[]" type="number" min="1" value="1" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-gray-100 px-2 rounded remove-row hidden">-</button>
                                    </div>
                                    @endforeach
                                    <div class="flex space-x-2 product-row">
                                            <select name="products[]" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                                                <option style="display:none;"></option>
                                                @foreach ($products as $product)
                                                <option value="{{ $product->name }}|{{ $product->price }}">
                                                    {{ $product->name }} (€{{ number_format($product->price, 2) }}) [{{ $product->quantity }}]
                                                </option>
                                                @endforeach
                                            </select>
                                            <input name="amounts[]" type="number" min="1" value="1" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
                                            <button type="button" class="bg-red-600 hover:bg-red-700 text-gray-100 px-2 rounded remove-row hidden">-</button>
                                        </div>
                                    </div>
                                <button type="button" onclick="addProductRow()" class="mt-2 bg-yellow-600 hover:bg-yellow-500 text-gray-900 font-bold py-1 px-4 rounded">+</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4">
                                <input name="notes" type="text" placeholder="{{ __('messages.notes') }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-center">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 text-3xl font-bold py-2 px-4 rounded">{{ __('messages.submit') }}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>


    </div>

    <script>
        $(document).ready(function() {
            $('#autocomplete-name').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete') }}",
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2 // Minimum length of input before triggering autocomplete
            });
        });
    </script>
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
                productOptions += `<option value="${product.name}|${product.price}">${product.name} (€${product.price}) [${product.quantity}]</option>`;
            });

            newRow.innerHTML = `
                    <select name="products[]" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                        ${productOptions}
                    </select>
                    <input name="amounts[]" type="number" min="1" value="1" onchange="updateTotal()" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
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
                if(!isNaN(Number(productPrice)) || total.innerHTML === "") {
                    newTotal = newTotal + (Number(productPrice) * Number(productAmount));
                    if(total.innerHTML === "") {
                        newTotal = 0;
                    }
                    total.innerHTML = `{{__('messages.total')}}: € ${newTotal}`;
                }
            });
        }

        window.addEventListener('load', updateTotal);
    </script>

@endsection
