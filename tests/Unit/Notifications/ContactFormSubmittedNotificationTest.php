<?php

namespace Tests\Unit\Notifications;

use App\Data\ContactFormSubmissionData;
use App\Notifications\ContactFormSubmittedNotification;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\VarExporter\VarExporter;
use Tests\TestCase;
use TiMacDonald\Log\ChannelFake;
use TiMacDonald\Log\LogEntry;
use TiMacDonald\Log\LogFake;

/**
 * @see \App\Notifications\ContactFormSubmittedNotification
 */
class ContactFormSubmittedNotificationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        LogFake::bind();
        $mailer = app(Mailer::class);
        assert($mailer instanceof Mailer);
        $this->assertEquals('array', invade($mailer)->name);
    }

    public function test_it_sends_an_email(): void
    {
        $contactFormSubmissionData = new ContactFormSubmissionData(
            name: $name = fake()->name,
            email: $email = fake()->safeEmail,
            phone: $phone = fake()->optional()->numerify('(###) ###-####') ?: '',
            message: $message = fake()->paragraph,
        );
        Notification::route('mail', ['example@example.com' => 'Example Name'])
            ->notify(new ContactFormSubmittedNotification($contactFormSubmissionData));

        $messages = $this->getMailMessages();
        $this->assertCount(1, $messages);
        $mailMessage = $messages->first();
        assert($mailMessage instanceof SentMessage);
        $mailMessage = $mailMessage->getOriginalMessage();
        assert($mailMessage instanceof Email);

        $websiteName = config('app.name');
        assert(is_string($websiteName));

        $this->assertEquals("Inquiry from {$name} via {$websiteName} Contact Form", $mailMessage->getHeaders()->get('subject')?->getBody());

        $recipients = $mailMessage->getHeaders()->get('to')?->getBody();
        $this->assertIsArray($recipients);
        $this->assertCount(1, $recipients);
        $recipient = $recipients[0];
        assert($recipient instanceof Address);
        $this->assertEquals('example@example.com', $recipient->getAddress());
        $this->assertEquals('Example Name', $recipient->getName());

        $textBody = $mailMessage->getTextBody();
        assert(is_string($textBody));
        $this->assertTrue(Str::containsAll($textBody, [
            "Name: {$name}",
            "Email: {$email}",
            "Phone: {$phone}",
            "Message: {$message}",
        ]));

        $htmlBody = $mailMessage->getHtmlBody();
        assert(is_string($htmlBody));
        $this->assertTrue(Str::containsAll($htmlBody, [
            "<b style=\"box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;\">Name:</b> {$name}",
            "<b style=\"box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;\">Email:</b> {$email}",
            "<b style=\"box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;\">Phone:</b> {$phone}",
            "<b style=\"box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;\">Message:</b> {$message}",
        ]));
    }

    public function test_it_adds_a_log_entry(): void
    {
        $contactFormSubmissionData = new ContactFormSubmissionData(
            name: $name = fake()->name,
            email: $email = fake()->safeEmail,
            phone: $phone = fake()->optional()->numerify('(###) ###-####') ?: '',
            message: $message = fake()->paragraph,
        );
        Notification::route('mail', ['example@example.com' => 'Example Name'])
            ->notify(new ContactFormSubmittedNotification($contactFormSubmissionData));

        $channelFake = Log::channel('notifications');
        assert($channelFake instanceof ChannelFake);

        $channelFake->assertLoggedTimes(
            fn (LogEntry $log) => $log->message === VarExporter::export([
                'notification_type' => ContactFormSubmittedNotification::class,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
            ]),
            1
        );
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Symfony\Component\Mailer\SentMessage>
     */
    protected function getMailMessages(): Collection
    {
        $mailer = app(Mailer::class);
        assert($mailer instanceof Mailer);

        $transport = $mailer->getSymfonyTransport();
        assert($transport instanceof ArrayTransport);

        return $transport->messages();
    }
}
