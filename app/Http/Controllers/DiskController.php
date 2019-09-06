<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DiskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    public function getInfoAllFiles()
    {
        //$client = new Client();
        //$url ='https://cloud-api.yandex.net/v1/disk/resources';//Получить метаинформацию о файле или каталоге
        $url ='https://cloud-api.yandex.net/v1/disk/resources/files';//Получить метаинформацию о файле или каталоге
        $url ='https://cloud-api.yandex.net/v1/disk';//
        $client = new Client(
            ['base_uri' =>$url,
            'rpc_error'=>true,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'OAuth AgAAAAAL5frgAAXIBC7_7oGOpk0-jBaSCvxdKCQ',
                ],
            'timeout' => 30
            ]

        );
       $url ='https://cloud-api.yandex.net/v1/disk/resources/files';//Получить метаинформацию о файле или каталоге
       // dd($client);
       // $res = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        $res = $client->request('GET', $url, [
           // 'query' => ['path'=>'foto']
              'query' => ['limit'=>'10',
                          'fields'=>'items.md5, items.path',
                          'media_type'=>'image',
                          'offset'=>'100'
                         ]
        ]);
        //dd($res);
        echo $res->getStatusCode();
        //echo $res->getHeaderLine('content-type');
        echo $res->getBody();
        dd(48);
        dd($res->getBody());

        $info = 123;
        return view('diskInfo', ['info'=>$info]);
    }
}

