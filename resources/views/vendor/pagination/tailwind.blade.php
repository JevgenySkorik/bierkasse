@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center m-2 px-4 py-2 text-2xl font-medium text-gray-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-zinc-800 dark:border-zinc-600">
                ←
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center m-2 px-4 py-2 text-2xl font-medium text-gray-700 bg-white border border-zinc-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-zinc-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-gray-300">
                ←
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center m-2 px-4 py-2 text-2xl font-medium text-gray-700 bg-white border border-zinc-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-zinc-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-gray-300">
                →
            </a>
        @else
            <span class="relative inline-flex items-center m-2 px-4 py-2 text-2xl font-medium text-gray-500 bg-white border border-zinc-300 cursor-default leading-5 rounded-md dark:text-gray-600 dark:bg-zinc-800 dark:border-zinc-600">
                →
            </span>
        @endif
    </nav>
@endif
