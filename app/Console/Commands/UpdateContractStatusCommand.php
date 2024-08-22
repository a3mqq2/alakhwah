<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;

class UpdateContractStatusCommand extends Command
{
    protected $signature = 'contracts:update-status';
    protected $description = 'Update contract statuses where due <= 0';

    public function handle()
    {
        Contract::where('due', '<=', 0)->update(['contract_status' => 'مكتمل']);
        $this->info('Contract statuses updated successfully.');
    }
}
