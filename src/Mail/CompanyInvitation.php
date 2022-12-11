<?php

namespace ERPSAAS\Context\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use ERPSAAS\Context\CompanyInvitation as CompanyInvitationModel;

class CompanyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The company invitation instance.
     *
     * @var \ERPSAAS\Context\CompanyInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \ERPSAAS\Context\CompanyInvitation  $invitation
     * @return void
     */
    public function __construct(CompanyInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('context::mail.company-invitation', ['acceptUrl' => URL::signedRoute('company-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('Company Invitation'));
    }
}
