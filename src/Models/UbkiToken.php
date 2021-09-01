<?php

namespace Arttiger\Ubki\Models;

use Illuminate\Database\Eloquent\Model;

class UbkiToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'error_code',
        'response',
        'account_login',
    ];
}
