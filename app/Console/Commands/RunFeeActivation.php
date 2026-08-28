<?php

namespace App\Console\Commands;

use App\Models\FeeGroup;
use App\Services\FeeActivationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RunFeeActivation extends Command
{
    protected $signature = 'fees:run
        {--group= : ID konkretne grupe (za test)}
        {--date= : Datum YYYY-MM-DD (za test, zadano danas)}';

    protected $description = 'Aktivira članarine: kreira uplatnice i šalje mailove.';

    public function handle(FeeActivationService $service): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now();

        if ($groupId = $this->option('group')) {
            $group = FeeGroup::find($groupId);
            if (! $group) {
                $this->error("Grupa {$groupId} ne postoji.");
                return self::FAILURE;
            }
            $this->info("Aktiviram grupu: {$group->name} ({$date->format('Y-m')})");
            $this->printSummary($group->name, $service->activateGroup($group, $date));
            return self::SUCCESS;
        }

        $this->info("Aktiviram grupe s danom naplate {$date->day} ({$date->format('Y-m')})");
        $results = $service->activateDue($date);

        if (empty($results)) {
            $this->warn('Nema grupa za naplatu danas.');
            return self::SUCCESS;
        }

        foreach ($results as $name => $summary) {
            $this->printSummary($name, $summary);
        }

        return self::SUCCESS;
    }

    protected function printSummary(string $name, array $s): void
    {
        $this->line("── {$name}");
        $this->line("   kreirano: {$s['created']} | preskočeno: {$s['skipped']} | mailova: {$s['mailed']}");
        foreach ($s['errors'] as $err) {
            $this->warn("   ! {$err}");
        }
    }
}