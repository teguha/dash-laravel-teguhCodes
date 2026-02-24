<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataCreateNotification extends Notification
{
    use Queueable;

    public $dataDetails;
    

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($dataDetails)
    {
        $this->dataDetails = $dataDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'action'   => 'Data Created',
            // 'data'     => $this->dataDetails,
            'data' => [
                'id' => $this->dataDetails['id'],
                'name' => $this->dataDetails['name'],
                'description' => $this->dataDetails['description'],
                'url' =>  $this->dataDetails['url'] , // kalau perlu
            ],

            // ✔ user yang menerima notifikasi
            'user'     => $notifiable->name,
            'user_id'  => $notifiable->id,
        ];
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
}
