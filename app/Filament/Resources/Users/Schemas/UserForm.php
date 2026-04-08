<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('username')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->options([
                        Role::Admin->value => 'Admin',
                        Role::Author->value => 'Author',
                        Role::User->value => 'User',
                    ])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->rule(Password::default())
                    ->label(fn (string $context) => $context === 'edit' ? 'New Password (leave blank to keep current)' : 'Password'),
            ]);
    }
}
