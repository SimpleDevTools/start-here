<?php

declare(strict_types=1);

use Lendable\ComposerLicenseChecker\LicenseConfigurationBuilder;

return (new LicenseConfigurationBuilder)
    ->addLicenses(
        'MIT',
        'BSD-2-Clause',
        'BSD-3-Clause',
        'Apache-2.0',
    )
    ->addAllowedPackage('livewire/flux')
    ->addAllowedPackage('livewire/flux-pro')
    ->build();
