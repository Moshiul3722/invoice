<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Invoice') }}
            </h2>
            <a href="{{ route('invoice.index') }}" class="border border-emerald-400 px-3 py-1">Back</a>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('invoice.create') }}" method="GET">
                        @csrf

                        <div class="flex space-x-3 items-end justify-center">
                            <div class="">
                                @error('client_id')
                                    <p class="text-red-700">{{ $message }}</p>
                                @enderror
                                <label for="client_id" class="formLabel">Select Client</label>
                                <select name="client_id" id="client_id" class="formInput">
                                    <option value="none">Select Client</option>

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
                                    <option value="none">Select Status</option>
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
                                    value="{{ request('endDate') != '' ? request('endDate') : now()->format('Y-m-d') }}"
                                    max="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="">
                                <button class="formInput border bg-blue-600 px-6 py-2 text-white">Search</button>
                            </div>

                        </div>

                    </form>


                    @if ($tasks)

                        <form action="{{ route('invoice') }}" method="GET" id="tasksInvoiceForm">
                            @csrf

                            <table class="w-full border-collapse mt-5">
                                <thead>
                                    <tr>
                                        <th class="border py-2">Select</th>
                                        <th class="border py-2">Name</th>
                                        <th class="border py-2">Status</th>
                                        <th class="border py-2">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- {{dd($clients)}} --}}
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td class="border p-2 text-center">
                                                <input type="checkbox" name="invoice_ids[]" value="{{ $task->id }}"
                                                    checked>
                                            </td>
                                            <td class="border p-2 text-center">{{ $task->name }}</td>
                                            <td class="border p-2 text-center">{{ $task->status }}</td>
                                            <td class="border p-2 text-center">${{ $task->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-6 flex justify-between ">
                                <div>
                                    <label for="discount">Discount :</label>
                                    <input type="number" name="discount" id="discount"
                                        placeholder="Enter discount amount">
                                    <select name="discount_type" id="discount_type">
                                        <option value="%">%</option>
                                        <option value="$">$</option>
                                    </select>
                                </div>
                                <div class="space-x-2">
                                    <button type="submit" name="preview" value="yes" form="tasksInvoiceForm"
                                        class="bg-purple-600 text-white px-5 py-2">Preview</button>
                                    <button type="submit" name="generate_pdf" value="yes" form="tasksInvoiceForm"
                                        class="bg-pink-600 text-white px-3 py-2">Generate PDF</button>
                                </div>
                            </div>
                        </form>


                    @endif


                </div>


            </div>
        </div>
    </div>


</x-app-layout>
