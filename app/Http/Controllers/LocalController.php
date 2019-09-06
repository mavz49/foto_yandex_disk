<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LocalFiles;
//use Storage;

class LocalController extends Controller
{

    public function __construct(LocalFiles $localFiles)
    {
        $this->localFiles = $localFiles;
    }

    public function localUpdate(){
      $this->localFiles->updateLocalDir();
    }
}

