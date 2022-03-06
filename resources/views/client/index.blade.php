<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Client') }}
            </h2>
            <a href="{{ route('client.create') }}" class="border border-emerald-400 px-3 py-1">Add New Client</a>
        </div>
    </x-slot>

    @if (Session('success'))
        <div class="bg-emerald-200 text-emerald-700 text-center py-2" id="status_message">
            <p>{{ Session('success') }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border py-2 w-32 text-center">Thumbnail</th>
                                <th class="border py-2">Name</th>
                                <th class="border py-2">Country</th>
                                <th class="border py-2">Task count</th>
                                <th class="border py-2">Status</th>
                                <th class="border py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{dd($clients)}} --}}
                            @php
                                function getImageUrl($image)
                                {
                                    if (str_starts_with($image, 'http')) {
                                        return $image;
                                    }
                                    return asset('storage/uploads') . '/' . $image;
                                }
                            @endphp

                            @forelse ($clients as $client)
                                <tr>
                                    <td class="border py-2 w-32 text-center">
                                        <img src="{{ getImageUrl($client->thumbnail) }}" alt="" width="50"
                                            class="mx-auto" srcset="">
                                    </td>
                                    <td class="border px-3 py-2 text-left">
                                        <a class="font-bold text-xl hover:text-purple-600"
                                            href="{{ route('client.show', $client) }}"> {{ $client->name }}</a>

                                        <p class="text-base"> {{ $client->username }}<br>
                                            {{ $client->email }}<br>
                                            {{ $client->phone }}</p>
                                    </td>

                                    <td class="border p-2 text-center">{{ $client->country }}</td>
                                    <td class="border p-2 text-center">
                                        <div class=""> <a
                                                href="{{ route('task.index') }}?client_id={{ $client->id }}"
                                                class="relative px-3 py-1 bg-teal-600 group inline-block uppercase text-white text-sm">
                                                <span
                                                    class="absolute group-hover:bg-orange-500 group-hover:text-white group-hover:border-white transition-all from-neutral-300 bg-white text-black border border-black -right-4 -top-4 rounded-full w-7 h-7 leading-7 text-center text-xs">{{ count($client->tasks) }}</span>View</a>
                                        </div>
                                    </td>
                                    <td class="border p-2 text-center">{{ $client->status }}</td>
                                    {{-- <td class="border p-2 text-center">{{ $client->status }}</td> --}}
                                    <td class="border p-2">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('client.edit', $client->id) }}"
                                                class="bg-emerald-800 text-white px-3 py-1 mr-1">Edit</a>
                                            <form action="{{ route('client.destroy', $client->id) }}" method="POST"
                                                onsubmit="return confirm('Do you really want to delete!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-700 text-white px-3 py-1">Delete</button>
                                            </form>
                                        </div>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border p-2 text-center" colspan="6">
                                        <p>No Client found.</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
