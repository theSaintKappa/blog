<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
            ]);
    }
}
