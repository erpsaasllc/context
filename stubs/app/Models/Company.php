<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use ERPSAAS\Context\Events\CompanyCreated;
use ERPSAAS\Context\Events\CompanyDeleted;
use ERPSAAS\Context\Events\CompanyUpdated;
use ERPSAAS\Context\Company as ContextCompany;

class Company extends ContextCompany
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'personal_company' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'personal_company',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CompanyCreated::class,
        'updated' => CompanyUpdated::class,
        'deleted' => CompanyDeleted::class,
    ];
}
