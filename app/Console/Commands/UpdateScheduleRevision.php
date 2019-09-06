<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Revisions;

class UpdateScheduleRevision extends Command
{
    protected $revisions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateScheduleRevision';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обнуляет старые и создаёт новые ревизии';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Revisions $revisions)
    {
        parent::__construct();
        $this->revisions = $revisions;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->revisions->updateScheduleRevision();
    }
}
