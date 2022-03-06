<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="container mx-auto py-10">
            <div class="grid grid-cols-4 gap-5">

                <x-card text="Clients" :count="count($user->clients)" :route="route('client.index')"
                    class="bg-gradient-to-tr from-cyan-300 to-white rounded-md" />

                <x-card text="Pending Tasks" :count="count($pending_tasks)" :route="route('task.index')"
                    class="bg-gradient-to-tl from-cyan-300 to-white rounded-md" />

                <x-card text="Completed Tasks" :count="count($user->tasks) - count($pending_tasks)"
                    route="{{ route('task.index') }}?status=complete"
                    class="bg-gradient-to-bl from-cyan-300 to-white rounded-md" />

                <x-card text="Due Invoice" :count="count($due_invoices)"
                    route="{{ route('invoice.index') }}?status=unpaid"
                    class="bg-gradient-to-br from-cyan-300 to-white rounded-md" />

            </div>
        </div>
    </div>

    <div class="">
        <div class="container mx-auto">
            <div class="flex justify-between space-x-5">
                <div class="prose max-w-none flex-1">
                    <div class="">
                        <h3 class="text-black mt-0">Todo:</h3>
                        <ul class="bg-cyan-500 px-5 py-4 inline-block rounded-md list-none w-full">
                            @foreach ($todo_lists as $task)
                                <li><a href="{{ route('task.show', $task->slug) }}"
                                        class="no-underline font-bold hover:text-white">{{ $task->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>{{ $todo_lists->links() }}</div>
                </div>
                <div class="prose max-w-none flex-1">
                    <h3 class="text-black">Payment History:</h3>

                    <ul class="bg-cyan-600 text-white rounded-md px-5 py-4  list-none">
                        @forelse ($paid_invoices->slice(0, 5) as $paid_invo)
                            <li class="flex justify-between items-center">
                                <span class="text-sm">{{ $paid_invo->updated_at->format('d M, Y') }}</span>
                                <span class="text-left flex-1 mx-5">{{ $paid_invo->client->name }}</span>
                                <span class="text-left">${{ $paid_invo->amount }}</span>
                            </li>
                        @empty
                            <li>No paid invoice found !!!</li>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
