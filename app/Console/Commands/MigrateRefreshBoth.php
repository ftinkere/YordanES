<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateRefreshBoth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:refresh-both {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate both databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Чистим бдшки
        $this->call('db:wipe', [
            '--database' => 'mongodb',
        ]);
        $this->call('db:wipe', [
            '--database' => 'mysql',
        ]);

        // Мигрируем их
        $this->call('migrate');

        // Если передан флаг --seed, запускаем сидеры
        if ($this->option('seed')) {
            $this->call('db:seed');
        }

        $this->info('Migrations refreshed for both databases.');
    }
}
