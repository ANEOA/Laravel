<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'my:install';

    protected $description = 'Installation';

    public function handle()
    {
        $this->call('storage:link');
        $this->call('migrate');

        return self::SUCCESS;
    }
}
