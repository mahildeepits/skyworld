<?php

namespace App\Console\Commands;

use App\Services\IncomeService;
use Illuminate\Console\Command;

class SettleBonuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settle:bonuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will settle all the bonuses monthly.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Bonuses settling...');
        (new IncomeService)->settleAllBonuses();
        $this->line('Bonuses settled...');
    }
}
