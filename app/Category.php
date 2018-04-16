<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //


    protected  $table='categorise';

    public  function posts(){
        $this->hasMany('App\Post');


    }
}
