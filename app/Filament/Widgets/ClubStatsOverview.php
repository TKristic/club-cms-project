<?php

namespace App\Filament\Widgets;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\MembershipFee;
use App\Models\Player;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClubStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $paid    = (float) MembershipFee::where('status', 'placeno')->sum('amount');
        $unpaid  = (float) MembershipFee::where('status', '!=', 'placeno')->sum('amount');
        $total   = $paid + $unpaid;

        $fmt = fn (float $v) => number_format($v, 2, ',', '.') . ' €';

        return [
            Stat::make('Naplaćeno', $fmt($paid))
                ->description('Plaćene članarine')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Dugovanje', $fmt($unpaid))
                ->description('Nepodmirene članarine')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Ukupno očekivano', $fmt($total))
                ->description('Sve članarine')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('gray'),

            Stat::make('Broj igrača', Player::count())
                ->description('Ukupno registrirano')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Aktivnost foruma', ForumTopic::count() . ' tema')
                ->description(ForumPost::count() . ' poruka')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
        ];
    }
}