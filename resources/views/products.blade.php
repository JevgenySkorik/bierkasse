@extends('layouts.layout')

@section('content')

@include('layouts.nav')

@include('layouts.alerts')

<div class="pt-4 w-full max-w-6xl">
    <!-- Add new product -->
    <h1 class="text-3xl font-bold text-yellow-500 pt-3 my-3 text-center">Add new product</h1>
    <form action="{{ route('addProductEntry') }}" method="post" class="flex min-w-full items-center space-x-4 justify-center">
        {{ csrf_field() }}
        <div class="flex flex-col">
            <label for="name" class="text-gray-200">Name:</label>
            <input name="name" type="text" required class="bg-zinc-600 text-gray-200 p-2 rounded">
        </div>
        <div class="flex flex-col">
            <label for="price" class="text-gray-200">Price:</label>
            <input name="price" type="text" required class="bg-zinc-600 text-gray-200 p-2 rounded w-24">
        </div>
        <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded self-end">
            Submit
        </button>
    </form>


    <!-- Edit products -->
    <h1 class="text-3xl font-bold text-yellow-500 pt-3 mb-3 mt-12 text-center">Edit products</h1>
    <div class="overflow-x-auto">
        <form method="post" action="{{ route('updateProductEntries') }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-yellow-600 text-gray-100">
                        <th class="py-3 px-4 text-center">Name</th>
                        <th class="py-3 px-4 w-32 text-center">Price</th>
                        <th class="py-3 px-4 w-12 text-center">Delete</th>
                        <th class="py-3 px-4 w-12 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productEntries as $entry)
                    <tr class="border-t border-gray-600">
                        <!-- Name -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][name]" type="text" value="{{ $entry->name }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Price -->
                        <td class="py-3 px-4">
                            <input name="entries[{{ $entry->id }}][price]" type="text" value="{{ $entry->price }}" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                        </td>
                        <!-- Delete -->
                        <td class="py-3 px-4 text-center">
                            <input name="entries[{{ $entry->id }}][delete]" type="checkbox" class="text-center">
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
    {{ $productEntries->links() }}

</div>

@endsection
