<x-app-layout>
    <x-slot name="header">

    </x-slot>

    @php
        function getImageUrl($image)
        {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return asset('storage/uploads') . '/' . $image;
        }
    @endphp

    <div class="py-10">
        <div class="max-w-5xl mx-auto">
            <div class="">
                <div class="py-36 relative bg-black bg-opacity-50 bg-blend-overlay bg-cover bg-no-repeat bg-center mb-10"
                    style="background-image: url('https://picsum.photos/1024/400')">
                    <div class="absolute bottom-5 left-5 flex space-x-5">
                        <div class=" w-28 h-28 bg-center bg-cover rounded-full "
                            style="background-image: url({{ getImageUrl($client->thumbnail) }})"></div>
                        <div class="">
                            <h1 class="text-white mt-5 text-lg">{{ $client->name }}
                                <span class="text-xs">({{ $client->username }})</span>
                            </h1>
                            <h2 class="text-white">{{ $client->country }}</h2>
                        </div>
                    </div>
                </div>

                <h2 class="text-black font-bold text-2xl">Summery</h2>
                <div class="grid grid-cols-4 gap-5 mt-6">
                    <div class="bg-gradient-to-tr from-amber-500 to-yellow-200 rounded-md">
                        <a href="{{ route('task.index') }}?client_id={{ $client->id }}"
                            class="flex px-10 py-14 flex-col items-center">
                            <h1 class="font-bold text-3xl">{{ count($client->tasks) }}</h1>
                            <h2 class="text-emerald-900 font-black uppercase">Total Tasks</h2>
                        </a>
                    </div>
                    <div class="bg-gradient-to-tr from-amber-500 to-yellow-200 rounded-md">
                        <a href="{{ route('task.index') }}?client_id={{ $client->id }}&status=pending"
                            class="flex px-10 py-14 flex-col items-center">
                            <h1 class="font-bold text-3xl">{{ count($pending_tasks) }}</h1>
                            <h2 class="text-emerald-900 font-black uppercase">Pending Tasks</h2>
                        </a>
                    </div>
                    <div class="bg-gradient-to-tr from-amber-500 to-yellow-200 rounded-md">
                        <a href="{{ route('invoice.index') }}?client_id={{ $client->id }}"
                            class="flex px-10 py-14 flex-col items-center">
                            <h1 class="font-bold text-3xl">{{ count($client->invoices) }}</h1>
                            <h2 class="text-emerald-900 font-black uppercase">Total Invoice</h2>
                        </a>
                    </div>
                    <div class="bg-gradient-to-tr from-amber-500 to-yellow-200 rounded-md">
                        <a href="{{ route('invoice.index') }}?client_id={{ $client->id }}&status=paid"
                            class="flex px-10 py-14 flex-col items-center">
                            <h1 class="font-bold text-3xl">{{ count($paid) }}</h1>
                            <h2 class="text-emerald-900 font-black uppercase">Paid Invoice</h2>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
