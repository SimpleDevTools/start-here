<?php

namespace Tests\Feature;

use App\Http\Livewire\Contact;
use App\Notifications\ContactFormSubmittedNotification;
use App\Notifications\LogChannel;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Contact
 */
class ContactPageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_route_is_registered(): void
    {
        $this->assertEquals('/contact', route('contact', absolute: false));
        $this->assertEquals('/contact', action(Contact::class, absolute: false));
    }

    public function test_expected_view_is_returned(): void
    {
        $this
            ->get(route('contact'))
            ->assertSuccessful()
            ->assertSeeLivewire(Contact::class);
    }

    /**
     * @dataProvider validationRulesProvider
     */
    public function test_form_input_validation_rules(
        string $field,
        string $value,
        ?string $rule,
        bool $hasError = true
    ): void {
        $assertion = $hasError ? 'assertHasErrors' : 'assertHasNoErrors';

        $assertionArgument = $rule ? [$field => $rule] : [$field];

        $this->livewire()
            ->set($field, $value)
            ->call('submit')
            ->{$assertion}($assertionArgument);
    }

    public function test_submitting_the_contact_form_sends_the_notification(): void
    {
        $this->livewire()
            ->call('submit');

        Notification::assertSentOnDemandTimes(
            ContactFormSubmittedNotification::class,
            1
        );

        Notification::assertSentOnDemand(
            ContactFormSubmittedNotification::class,
            function (ContactFormSubmittedNotification $notification, array $channels, AnonymousNotifiable $notifiable) {
                $this->assertEquals([MailChannel::class, LogChannel::class], $channels);
                $this->assertEquals(['john.doe@example.com' => 'John Doe'], $notifiable->routeNotificationFor('mail'));

                return true;
            }
        );
    }

    public function test_submitting_the_contact_form_resets_the_form_data(): void
    {
        $livewire = $this->livewire()->set('phone', '5555555555');
        $this->assertNotEmpty($livewire->get('name'));
        $this->assertNotEmpty($livewire->get('email'));
        $this->assertNotEmpty($livewire->get('phone'));
        $this->assertNotEmpty($livewire->get('message'));

        $livewire->call('submit');
        $this->assertEmpty($livewire->get('name'));
        $this->assertEmpty($livewire->get('email'));
        $this->assertEmpty($livewire->get('phone'));
        $this->assertEmpty($livewire->get('message'));
    }

    public function test_submitting_the_contact_form_shows_thank_you_messages(): void
    {
        $livewire = $this->livewire();
        $this->assertFalse($livewire->get('submittedSuccessfully'));
        $livewire->assertDontSee('Your message was successfully sent. Thank you for getting in touch!');

        $livewire->call('submit');
        $this->assertTrue($livewire->get('submittedSuccessfully'));
        $livewire->assertSee('Your message was successfully sent. Thank you for getting in touch!');
        FilamentNotification::assertNotified(
            FilamentNotification::make()
                ->title('Message sent successfully')
                ->success()
        );
    }

    /**
     * @return array<array<string|bool|null>>
     */
    public static function validationRulesProvider(): array
    {
        return [
            ['name', 'John', null, false],
            ['name', '', 'required'],
            ['name', Str::random(201), 'max:200'],

            ['email', 'example@example.com', null, false],
            ['email', 'not_an_email', 'email'],
            ['email', '', 'required'],
            ['email', Str::random(255), 'max:254'],

            ['phone', '', null, false],
            ['phone', '', '5555555555', false],
            ['phone', '123456789', 'digits:10'],
            ['phone', '12345678911', 'digits:10'],
            ['phone', 'randomtext', 'numeric'],

            ['message', 'Message body', null, false],
            ['message', '', 'required'],
            ['message', Str::random(65_001), 'max:65000'],
        ];
    }

    protected function livewire(): TestableLivewire
    {
        return Livewire::test(Contact::class, [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'phone' => fake()->optional()->numerify('##########') ?: '',
            'message' => fake()->paragraph,
        ]);
    }
}
