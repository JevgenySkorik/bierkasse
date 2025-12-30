@extends('layouts.layout')

@section('content')

    @include('layouts.nav')

    @include('layouts.alerts')

    @vite('resources/js/utils.js')

    <div class="pt-4 w-full max-w-6xl">
        <!-- Add new product -->
        <h1 class="text-3xl font-bold text-yellow-500 pt-3 my-3 text-center">{{ __('messages.add_product') }}</h1>
        <form action="{{ route('addProductEntry') }}" method="post"
            class="flex min-w-full items-center space-x-4 justify-center">
            {{ csrf_field() }}
            <div class="flex flex-col">
                <label for="name" class="text-gray-200">{{ __('messages.pr_name') }}:</label>
                <input name="name" type="text" required class="bg-zinc-600 text-gray-200 p-2 rounded">
            </div>
            <div class="flex flex-col">
                <label for="price" class="text-gray-200">{{ __('messages.price') }}:</label>
                <input name="price" type="text" required class="bg-zinc-600 text-gray-200 p-2 rounded w-24">
            </div>
            <button type="submit"
                class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded self-end">
                {{ __('messages.submit') }}
            </button>
        </form>


        <!-- Edit products -->
        <h1 class="text-3xl font-bold text-yellow-500 pt-3 mb-3 mt-12 text-center">{{ __('messages.edit_products') }}</h1>

        <div class="flex w-fit p-4 mb-4 mx-auto text-sm rounded-lg bg-zinc-800 text-red-400" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Danger</span>
            <div>
                <span class="font-medium">{{ __('messages.danger_1') }}</span>
                <ul class="mt-1.5 list-disc list-inside">
                    <li>{{ __('messages.danger_2') }}</li>
                    <li>{{ __('messages.danger_3') }}></li>
                    <li>{{ __('messages.danger_4') }}</li>
                </ul>
            </div>
        </div>

        <div class="overflow-x-auto">
            <form method="post" action="{{ route('updateProductEntries') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th class="py-3 px-4 text-center">{{ __('messages.pr_name') }}</th>
                            <th class="py-3 px-4 w-32 text-center">{{ __('messages.price') }}</th>
                            <th class="py-3 px-4 w-32 text-center">{{ __('messages.amount') }}</th>
                            <th class="py-3 px-4 text-center">
                                <input type="button"
                                    class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded"
                                    onclick="enableRemoving()" value="{{ __('messages.delete') }}">
                            </th>
                            <th class="py-3 px-4 w-12 text-center">
                                <button type="submit" value="1"
                                    class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                    {{ __('messages.save') }}
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productEntries as $entry)
                            <tr class="border-t border-gray-600">
                                <!-- Name -->
                                <td class="py-3 px-4">
                                    <input name="entries[{{ $entry->id }}][name]" type="text" value="{{ $entry->name }}"
                                        required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <!-- Price -->
                                <td class="py-3 px-4">
                                    <input name="entries[{{ $entry->id }}][price]" type="text" value="{{ $entry->price }}"
                                        required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <input name="entries[{{ $entry->id }}][quantity]" type="text" value="{{ $entry->quantity }}"
                                        required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <!-- Delete -->
                                <td class="py-3 px-4 text-center">
                                    <input name="entries[{{ $entry->id }}][delete]" type="checkbox" class="text-center">
                                </td>
                                <td class="py-3 px-4 text-center">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        <!-- Pagination -->
        {{ $productEntries->links() }}

    </div>

@endsection