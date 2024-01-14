<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeySatuSehat extends Model
{
    use  SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['id'];
}
