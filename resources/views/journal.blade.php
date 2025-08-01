@extends('layouts.layout')

@section('content')

@include('layouts.nav')

@include('layouts.alerts')

<div class="pt-8 w-full max-w-6xl">
    <h1 class="text-3xl font-bold text-yellow-500 my-3 text-center">{{ __('messages.edit_journal') }}</h1>
    <!-- Dashboard Table -->
    <div class="overflow-x-auto">
        <form method="post" action="{{ route('updateJournalEntries') }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-yellow-600 text-gray-100">
                        <th class="py-3 px-4 text-center">{{ __('messages.name') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.date') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.paymethod') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.product') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.amount') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.total') }}</th>
                        <th class="py-3 px-4 text-center">
                            <input type="button" class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded" onclick="enableRemoving()" value="{{ __('messages.delete') }}">

                        </th>
                        <th class="py-3 px-4 text-center">
                            <button type="submit" value="1" class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                {{ __('messages.save') }}
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($journalEntries as $entry)
                    <tr class="border-t border-gray-600">
                        <!-- Name -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][name]" type="text" value="{{ $entry->name }}" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Date -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][date]" type="date" value="{{ $entry->date }}" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Payment Method -->
                        <td class="py-3 px-4">
                            <select name="entries[{{ $entry->id }}][method]" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                <option value="Cash" {{ $entry->method == 'Cash' ? 'selected' : '' }}>{{ __('messages.cash') }}</option>
                                <option value="Debt" {{ $entry->method == 'Debt' ? 'selected' : '' }}>{{ __('messages.debt') }}</option>
                            </select>
                        </td>
                        <!-- Product -->
                        <td class="py-3 px-4">
                            <select name="entries[{{ $entry->id }}][product]" required class="bg-zinc-600 text-gray-200 w-32 p-2 rounded">
                                <option style="display:none;" value="{{ $entry->product->name }}">{{ $entry->product->name }}</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->name }}" {{ $entry->product == $product->name ? "selected" : "" }}>
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
                            <input name="entries[{{ $entry->id }}][total]" type="text" value="{{ $entry->total }}" required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Remove entry -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][remove]" disabled type="checkbox"  class="bg-zinc-600 text-gray-200 w-full p-2 rounded" >
                        </td>
                        <!-- Notes -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][notes]" type="text" value="{{ $entry->notes }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded" placeholder="{{ __('messages.notes') }}">
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
<script>
    function enableRemoving() {
        const checkboxes = document.querySelectorAll('input[type=checkbox]');
        const isDisabled= checkboxes[0].disabled;
        checkboxes.forEach((el) => {
            if(isDisabled) {
                el.disabled = false;
            }
            else {
                el.checked = false;
                el.disabled = true;
            }
        });
    }
</script>
@endsection