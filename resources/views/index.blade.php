<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bierkasse</title>

        <script src="https://cdn.tailwindcss.com"></script>

        <style>
.css-selector {
    background: linear-gradient(179deg, #202020, #291d00);
    background-size: 400% 400%;

    -webkit-animation: AnimationName 11s ease infinite;
    -moz-animation: AnimationName 11s ease infinite;
    animation: AnimationName 11s ease infinite;
}

@-webkit-keyframes AnimationName {
    0%{background-position:51% 0%}
    50%{background-position:50% 100%}
    100%{background-position:51% 0%}
}
@-moz-keyframes AnimationName {
    0%{background-position:51% 0%}
    50%{background-position:50% 100%}
    100%{background-position:51% 0%}
}
@keyframes AnimationName {
    0%{background-position:51% 0%}
    50%{background-position:50% 100%}
    100%{background-position:51% 0%}
}
        </style>

    </head>
    <body class="css-selector text-gray-100 min-h-screen flex flex-col items-center justify-center">

        <!-- Logo Section -->
        <header class="mb-8 flex flex-col items-center">
            <!-- Beer Mug Logo -->
            <div class="text-6xl mb-2">🍺</div> <!-- Placeholder for an image -->
            <h1 class="text-5xl font-bold text-yellow-500">Bierkasse</h1>
        </header>

        <div class="w-full max-w-6xl">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-left">Amount</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Payment</th>
                            <th class="py-3 px-4 text-left">Notes</th>
                            <th class="py-3 px-4 text-left">Total</th>
                            <th class="py-3 px-4 text-left">Submit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Form Row -->
                        <tr class="border-t border-gray-600">
                            <form method="post" action="{{ route('addJournalEntry') }}" accept-charset="UTF-8">
                                {{ csrf_field() }}
                                <td class="py-3 px-4">
                                    <input name="name" type="text" placeholder="Enter name" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <select name="product" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                        <option style="display:none;"></option>
                                        <option value="beer">Beer</option>
                                        <option value="chips">Chips</option>
                                        <option value="kvas">Kvas</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <input name="amount" type="number" placeholder="Amount" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <input name="date" type="date" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <select name="method" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                        <option style="display:none;"></option>
                                        <option value="cash">Cash</option>
                                        <option value="debt">Debt</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <input name="notes" type="text" placeholder="Enter notes" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4">
                                    <input name="total" type="text" placeholder="Enter total" class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-600 text-gray-100 font-bold py-2 px-4 rounded">Submit</button>
                                </td>
                            </form>
                        </tr>
                        @foreach ($journalEntries as $entry)
                            <tr class="border-t border-gray-600">
                                <td class="py-3 px-4">{{ $entry->name }}</td>
                                <td class="py-3 px-4">{{ $entry->product }}</td>
                                <td class="py-3 px-4">{{ $entry->amount }}</td>
                                <td class="py-3 px-4">{{ $entry->date }}</td>
                                <td class="py-3 px-4">{{ $entry->method }}</td>
                                <td class="py-3 px-4">{{ $entry->notes }}</td>
                                <td class="py-3 px-4">{{ $entry->total }}</td>
                            </tr>
                        @endforeach
                        <!-- Example Data Rows (Optional) -->
                        <tr class="border-t border-gray-600">
                            <td class="py-3 px-4">John Doe</td>
                            <td class="py-3 px-4">Beer</td>
                            <td class="py-3 px-4">2</td>
                            <td class="py-3 px-4">25.11.2024.</td>
                            <td class="py-3 px-4">Cash</td>
                            <td class="py-3 px-4">Birthday gift</td>
                            <td class="py-3 px-4">2.50</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>
