<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LocalFiles\LocalFiles;

class AddListFilesToUpload extends Command
{
    protected $localFiles;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addListFilesToUpload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Забирает партию файлов из таблицы для проверки присутствия их в яндекс-диске, получения метаинформации, и добавление к очереди на отправку';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LocalFiles $localFiles)
    {
        parent::__construct();
        $this->localFiles = $localFiles;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->localFiles->addListFiles();
    }
}
