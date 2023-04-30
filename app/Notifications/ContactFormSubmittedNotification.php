<?php

namespace App\Notifications;

use App\Data\ContactFormSubmissionData;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ContactFormSubmittedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected ContactFormSubmissionData $contactFormSubmissionData)
    {
        //
    }

    /**
     * @return array<class-string>
     */
    public function via(object $notifiable): array
    {
        return [MailChannel::class, LogChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $websiteName = config('app.name');
        assert(is_string($websiteName));

        return (new MailMessage)
            ->subject($subject = "Inquiry from {$this->contactFormSubmissionData->name} via {$websiteName} Contact Form")
            ->line($subject.':')
            ->line(new HtmlString('<b>Name:</b> '.e($this->contactFormSubmissionData->name)))
            ->line(new HtmlString('<b>Email:</b> '.e($this->contactFormSubmissionData->email)))
            ->line(new HtmlString('<b>Phone:</b> '.e($this->contactFormSubmissionData->phone ?: 'Not Provided')))
            ->line(new HtmlString('<b>Message:</b> '.e($this->contactFormSubmissionData->message)));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toLog(object $notifiable): array
    {
        return [
            'name' => $this->contactFormSubmissionData->name,
            'email' => $this->contactFormSubmissionData->email,
            'phone' => $this->contactFormSubmissionData->phone,
            'message' => $this->contactFormSubmissionData->message,
        ];
    }
}
