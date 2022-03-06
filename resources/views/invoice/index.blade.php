<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoices') }}
            </h2>
            <a href="{{ route('invoice.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>

    @include('layouts.messages')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



            <div class="mb-6 bg-white py-10 rounded-md {{ request('client_id') || request('status') || request('email_sent') ? '' : 'hidden' }}"
                id="task_filter">
                <h2 class="text-center text-2xl font-bold mb-6">Filter Invoice</h2>
                <form action="{{ route('invoice.index') }}" method="GET">
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
                                <option value="paid"
                                    {{ old('status') == 'paid' || request('status') == 'paid' ? 'selected' : '' }}>
                                    Paid</option>
                                <option value="unpaid"
                                    {{ old('status') == 'unpaid' || request('status') == 'unpaid' ? 'selected' : '' }}>
                                    Unpaid</option>
                            </select>
                        </div>
                        <div class="">
                            @error('email_sent')
                                <p class="text-red-700">{{ $message }}</p>
                            @enderror
                            <label for="email_sent" class="formLabel">Email Sent</label>
                            <select name="email_sent" id="status" class="formInput">
                                <option value="">Email Sent</option>
                                <option value="yes"
                                    {{ old('email_sent') == 'yes' || request('email_sent') == 'yes' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="no"
                                    {{ old('email_sent') == 'no' || request('email_sent') == 'no' ? 'selected' : '' }}>
                                    No</option>
                            </select>
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
                                class="py-2 px-4 bg-cyan-600 text-white mb-6">{{ request('client_id') || request('status') || request('email_sent') ? 'Close Filter' : 'Filter' }}</button>
                        </div>
                    </div>

                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border py-2">#</th>
                                @if (empty(request('client_id')))
                                    <th class="border py-2">Client Name</th>
                                @endif
                                <th class="border py-2">Status</th>
                                <th class="border py-2">Email Sent</th>
                                <th class="border py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{dd($clients)}} --}}
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="border p-2 text-center">
                                        <a target="_blank" class="hover:text-purple-600 font-bold"
                                            href="{{ asset('storage/invoices/' . $invoice->download_url) }}">{{ $invoice->invoice_id }}</a>
                                    </td>

                                    @if (empty(request('client_id')))
                                        <td class="border p-2 text-center ">
                                            <a class="text-red-600 text-bold hover:text-purple-500"
                                                href="{{ route('task.index') }}?client_id={{ $invoice->client->id }}">{{ $invoice->client->name }}</a>
                                        </td>
                                    @endif

                                    {{-- <td class="border p-2 text-center">{{ $invoice->client->name }}</td> --}}
                                    <td class="border text-center capitalize">{{ $invoice->status }}

                                        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST"
                                            onsubmit="return confirm('Did you want continue?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="bg-cyan-500 text-white px-3 py-1 w-full hover:bg-cyan-600 transition-all">{{ $invoice->status == 'unpaid' ? 'paid' : 'unpaid' }}</button>
                                        </form>

                                    </td>

                                    <td class="border text-center capitalize flex flex-col">
                                        {{ $invoice->email_sent }}
                                        <a href="{{ route('invoice.sendEmail', $invoice) }}"
                                            class="bg-teal-600 w-full text-white hover:bg-orange-400 transition-all duration-300 px-3 py-1">Send
                                            Email</a>

                                    </td>
                                    <td class="border p-2 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a target="_blank" class="bg-purple-600 text-white px-3 py-1"
                                                href="{{ asset('storage/invoices/' . $invoice->download_url) }}">Preview</a>

                                            <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST"
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
                                        <p>No Invoice found.</p>
                                    </td>
                                </tr>
                            @endforelse




                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
