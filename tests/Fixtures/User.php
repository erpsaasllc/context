<?php

namespace ERPSAAS\Context\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use ERPSAAS\Context\HasProfilePhoto;
use ERPSAAS\Context\HasCompanies;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasCompanies, HasProfilePhoto;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
