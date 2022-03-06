<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task') }}
            </h2>
            <a href="{{ route('task.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>

    @include('layouts.messages')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 bg-white py-10 rounded-md {{ request('client_id') || request('status') || request('fromDate') || request('endDate') || request('price')? '': 'hidden' }}"
                id="task_filter">
                <h2 class="text-center text-2xl font-bold mb-6">Filter Tasks</h2>
                <form action="{{ route('task.index') }}" method="GET">
                    <div class="flex space-x-3 items-end justify-center">
                        <div class="">
                            @error('client_id')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="client_id" class="formLabel">Select Client</label>
                            <select name="client_id" id="client_id" class="formInput">
                                <option value="">Select Client</option>

                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $client->id == old('client_id') || $client->id == request('client_id') ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="">
                            @error('status')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="status" class="formLabel">Select Status</label>
                            <select name="status" id="status" class="formInput">
                                <option value="">Select Status</option>
                                <option value="pending"
                                    {{ old('status') == 'pending' || request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="complete"
                                    {{ old('status') == 'complete' || request('status') == 'complete' ? 'selected' : '' }}>
                                    Complete</option>
                            </select>
                        </div>
                        <div class="">
                            @error('fromDate')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="fromDate" class="formLabel">Start Date</label>
                            <input type="date" class="formInput" name="fromDate" id="formDate"
                                max="{{ now()->format('Y-m-d') }}" value="{{ request('fromDate') }}">
                        </div>
                        <div class="">
                            @error('endDate')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="endDate" class="formLabel">End Date</label>
                            <input type="date" class="formInput" name="endDate" id="toDate"
                                value="{{ request('endDate') != '' ? request('endDate') : '' }}"
                                max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="">
                            @error('endDate')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="price" class="formLabel">Max Price</label>
                            <input type="number" class="formInput" name="price" id="price"
                                value="{{ request('price') }}">
                        </div>
                        <div class="">
                            <button class="formInput border bg-blue-600 px-6 py-2 text-white">Search</button>
                        </div>

                    </div>

                </form>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @php
                        $justify = !empty(request('client_id')) ? 'justify-between' : 'justify-end';
                    @endphp

                    <div class="flex {{ $justify }}">
                        @if (!empty(request('client_id')))
                            <div class="bg-cyan-500 text-lg py-3 px-3 mb-4 text-white">
                                Client name : <b>{{ $clients->where('id', request('client_id'))->first()->name }}</b>
                            </div>
                        @endif


                        <div class="text-right">
                            <button id="task_filter_btn" type="submit"
                                class="py-2 px-4 bg-cyan-600 text-white mb-6">{{ request('client_id') || request('status') || request('fromDate') || request('endDate') || request('price')? 'Close Filter': 'Filter' }}</button>
                        </div>

                    </div>

                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border py-2">Name</th>
                                <th class="border py-2">Price ($)</th>
                                <th class="border py-2">Status</th>
                                @if (empty(request('client_id')))
                                    <th class="border py-2">Client Name</th>
                                @endif
                                <th class="border py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{dd($clients)}} --}}

                            @forelse ($tasks as $task)
                                <tr>

                                    <td class="border p-2 text-left">
                                        <a class="font-bold text-base hover:text-purple-500"
                                            href="{{ route('task.show', $task->slug) }}">{{ $task->name }}</a>
                                    </td>
                                    <td class="border p-2 text-center text-sm">{{ $task->price }}</td>
                                    <td class="border text-center capitalize text-sm">{{ $task->status }}

                                        @if ($task->status == 'pending')
                                            <form action="{{ route('markAsComplete', $task) }}" method="POST"
                                                onsubmit="return confirm('Are you sure the task is Done!');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="border-2 bg-teal-500 text-white hover:bg-transparent hover:text-black transition-all duration-300 px-3 py-1 w-full">Done</button>
                                            </form>
                                        @endif

                                    </td>



                                    @if (empty(request('client_id')))
                                        <td class="border p-2 text-center ">
                                            <a class="text-red-600 text-bold hover:text-purple-500"
                                                href="{{ route('task.index') }}?client_id={{ $task->client->id }}">{{ $task->client->name }}</a>
                                        </td>
                                    @endif


                                    <td class="border p-2 text-center">
                                        <div class="flex justify-center">

                                            <a href="{{ route('task.edit', $task->id) }}"
                                                class="bg-emerald-800 text-white px-3 py-1 mr-1">Edit</a>

                                            <form action="{{ route('task.destroy', $task->id) }}" method="POST"
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
                                        <p>No Task found.</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
