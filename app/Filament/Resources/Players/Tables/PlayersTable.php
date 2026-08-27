<?php

namespace App\Filament\Resources\Players\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn::make('photo')->label('Foto')->disk('public')->circular(),
                ImageColumn::make('photo_blob_preview')
                    ->label('BLOB slika')
                    ->getStateUsing(fn ($record) => $record->photo_blob
                        ? route('player.photoBlob', $record)
                        : null)
                    ->circular(),
                TextColumn::make('full_name')->label('Ime')
                    ->state(fn (\App\Models\Player $record) => $record->full_name)
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('category.name')->label('Kategorija')->sortable(),
                TextColumn::make('position')->label('Pozicija'),
                TextColumn::make('jersey_number')->label('Br.')->sortable(),
                TextColumn::make('age')->label('Dob')
                    ->state(fn (\App\Models\Player $record) => $record->age),
                TextColumn::make('email')->label('E-mail')->searchable()->toggleable(),

            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategorija')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                
            ])
            ->headerActions([
                Action::make('exportXml')
                    ->label('Izvoz XML')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategorija')
                            ->options(Category::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $xml = app(\App\Services\PlayerXmlService::class)->export((int) $data['category_id']);
                        $cat = Category::find($data['category_id']);

                        return response()->streamDownload(
                            fn () => print($xml),
                            'igraci-' . \Illuminate\Support\Str::slug($cat->name) . '.xml',
                            ['Content-Type' => 'application/xml']
                        );
                    }),

                Action::make('importXml')
                    ->label('Uvoz XML')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('gray')
                    ->schema([
                        Select::make('category_id')
                            ->label('Uvezi u kategoriju')
                            ->options(Category::pluck('name', 'id'))
                            ->required(),
                        FileUpload::make('file')
                            ->label('XML datoteka')
                            ->acceptedFileTypes(['application/xml', 'text/xml'])
                            ->storeFiles(false)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $upload = $data['file'];
                        $file = is_array($upload) ? reset($upload) : $upload;

                        $xml = $file->get();
                        $count = app(\App\Services\PlayerXmlService::class)->import($xml, (int) $data['category_id']);

                        Notification::make()
                            ->title("Uvezeno igrača: {$count}")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
