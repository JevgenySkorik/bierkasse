@extends('layouts.layout')

@section('content')

    @include('layouts.alerts')

    @vite('resources/js/utils.js')

    <div class="pt-4 w-full max-w-6xl">

        <h1 class="text-3xl font-bold text-yellow-500 my-3 text-center">{{ __('messages.debts') }}</h1>
        <h2 class="text-2xl font-bold text-yellow-500 my-3 text-center">{{ $clientName ?? "-"}}</h2>
        <br />
        <div class="mx-auto max-w-screen-lg">
            <p class="text-center text-2xl">{{ __('messages.total') }} - <span
                    class="text-lg text-red-300 debt-value">&euro; {{ $totalDebt ?? "-" }}</span></p>
            <div class="p-4 text-gray-300">
                <form method="post" action="{{ route('updateDebts') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                    <table class="min-w-full text-center table-auto bg-zinc-700 shadow-lg">
                        <thead>
                            <tr class="bg-yellow-600 text-gray-100">
                                <th class="py-3 px-4 text-center">{{ __('messages.date') }}</th>
                                <th class="py-3 px-4 text-center">{{ __('messages.product') }}</th>
                                <th class="py-3 px-4 text-center">{{ __('messages.total') }}</th>
                                <th class="py-3 px-4 text-center">{{ __('messages.notes') }}</th>
                                <th class="py-3 px-4 text-center">
                                    <button type="submit" value="1"
                                        class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                        {{ __('messages.mark_paid') }}
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($debts as $debt)
                                <tr class="border-t border-gray-600">
                                    <!-- Date -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debt['date'] }}</p>
                                    </td>
                                    <!-- Product -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debt['amount'] }}x {{ $debt['product']['name'] }}</p>
                                    </td>
                                    <!-- Total -->
                                    <td class="py-3 px-4">
                                        <p>&euro; {{ $debt['total'] }}</p>
                                    </td>
                                    <!-- Notes -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debt['notes'] }}</p>
                                    </td>
                                    <td class="py-3 px-4 markPaid">
                                        <input name="debts[{{ $debt['id'] }}][pay]" type="checkbox"
                                            class="text-center">
                                    </td>
                                    <input type="hidden" id="custId" name="fromMyDebt" value="1">                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

@endsection