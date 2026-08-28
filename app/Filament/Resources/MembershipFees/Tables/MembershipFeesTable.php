<?php

namespace App\Filament\Resources\MembershipFees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use App\Services\InvoiceService;

class MembershipFeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('player.last_name')->label('Igrač')
                    ->formatStateUsing(fn ($state, $record) => "{$record->player->first_name} {$record->player->last_name}")
                    ->searchable(),
                TextColumn::make('season')->label('Sezona')->sortable(),
                TextColumn::make('amount')->label('Iznos')->money('EUR')->sortable(),
                TextColumn::make('due_date')->label('Dospijeće')->date('d.m.Y.')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (string $state) => $state === 'placeno' ? 'success' : 'warning'),
            ])
            ->filters([
                SelectFilter::make('status')->options(['nepodmireno' => 'Nepodmireno', 'placeno' => 'Plaćeno']),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('invoice')
                ->label('Uplatnica')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->action(function ($record) {
                    $invoice = app(InvoiceService::class)->generateForFee($record);

                    \Filament\Notifications\Notification::make()
                        ->title('Uplatnica generirana')
                        ->body('Broj: ' . $invoice->invoice_number)
                        ->success()
                        ->send();
                }),
                Action::make('emailInvoice')
                    ->label('Pošalji mailom')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Slanje uplatnice')
                    ->modalDescription(fn ($record) => 'Uplatnica će biti poslana na: ' . ($record->player->email ?? 'nepoznato'))
                    ->action(function ($record) {
                        $email = $record->player->email ?? null;

                        if (! $email) {
                            \Filament\Notifications\Notification::make()
                                ->title('Igrač nema e-mail adresu')
                                ->danger()->send();
                            return;
                        }

                        try {
                            $invoice = app(\App\Services\InvoiceService::class)->generateForFee($record);
                            app(\App\Services\ClubMailer::class)->send(
                                $email,
                                new \App\Mail\MembershipFeeInvoiceMail($invoice),
                            );
                            \Filament\Notifications\Notification::make()
                                ->title('Uplatnica poslana na ' . $email)->success()->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Slanje nije uspjelo')->body($e->getMessage())->danger()->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
