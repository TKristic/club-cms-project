<?php

namespace App\Filament\Resources\Enrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Category;
use App\Models\Club;
use App\Models\Player;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('child_first_name')->label('Dijete')
                    ->formatStateUsing(fn ($state, $record) => "{$record->child_first_name} {$record->child_last_name}")
                    ->searchable(['child_first_name', 'child_last_name']),
                \Filament\Tables\Columns\TextColumn::make('parent_name')->label('Roditelj')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('parent_phone')->label('Telefon'),
                \Filament\Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'odobreno' => 'success', 'odbijeno' => 'danger', default => 'warning',
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Zaprimljeno')->dateTime('d.m.Y.')->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(['novo' => 'Novo', 'odobreno' => 'Odobreno', 'odbijeno' => 'Odbijeno']),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('assignToTeam')
                    ->label('Smjesti u ekipu')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'odobreno')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategorija')
                            ->options(Category::orderBy('sort_order')->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('jersey_number')
                            ->label('Broj dresa')->numeric()->minValue(1)->maxValue(99),
                    ])
                    ->action(function (array $data, $record) {
                        Player::create([
                            'club_id'       => Club::value('id') ?? 1,
                            'category_id'   => $data['category_id'],
                            'first_name'    => $record->child_first_name,
                            'last_name'     => $record->child_last_name,
                            'email'         => $record->parent_email,   // ← prenosimo mail roditelja
                            'birth_date'    => $record->child_birth_date,
                            'jersey_number' => $data['jersey_number'] ?? null,
                        ]);

                        $record->update(['status' => 'odobreno']);

                        \Filament\Notifications\Notification::make()
                            ->title('Igrač je dodan u ekipu')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
