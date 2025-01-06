<nav class="bg-white bg-zinc-800 w-full border-b border-gray-200 border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('index') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="h-8 text-3xl" alt="Beer">🍺</span>
            <span style="font-family: 'Quintessential', serif;" class="self-center text-2xl font-semibold whitespace-nowrap text-yellow-500">Bierkasse</span>
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0">
                <li>
                    <a href="{{ route('journal') }}" class="block py-2 px-3 text-white hover:text-yellow-500 rounded md:p-0">Journal</a>
                </li>
                <li>
                    <a href="{{ route('products') }}" class="block py-2 px-3 text-white hover:text-yellow-500 rounded md:p-0">Products</a>
                </li>
                <li>
                    <a href="{{ route('debts') }}" class="block py-2 px-3 text-white hover:text-yellow-500 rounded md:p-0">Debts</a>
                </li>
                <li>
                    <a href="{{ route('export') }}" class="block py-2 px-3 text-green-500 hover:text-green-400 rounded md:p-0">Export</a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="block py-2 px-3 text-red-500 hover:text-red-600 rounded md:p-0">logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
