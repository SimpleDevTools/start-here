<?php

namespace App\Data;

class ContactFormSubmissionData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $message,
    ) {
    }
}
