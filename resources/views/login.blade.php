@extends('layouts.layout')

@section('content')

    <!-- Logo Section -->
    <header class="mb-8 flex flex-col items-center">
        <!-- Beer Mug Logo -->
        <div class="text-6xl my-3">🍺</div> <!-- Placeholder for an image -->
        <h1 style="font-family: 'Quintessential', serif;" class="text-5xl font-bold text-yellow-500 pt-3">Bierkasse</h1>
    </header>

    @include('layouts.errors')

    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-zinc-800 dark:border-zinc-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Administrator login
                </h1>
                <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('authenticate') }}">
                {{ csrf_field() }}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your username</label>
                        <input type="text" name="name" id="name" class="bg-zinc-50 border border-zinc-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="admin" required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-zinc-50 border border-zinc-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    </div>
                    <button type="submit" class="w-full text-white text-sm bg-yellow-600 hover:bg-yellow-500 text-gray-900 font-bold py-1.5 px-4 rounded">Sign in</button>
                </form>
            </div>
        </div>
    </div>

@endsection
