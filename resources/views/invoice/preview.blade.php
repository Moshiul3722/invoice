<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Preview') }}
            </h2>

        </div>

    </x-slot>

    <div class="py-20 bg-white border-t flex justify-center">
        <div class="container">
            @include('invoice.pdf');
        </div>
    </div>


</x-app-layout>
