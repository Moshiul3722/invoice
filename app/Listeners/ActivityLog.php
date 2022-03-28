<?php

namespace App\Listeners;

use App\Events\ActivityEvent;
use App\Models\ActivityLog as ModelsActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class ActivityLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ActivityEvent  $event
     * @return void
     */
    public function handle(ActivityEvent $event)
    {
        ModelsActivityLog::create([
            'message'   => $event->message,
            'model'     => $event->model,
            'user_id'   => Auth::id()
        ]);
    }
}
