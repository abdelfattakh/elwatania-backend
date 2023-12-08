<?php

namespace App\Listeners;

use App\Events\NewOrderEvent;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewOrderListener
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
    public function handle( NewOrderEvent $event)
    {
       $user=User::find( $event->order->user_id);
        $order_code=($event->order->order_code);
       Notification::send($user,new GeneralNotification("New Order was submitted","your order code  is" .' '.$order_code .'  '. "save ite for more information Contact us "));
    }
}
