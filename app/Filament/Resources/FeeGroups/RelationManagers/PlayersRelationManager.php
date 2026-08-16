<?php

namespace App\Filament\Resources\FeeGroups\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Category;

class PlayersRelationManager extends RelationManager
{
    protected static string $relationship = 'players';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('amount_override')
                ->label('Individualni iznos (popust)')
                ->numeric()
                ->helperText('Ostavi prazno za zadani iznos grupe.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('last_name')
            ->columns([
                TextColumn::make('first_name')->label('Ime'),
                TextColumn::make('last_name')->label('Prezime'),
                TextColumn::make('email')->label('E-mail'),
                TextColumn::make('pivot.amount_override')->label('Individualni iznos')
                    ->money('EUR')
                    ->placeholder('zadani iznos'),
            ])
            ->headerActions([
                AttachAction::make()
                ->label('Dodaj igrača')
                ->recordSelect(fn ($select) => $select
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                    ->searchColumns(['first_name', 'last_name']))
                ->schema(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                    TextInput::make('amount_override')
                        ->label('Individualni iznos (popust)')
                        ->numeric()
                        ->helperText('Prazno = zadani iznos grupe.'),
                ]),
                Action::make('attachCategory')
                ->label('Dodaj cijelu kategoriju')
                ->icon('heroicon-o-user-group')
                ->color('gray')
                ->schema([
                    Select::make('category_id')
                        ->label('Kategorija')
                        ->options(Category::orderBy('sort_order')->pluck('name', 'id'))
                        ->required(),
                    TextInput::make('amount_override')
                        ->label('Iznos za sve (opcionalno)')
                        ->numeric()
                        ->helperText('Prazno = zadani iznos grupe za svakog.'),
                ])
                ->action(function (array $data) {
                    $group = $this->getOwnerRecord();

                    $players = \App\Models\Player::where('category_id', $data['category_id'])->get();

                    // preskoči one koji su već u grupi
                    $existing = $group->players()->pluck('players.id')->all();

                    $attached = 0;
                    foreach ($players as $player) {
                        if (in_array($player->id, $existing)) {
                            continue;
                        }
                        $group->players()->attach($player->id, [
                            'amount_override' => $data['amount_override'] ?? null,
                        ]);
                        $attached++;
                    }

                    \Filament\Notifications\Notification::make()
                        ->title("Dodano igrača: {$attached}")
                        ->success()
                        ->send();
                }),
            ])
            ->recordActions([
                EditAction::make()->label('Iznos'),
                DetachAction::make()->label('Ukloni'),
            ]);
    }
}