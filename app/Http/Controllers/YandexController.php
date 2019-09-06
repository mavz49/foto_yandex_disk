<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Revisions;
//use Storage;

class YandexController extends Controller
{

    public function __construct(Revisions $revisions)
    {
        $this->revisions = $revisions;
    }

    public function yandexUpdate(){
        $this->revisions->updateListFiles();
    }
}