<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Task') }}
            </h2>
            <a href="{{ route('task.create') }}" class="border border-emerald-400 px-3 py-1">Add New</a>
        </div>
    </x-slot>
    @include('layouts.messages')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex justify-between">
                        <div class="mt-10">
                            <h2><b>Title of the task: </b> {{ $task->name }}</h2>
                            <h2><b>Client: </b> {{ $task->client->name }}</h2>
                            <h2><b>E-mail: </b> {{ $task->client->email }}</h2>
                            <h2><b>Phone: </b> {{ $task->client->phone }}</h2>
                        </div>
                        <div>
                            <span
                                class="text-3xl font-black border block border-green-400 py-2 px-5 mt-2 text-green-600 text-center rounded-md">
                                {{ $task->price }}$</span>
                            <div
                                class="capitalize border mt-3 py-2 text-center bg-orange-400 rounded-md text-white font-bold text-xl">
                                <h2> {{ $task->status }}</h2>
                            </div>
                            @if ($task->status == 'pending')
                                <div>
                                    <form action="{{ route('markAsComplete', $task) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="border mt-2 px-5 py-3 rounded-md bg-violet-400 text-white"
                                            type="submit">Mark as Complete</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>



                    <div>

                        <div class="flex justify-between">

                        </div>

                        <h2 class="my-3"><b>Task Details: </b></h2>
                        <div class="border my-4 p-5 prose max-w-none">
                            {!! $task->description !!}
                        </div>

                    </div>




                </div>
            </div>
        </div>
    </div>
</x-app-layout>
