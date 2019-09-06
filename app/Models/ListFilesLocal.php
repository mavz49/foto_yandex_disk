<?php

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class ListFilesLocal extends Model
    {
        //
        protected $table = 'list_files_local';
        protected $fillable = ['name'];

    }

}