<?php

namespace ERPSAAS\Context\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use ERPSAAS\Context\HasProfilePhoto;
use ERPSAAS\Context\HasCompanies;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasProfilePhoto;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
