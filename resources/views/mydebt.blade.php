@extends('layouts.layout')

@section('content')

@include('layouts.alerts')

@vite('resources/js/utils.js')

<div class="pt-4 w-full max-w-6xl">

    <h1 class="text-3xl font-bold text-yellow-500 my-3 text-center">{{ __('messages.debts') }}</h1>
    <h2 class="text-2xl font-bold text-yellow-500 my-3 text-center">{{$clientName}}</h2>
    <br/>
    <div class="mx-auto max-w-screen-lg">
        <p class="text-center text-2xl">{{ __('messages.total') }} - <span class="text-lg text-red-300 debt-value">&euro;  {{ $totalDebt }}</span></p>
        <div class="p-4 text-gray-300">
            <table class="min-w-full text-center table-auto bg-zinc-700 shadow-lg">
                <thead>
                    <tr class="bg-yellow-600 text-gray-100">
                        <th class="py-3 px-4 text-center">{{ __('messages.date') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.product') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.total') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('messages.notes') }}</th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection