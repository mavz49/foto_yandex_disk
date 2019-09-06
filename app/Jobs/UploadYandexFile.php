<?php

namespace App\Jobs;

use App\Services\LocalFiles\Foto ;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\YandexFiles\YandexFiles;

class UploadYandexFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $foto;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Foto $foto)
    {
        $this->foto =  $foto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $yandex = new YandexFiles;
        $yandex->uploadFile($this->foto);
        unset($yandex);
    }
}

