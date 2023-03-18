<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminUserResource\Pages;
use App\Filament\Resources\AdminUserResource\Pages\CreateAdminUser;
use App\Models\AdminUser;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentPasswordReveal\Password;

class AdminUserResource extends Resource
{
    protected static ?string $model = AdminUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Password::make('password')
                    ->generatable()
                    ->revealable()
                    ->copyable()
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof CreateAdminUser)
                    ->confirmed()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => ! empty($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                Password::make('password_confirmation')
                    ->revealable()
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    /**
     * @return array<class-string<\Filament\Resources\RelationManagers\RelationManager>>
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminUsers::route('/'),
            'create' => Pages\CreateAdminUser::route('/create'),
            'edit' => Pages\EditAdminUser::route('/{record}/edit'),
        ];
    }
}
