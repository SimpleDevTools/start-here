<?php

declare(strict_types=1);

use App\Concerns\LogsModelActivity;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\TwoFactorAuthenticatable;

it('returns correct unique ids', function () {
    expect((new User)->uniqueIds())->toBe(['uuid']);
});

it('has uuid column on the users table', function () {
    expect((new User)->getTable())->toBe('users');
    expect(Schema::hasColumn('users', 'uuid'))->toBeTrue();
});

it('uses required traits', function () {
    expect(User::class)->toUseTraits([
        HasFactory::class,
        HasUuids::class,
        LogsModelActivity::class,
        Notifiable::class,
        TwoFactorAuthenticatable::class,
    ]);
});

it('has guarded attributes', function () {
    expect((new User)->getGuarded())->toBe([
        'id',
        'created_at',
        'updated_at',
        'uuid',
    ]);
});

it('generates a uuid when created', function () {
    $user = User::factory()->create();

    expect($user->uuid)->not->toBeNull();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'uuid' => $user->uuid,
    ]);
});
