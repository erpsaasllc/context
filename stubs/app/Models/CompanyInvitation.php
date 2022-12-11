<?php

namespace App\Models;

use ERPSAAS\Context\Context;
use ERPSAAS\Context\CompanyInvitation as ContextCompanyInvitation;

class CompanyInvitation extends ContextCompanyInvitation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the company that the invitation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Context::companyModel());
    }
}
