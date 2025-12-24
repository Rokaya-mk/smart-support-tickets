<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // id comlumn
                TextColumn::make('id')
                          ->label('ID')
                          ->sortable(),
                
                TextColumn::make('subject')
                          ->label('Subject')
                          ->searchable()
                          ->limit(50)
                          ->tooltip(fn ($r) => $r->subject),

                TextColumn::make('user.name')
                            ->label('Created By')
                            ->searchable()
                            ->sortable(),
                
                TextColumn::make('agent.name')
                        ->label('Assigned To')
                        ->placeholder('—')
                        ->sortable(),

                TextColumn::make('status')
                            ->badge()
                            ->label('Status')
                            ->color(fn (string $st):string => match($st) {
                                'open' => 'success',
                                'pending' => 'warning',
                                'closed' => 'danger',
                                default => 'secondary',
                            }),

                TextColumn::make('priority')
                            ->badge()
                            ->label('Priority')
                            ->color(fn (string $st):string => match($st) {
                                'low' => 'success',
                                'medium' => 'warning',
                                'high' => 'danger',
                                default => 'secondary',
                            }),      
                            
                TextColumn::make('created_at')
                            ->label('Created At')
                            ->since()
                            ->sortable(),
                TextColumn::make('deleted_at')
                            ->label('Deleted At')
                            ->since()
                            ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
