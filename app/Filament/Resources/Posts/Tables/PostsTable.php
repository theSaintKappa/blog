<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\Role;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()?->role !== Role::Admin) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->columns([
                ImageColumn::make('photo'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->badge()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->searchable(),
                TextColumn::make('tags.name')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
