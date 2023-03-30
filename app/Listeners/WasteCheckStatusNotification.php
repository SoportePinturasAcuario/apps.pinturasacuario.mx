<?php

namespace Apps\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// use Apps\Collaborator;

use Apps\Notifications\WasteApprovedNotification;
use Apps\Notifications\WasteRejectedNotification;

class WasteCheckStatusNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $waste = $event->approval->waste;

        $user = $waste->from->user;

        if (!empty($user)) {
            if ($waste->wasReviewed()) {
                if ($waste->isApproved()) {
                    $user->notify(new WasteApprovedNotification($waste));
                }else{
                    $user->notify(new WasteRejectedNotification($waste));
                }
            }
        }
    }
}
