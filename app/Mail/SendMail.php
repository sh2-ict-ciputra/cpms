<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
// use App\User;
use Modules\User\Entities\User;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    // protected user = $user;
    public $user;
    // Data yang dilempar dari controller dibaca pada constructor ini
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    // Proses mengirimkan email ke tujuan ($user)
    public function build()
    {
        return $this->subject('Selamat datang di Kodehero!')
            ->view('mail.demo');
    }
}