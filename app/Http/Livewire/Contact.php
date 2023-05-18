<?php

namespace App\Http\Livewire;

use App\Data\ContactFormSubmissionData;
use App\Notifications\ContactFormSubmittedNotification;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property \Filament\Forms\ComponentContainer $form
 */
class Contact extends Component implements HasForms
{
    use InteractsWithForms;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public bool $submittedSuccessfully = false;

    public function render(): View
    {
        return view('livewire.contact');
    }

    /**
     * @return array<\Filament\Forms\Components\Component>
     */
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(200),
            TextInput::make('email')
                ->required()
                ->maxLength(254)
                ->email(),
            TextInput::make('phone')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->length(10)
                ->maxLength(14)
                ->integer(),
            Textarea::make('message')
                ->required()
                ->maxLength(65_000),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $contactFormSubmittedNotification = new ContactFormSubmittedNotification(new ContactFormSubmissionData(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            message: $data['message'],
        ));

        Notification::route('mail', ['john.doe@example.com' => 'John Doe'])
            ->notify($contactFormSubmittedNotification);

        $this->reset();

        $this->submittedSuccessfully = true;

        FilamentNotification::make()
            ->title('Message sent successfully')
            ->success()
            ->send();
    }
}
