<?php

namespace App\Filament\Pages;

use App\Services\SmtpSettingsService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class ManageSmtp extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'SMTP postavke';
    protected static ?string $title = 'SMTP postavke';
    protected string $view = 'filament.pages.manage-smtp';

    public ?array $data = [];

    public function mount(): void {
        $settings = app(SmtpSettingsService::class)->all(decrypt: true);
        $settings['password'] = '';
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('host')->label('SMTP server')->placeholder('smtp.gmail.com')->required(),
                TextInput::make('port')->label('Port')->numeric()->default(587)->required(),
                TextInput::make('username')->label('Korisničko ime (email)'),
                TextInput::make('password')->label('Lozinka')->password()
                    ->placeholder('ostavi prazno da ne mijenjaš'),
                Select::make('encryption')->label('Sigurnost')
                    ->options(['tls' => 'TLS (587)', 'ssl' => 'SSL (465)', '' => 'Bez'])
                    ->default(''),
                TextInput::make('from_address')->label('Pošiljatelj (email)')->email(),
                TextInput::make('from_name')->label('Naziv pošiljatelja')->placeholder('NK Primjer'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        app(SmtpSettingsService::class)->save($this->form->getState());

        Notification::make()->title('SMTP postavke spremljene')->success()->send();
    }
}