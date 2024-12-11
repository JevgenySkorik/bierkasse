@extends('layouts.layout')

@section('content')

@include('layouts.nav')

<div class="pt-32 w-full max-w-6xl">
    <!-- Dashboard Table -->
    <div class="overflow-x-auto">
        <form method="post" action="{{ route('updateJournalEntries') }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-yellow-600 text-gray-100">
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Date</th>
                        <th class="py-3 px-4 text-left">Payment</th>
                        <th class="py-3 px-4 text-left">Product</th>
                        <th class="py-3 px-4 text-left">Amount</th>
                        <th class="py-3 px-4 text-left">Total</th>
                        <th class="py-3 px-4 text-left">Notes</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journalEntries as $entry)
                    <tr class="border-t border-gray-600">
                        <!-- Name -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][name]" type="text" value="{{ $entry->name }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Date -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][date]" type="date" value="{{ $entry->date }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Payment Method -->
                        <td class="py-3 px-4">
                            <select name="entries[{{ $entry->id }}][method]" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                <option value="Cash" {{ $entry->method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Debt" {{ $entry->method == 'Debt' ? 'selected' : '' }}>Debt</option>
                            </select>
                        </td>
                        <!-- Product -->
                        <td class="py-3 px-4">
                            <select name="entries[{{ $entry->id }}][product]" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                                <option style="display:none;"></option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->name }}" {{ $entry->product == $product->name ? "selected" : "" }} >
                                        {{ $product->name }} (€{{ $product->price }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][amount]" type="number" value="{{ $entry->amount }}" min="1" required class="bg-zinc-600 text-gray-200 w-16 p-2 rounded">
                        </td>
                        <!-- Total -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][total]" type="text" value="{{ $entry->total }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Notes -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][notes]" type="text" value="{{ $entry->notes }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Actions -->
                        <td class="py-3 px-4 text-center">
                            <button type="submit" name="save[{{ $entry->id }}]" value="1" class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                Save
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
    <!-- Pagination -->
    {{ $journalEntries->links() }}
</div>

@endsection
