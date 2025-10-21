<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class EmployeeFormSmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contactData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['employees.index'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toNexmo($notifiable)
    // {
    //     return (new NexmoMessage)
    //         ->content("Hello {$this->contactData['name']}, your form has been submitted. Total: {$this->contactData['total_amount']}");
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Employee Reminder')
            ->line("Name: {$this->contactData->name}")
            ->line("Category: {$this->contactData->category}")
            ->line("Number: {$this->contactData->number}")
            ->line('This is a 20-minute reminder.');
    }

}

