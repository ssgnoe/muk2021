<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class CoronaNews extends Notification
{
    use Queueable;


    public $coronazahlen;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($coronazahlen)
    {
        $this->coronazahlen = $coronazahlen;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSlack() {
        $message = "Die sieben Tage Inzidenz im Landkreis *{$this->coronazahlen->county}* "
                    ."liegt bei *{$this->coronazahlen->cases7_per_100k}*";
        return (new SlackMessage)->content($message);
    }
}
