<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.forgot-password')
            ->subject('Notifikasi Mengganti Password')
            ->with([
                'id' => $this->data['id'],
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'link' => $this->data['link'],
            ]);
    }
}
