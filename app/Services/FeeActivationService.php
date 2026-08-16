<?php

namespace App\Services;

use App\Mail\MembershipFeeInvoiceMail;
use App\Models\FeeGroup;
use App\Models\MembershipFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FeeActivationService
{
    public function __construct(
        protected InvoiceService $invoices,
        protected ClubMailer $mailer,
    ) {}

    /**
     * Aktivira jednu grupu za zadani mjesec (period "YYYY-MM").
     * Vraća sažetak: kreirano, preskočeno, poslano, greške.
     */
    public function activateGroup(FeeGroup $group, ?Carbon $date = null): array
    {
        $date   = $date ?? now();
        $period = $date->format('Y-m');          // npr. "2026-08"
        $season = $this->seasonFor($date);

        $summary = ['created' => 0, 'skipped' => 0, 'mailed' => 0, 'errors' => []];

        if (! $group->isActive()) {
            $summary['errors'][] = "Grupa '{$group->name}' je suspendirana — preskočena.";
            return $summary;
        }

        $group->loadMissing('players', 'club');

        foreach ($group->players as $player) {
            // ZAŠTITA OD DUPLIKATA: isti igrač + grupa + mjesec
            $exists = MembershipFee::where('fee_group_id', $group->id)
                ->where('player_id', $player->id)
                ->where('period', $period)
                ->exists();

            if ($exists) {
                $summary['skipped']++;
                continue;
            }

            $amount = $group->amountForPlayer($player);

            // 1) članarina
            $fee = MembershipFee::create([
                'club_id'       => $group->club_id,
                'fee_group_id'  => $group->id,
                'player_id'     => $player->id,
                'season'        => $season,
                'period'        => $period,
                'amount'        => $amount,
                'due_date'      => $date->copy()->addDays(15),
                'status'        => 'nepodmireno',
            ]);
            $summary['created']++;

            // 2) uplatnica (PDF + HUB-3A)
            try {
                $invoice = $this->invoices->generateForFee($fee);
            } catch (\Throwable $e) {
                $summary['errors'][] = "Uplatnica za {$player->first_name} {$player->last_name}: {$e->getMessage()}";
                continue;
            }

            // 3) mail (email je obavezan, ali za svaki slučaj provjeri)
            if (empty($player->email)) {
                $summary['errors'][] = "Igrač {$player->first_name} {$player->last_name} nema e-mail — mail preskočen.";
                continue;
            }

            try {
                $this->mailer->send($player->email, new MembershipFeeInvoiceMail($invoice));
                $summary['mailed']++;
            } catch (\Throwable $e) {
                $summary['errors'][] = "Mail za {$player->email}: {$e->getMessage()}";
                Log::error('Fee mail failed', ['player' => $player->id, 'error' => $e->getMessage()]);
            }
        }

        return $summary;
    }

    /** Aktivira sve grupe kojima je danas dan naplate. */
    public function activateDue(?Carbon $date = null): array
    {
        $date = $date ?? now();
        $results = [];

        $groups = FeeGroup::where('status', 'aktivna')
            ->where('billing_day', $date->day)
            ->get();

        foreach ($groups as $group) {
            $results[$group->name] = $this->activateGroup($group, $date);
        }

        return $results;
    }

    protected function seasonFor(Carbon $date): string
    {
        // sezona npr. "2025/2026" — počinje u srpnju
        $year = $date->month >= 7 ? $date->year : $date->year - 1;
        return $year . '/' . ($year + 1);
    }
}