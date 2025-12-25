<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use App\Services\TicketReplyService;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketMessagesRelationManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Conversations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('message')
                    ->required()
                    ->rows(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                TextColumn::make('user.name')
                    ->label('From')
                    ->sortable(),
                TextColumn::make('message')
                    ->wrap()
                    ->limit(200)
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')

            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                ->label('Reply')
                 ->action(function (array $data) {
                    app(TicketReplyService::class)
                        ->reply($this->getOwnerRecord(), $data['message']);
                })
                ->disabled(fn () => $this->getOwnerRecord()->status === 'closed')
                ->tooltip('This ticket is closed'),

                AttachAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                })
                ->after(function () {
                    $ticket = $this->getOwnerRecord();

                    if ($ticket->status === 'open') {
                        $ticket->update(['status' => 'pending']);
                    }
                }),
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
