<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Revisions;

class UpdateListFiles extends Command
{
    protected $revisions;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listFilesUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет списки файлов с яндекс-диска и внутри синхронизируемой папки';

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
        $this->revisions->updateListFiles();
    }
}
