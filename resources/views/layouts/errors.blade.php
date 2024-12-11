@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 my-3 rounded relative" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li>
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $error }}</span>
        </li>
        @endforeach
    </ul>
</div>
@endif
