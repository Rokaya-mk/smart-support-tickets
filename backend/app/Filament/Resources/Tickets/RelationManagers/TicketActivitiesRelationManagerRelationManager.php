<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketActivitiesRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';
    protected static ?string $title = 'Activity Log';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('activities')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('activities')
            ->columns([
                TextColumn::make('activity_type')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'status_changed',
                        'info' => 'assigned',
                        'gray' => 'message_sent',
                    ]),

                TextColumn::make('description')
                    ->label('Activity')
                    ->wrap(),

                TextColumn::make('created_at')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
