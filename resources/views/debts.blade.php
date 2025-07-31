@extends('layouts.layout')

@section('content')

@include('layouts.nav')

@include('layouts.alerts')

<div class="pt-4 w-full max-w-6xl">

    <h1 class="text-3xl font-bold text-yellow-500 my-3 text-center">Debts</h1>
    <div class="flex justify-center">
        <button class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded" onclick="sortDebts()">
            <img src="{{ asset('sort.png') }}" alt="Sort" class="h-5 w-5">
        </button>
    </div>
    <br/>
    <div class="mx-auto max-w-screen-lg">
        <div class="divide-y divide-stone-700 overflow-hidden rounded-lg border border-zinc-600 bg-white dark:bg-zinc-800 shadow-sm">
            @foreach ($debts as $name => $debt)
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between p-4 font-medium text-gray-100 group-open:bg-zinc-900/20">
                    <p style="color: darkslategrey;">{{ $name }} - <span class="debt-value" class="text-lg text-red-300">&euro;  {{ $totals[$name] }}</span></p>
                    <div class="text-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="block h-5 w-5 transition-all duration-300 group-open:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </summary>
                <div class="border-t border-t-stone-100 dark:border-t-stone-700 p-4 text-gray-300">
                    <form method="post" action="{{ route('updateDebts') }}" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <table class="min-w-full text-center table-auto bg-zinc-700 shadow-lg rounded-lg">
                            <thead>
                                <tr class="bg-yellow-600 text-gray-100">
                                    <th class="py-3 px-4 text-center">Date</th>
                                    <th class="py-3 px-4 text-center">Product</th>
                                    <th class="py-3 px-4 text-center">Total</th>
                                    <th class="py-3 px-4 text-center">Notes</th>
                                    <th class="py-3 px-4 text-center">
                                        <button type="submit" value="1" class="bg-yellow-700 hover:bg-yellow-500 text-gray-100 font-bold py-2 px-4 rounded">
                                            Mark as paid
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($debt as $debtEntry)
                                <tr class="border-t border-gray-600">
                                    <!-- Date -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debtEntry['date'] }}</p>
                                    </td>
                                    <!-- Product -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debtEntry['amount'] }}x {{ $debtEntry['product']['name'] }}</p>
                                    </td>
                                    <!-- Total -->
                                    <td class="py-3 px-4">
                                        <p>&euro; {{ $debtEntry['total'] }}</p>
                                    </td>
                                    <!-- Notes -->
                                    <td class="py-3 px-4">
                                        <p>{{ $debtEntry['notes'] }}</p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input name="debts[{{ $debtEntry['id'] }}][pay]" type="checkbox" class="text-center">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </details>
            @endforeach
        </div>
    </div>
</div>

<script>
    function sortDebts() {
        

        const detailsList = Array.from(document.querySelectorAll('details'));
            const sortDirection = localStorage.getItem('sortDirection');
            detailsList.sort((a, b) => {
                const aText = a.querySelector('.debt-value')?.textContent || '';
                const bText = b.querySelector('.debt-value')?.textContent || '';

                const aValue = parseFloat(aText.split('€')[1]?.trim() || 0);
                const bValue = parseFloat(bText.split('€')[1]?.trim() || 0);
                
                if(sortDirection === 'asc') {
                    
                    return bValue - aValue;
                }
                else {

                    return aValue - bValue;
                }
            });
            localStorage.setItem('sortDirection', sortDirection === 'asc' ? 'desc' : 'asc');
            // Re-insert sorted elements
            const parent = detailsList[0].parentElement;
            detailsList.forEach(el => parent.appendChild(el));
    }
</script>

@endsection