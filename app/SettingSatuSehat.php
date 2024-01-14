<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingSatuSehat extends Model
{
    use SoftDeletes;


    protected $fillable = ['mode', 'oauth_url', 'base_url', 'consent_url', 'kfa_url', 'kfav2_url'];

    protected $hidden = ['id'];
}
