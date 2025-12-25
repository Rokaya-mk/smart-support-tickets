<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket Information')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->columns(2),

                        Select::make('user_id')
                            ->label('Created By')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->columns(2),

                        Select::make('assigned_to')
                            ->label('Assigned Agent')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->nullable()
                             ->visible(fn () => auth()->user()->role === 'admin')
                            ,

                        Select::make('status')
                            ->options([
                                'open'    => 'Open',
                                'pending' => 'Pending',
                                'closed'  => 'Closed',
                            ])
                            ->required(),

                        Select::make('priority')
                            ->options([
                                'low'    => 'Low',
                                'medium' => 'Medium',
                                'high'   => 'High',
                            ])
                            ->required(),
                            ]),
                 Section::make('Initial Message')
                    ->visibleOn('create')
                    ->schema([
                        Textarea::make('initial_message')
                            ->label('Message')
                            ->rows(4)
                            ->required()
                            ->dehydrated(false),
                    ]),
                    
            ]);
    }
}
