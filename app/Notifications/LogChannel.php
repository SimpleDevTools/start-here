<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Symfony\Component\VarExporter\VarExporter;

class LogChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        throw_if(! method_exists($notification, 'toLog'), 'toLog method not found on notification ['.$notification::class.'].');

        $data = array_merge(
            ['notification_type' => $notification::class],
            $notification->toLog($notifiable),
        );

        Log::channel('notifications')->info(VarExporter::export($data));
    }
}
