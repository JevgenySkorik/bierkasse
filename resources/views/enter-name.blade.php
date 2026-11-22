@extends('layouts.layout')

@section('content')

    <!-- Login Link at Bottom Right -->
    <div class="w-full max-w-6xl flex flex-row justify-between space-x-4 p-4">
        <div class="flex flex-row items-center space-x-4">
            <div class="text-2xl">🍺</div>
            <div style="font-family: 'Quintessential', serif;" class="text-xl font-bold text-yellow-500">Bierkasse</div>
        </div>
    </div>

    @include('layouts.alerts')

    <div class="w-full max-w-6xl" id="content">
        <!-- Table -->
        <div class="overflow-x-auto">
            <form method="post" action="{{ route('mobileLogin') }}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <input type="checkbox" style="display: none" name="isMobile" checked>
                <table class="min-w-full table-auto bg-zinc-700 shadow-lg rounded-lg text-l">
                    <thead>
                        <tr class="bg-yellow-600 text-gray-100">
                            <th id="total" class="py-3 px-4 text-2xl text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Form Row -->
                        <tr class="border-t border-gray-600">
                            <td class="py-3 px-4 text-center">
                                <input name="name" id="autocomplete-name" type="text"
                                    placeholder="{{ __('messages.enter_name') }}"  required
                                    class="bg-zinc-600 text-gray-200 w-full p-2 rounded">
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-center">
                                <button type="submit"
                                    class="bg-yellow-600 hover:bg-yellow-500 text-gray-100 text-3xl font-bold py-2 px-4 rounded">{{ __('messages.submit') }}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>


    </div>

    <script>
        $(document).ready(function () {
            $('#autocomplete-name').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('autocomplete') }}",
                        data: {
                            query: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 2 // Minimum length of input before triggering autocomplete
            });
        });
    </script>
    
@endsection