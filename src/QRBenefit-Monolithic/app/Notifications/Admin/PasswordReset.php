<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
class PasswordReset extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     *
     */
    public $url, $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url, $user)
    {
        $this->url = $url;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (file_exists( resource_path() . '/views/email/store/'. $this->user->store->code()) && !empty($this->user->store->code())) {
            $template_url = 'email.store.'. $this->user->store->code() .'.reset_password';
        } else {
            $template_url = 'email.store.common.reset_password';
        }
        return (new MailMessage)
            ->subject(trans('passwords.email_subject'))
            ->view($template_url,[
                'firstName' => head(explode(' ', trim($this->user->name))),
                'url' => $this->url,
            ]);
    }
}
