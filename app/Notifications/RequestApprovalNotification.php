<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;


class RequestApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $actionDetails;
    public $requester;


    public function __construct($actionDetails, $requester)
    {
        //
        $this->actionDetails = $actionDetails;
        $this->requester = $requester;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // public function via($notifiable)
    // {
    //     return ['mail'];
    // }
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
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toDatabase($notifiable)
    {
        return [
            'action' => $this->actionDetails,
            'requester' => $this->requester->name,
            'requester_id' => $this->requester->id,
            'url' => route('approval.show', $this->actionDetails['id']) // URL untuk detail approval
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


     // Mengirimkan notifikasi ke banyak user berdasarkan role
    public function sendNotificationToUsers()
    {
        // Menentukan siapa yang perlu menerima notifikasi
        $users = User::whereHas('roles', function ($query) {
            // Misalnya, mencari user dengan role 'admin' atau 'manager'
            $query->whereIn('name', ['admin', 'manager']);
        })->get();

        // Mengirimkan notifikasi ke semua user yang dipilih
        foreach ($users as $user) {
            // Menggunakan notifikasi polymorphic
            $user->notify(new self($this->actionDetails, $this->requester));
        }
    }
}
