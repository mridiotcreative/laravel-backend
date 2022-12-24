<?php

namespace App\Mail;

use App\Helpers\AppHelper;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $status;
    public $text;

    public function __construct($status, $text = '')
    {
        $this->status = $status;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->status == AppHelper::ACTIVE['status_code']) {
            return $this->view('frontend.email.account_verified');
        } elseif ($this->status == AppHelper::PENDING['status_code']) {
            return $this->view('frontend.email.account_under_verify');
        } elseif ($this->status == AppHelper::DECLINED['status_code']) {
            return $this->view('frontend.email.acount_declined')->with(['text' => $this->text]);
        } else {
            return $this->view('frontend.email.account_inactive');
        }
    }
}
