@extends('layouts.layout')

@section('content')

    @include('layouts.nav')

    @include('layouts.alerts')

    <div class="pt-4 w-full max-w-6xl">
        <h1 class="text-3xl font-bold text-yellow-500 my-3 text-center">{{ __('messages.balances') }}</h1>
        <!-- Add client, who has not purchaced anything yet -->
        <h2 class="text-2xl font-bold text-yellow-500 pt-3 my-3 text-center">{{ __('messages.add_client') }}</h2>
        <form action="{{ route('addName') }}" method="post" class="flex min-w-full items-center space-x-4 justify-center">
            {{ csrf_field() }}
            <div class="flex flex-col">
                <label for="name" class="text-gray-200">{{ __('messages.name') }} :</label>
                <input name="name" type="text" required class="bg-zinc-600 text-gray-200 p-2 rounded">
            </div>
            <div class="flex flex-col">
                <label for="balance" class="text-gray-200">{{ __('messages.balance') }}:</label>
                <input name="balance" type="number" required class="bg-zinc-600 text-gray-200 p-2 rounded w-24">
            </div>
            <button type="submit"
                class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded self-end">
                {{ __('messages.submit') }}
            </button>
        </form>
        <br />
        <!-- Manage balances -->
        <div class="overflow-x-auto">
            <form method="post" action="{{ route('updateBalances') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th class="py-3 px-4 text-center">{{ __('messages.name') }}</th>
                            <th class="py-3 px-4 w-32 text-center">{{ __('messages.balance') }}</th>
                            <th class="py-3 px-4 w-32 text-center">{{ __('messages.refill') }} (€)</th>
                            <th class="py-3 px-4 w-32 text-center">{{ __('messages.withdraw') }}</th>
                            <th class="py-3 px-4 w-12 text-center">
                                <button type="submit" value="1"
                                    class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                    {{ __('messages.save') }}
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($names as $currentName)
                            <tr class="border-t border-gray-600">
                                <!-- Name -->
                                <td class="py-3 px-4">
                                    {{ $currentName->name }}
                                </td>

                                <!-- Balance-->
                                <td class="py-3 px-4">
                                    € {{ $currentName->balance }}
                                </td>
                                <!-- Refill amount -->
                                <td class="py-3 px-4">
                                    <input name="entries[{{ $currentName->id }}][refillWith]" type="number" min="0" value="0"
                                        required class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <input name="entries[{{ $currentName->id }}][withdraw]" type="checkbox"
                                        class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            {{ $names->links() }}
        </div>
    </div>

@endsection