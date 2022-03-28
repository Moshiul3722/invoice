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
                                @php
                                    $startdate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', Carbon\Carbon::now())->setTimezone('Asia/Dhaka');
                                    $enddate = $task->end_date;
                                    // Time Left Calculation
                                    if ($enddate > $startdate) {
                                        $days = $startdate->diffInDays($enddate);
                                        $hours = $startdate
                                            ->copy()
                                            ->addDays($days)
                                            ->diffInHours($enddate);
                                        $minutes = $startdate
                                            ->copy()
                                            ->addDays($days)
                                            ->addHours($hours)
                                            ->diffInMinutes($enddate);
                                    } else {
                                        $days = 0;
                                        $hours = 0;
                                        $minutes = 0;
                                    }
                                    // Bar Color And Percent
                                    if ($enddate > $startdate && $task->status == 'pending') {
                                        if ($days == 1) {
                                            $percent = 95;
                                            $color = 'bg-red-700 ';
                                        } elseif ($days < 3) {
                                            $percent = 75;
                                            $color = 'bg-red-400 ';
                                        } elseif ($days < 5) {
                                            $percent = 50;
                                            $color = 'bg-red-300 ';
                                        } else {
                                            $percent = 100;
                                            $color = 'bg-green-500';
                                        }
                                    } else {
                                        $percent = 100;
                                        $color = 'bg-red-500';
                                    }
                                @endphp


                                <li class="flex justify-between items-center border-b py-2 last:border-b-0"><a
                                        href="{{ route('task.show', $task->slug) }}"
                                        class="text-white no-underline font-bold hover:text-black transition-all duration-300 w-8/12">{{ $task->name }}</a>
                                    @if ($enddate > $startdate)
                                        <span class="text-white text-xs w-2/12 text-right">

                                            {{ $days != 0 ? $days . ' Days,' : '' }}
                                            {{ $days != 0 && $hours != 0 ? $hours . ' Hours' : '' }}<br />
                                            {{ $minutes . ' Minutes' }}
                                        </span>
                                    @else
                                        <span class="text-white text-xs w-2/12 text-right">
                                            Time Over!
                                        </span>
                                    @endif

                                </li>
                            @endforeach
                            <div class="text-center">
                                <a href="{{ route('task.index') }}"
                                    class="no-underline inline-block px-5 py-1 text-white bg-teal-600 border-2 rounded-md">View
                                    More</a>
                            </div>
                        </ul>

                    </div>
                    <div>{{ $todo_lists->links() }}</div>
                </div>

                <div class="prose max-w-none flex-1">
                    <h3 class="text-black">Activity Log:</h3>
                    <ul class="bg-cyan-600 text-white rounded-md px-5 py-4 list-none">

                        @forelse ($activity_logs->slice(0, 10) as $activity)
                            <li class="flex justify-between items-center border-b py-2 last:border-b-0">
                                <span class="text-wite duration-300 w-8/12">
                                    {{ $activity->message }}
                                </span>
                                <span class="text-wite text-xs w-8/12 text-right">
                                    {{ $activity->created_at->diffForHumans() }}

                                </span>
                            </li>
                        @empty
                            <li class="flex justify-between items-center border-b py-2">
                                <span class="text-wite duration-300 w-8/12">
                                    No Activity founds!!
                                </span>
                            </li>
                        @endforelse



                        {{-- @forelse($paid_invoices->slice(0, 5) as $paid_invo)
                            <li class="flex justify-between items-center border-b py-2"><a
                                    href="{{ route('task.show', $task->slug) }}"
                                    class="text-white no-underline font-bold hover:text-black transition-all duration-300 w-8/12">{{ $task->name }}</a>
                                @if ($enddate > $startdate)
                                    <span class="text-white text-xs w-2/12 text-right">

                                        {{ $days != 0 ? $days . ' Days,' : '' }}
                                        {{ $days != 0 && $hours != 0 ? $hours . ' Hours' : '' }}
                                        {{ $minutes . ' Minutes' }}
                                    </span>
                                @else
                                    <span class="text-white text-xs w-2/12 text-right">
                                        Time Over!
                                    </span>
                                @endif

                            </li>
                        @empty
                            <li>No Activity found!</li>
                        @endforelse --}}
                    </ul>



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
