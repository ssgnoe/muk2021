<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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

    public function toSlack()
    {
        $message = "Die sieben Tage Inzidenz im Landkreis *{$this->coronazahlen->county}* liegt bei *".round($this->coronazahlen->cases7_per_100k)."*";
        return (new SlackMessage)->content($message);
    }
}
