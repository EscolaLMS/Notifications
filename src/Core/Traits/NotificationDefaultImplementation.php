<?php

namespace EscolaLms\Notifications\Core\Traits;

use EscolaLms\Notifications\Core\NotificationEmptyVariableSet;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait NotificationDefaultImplementation
{
    public static function templateVariablesClass(): string
    {
        return NotificationEmptyVariableSet::class;
    }

    public static function templateVariablesSetName(): string
    {
        return Str::snake(class_basename(static::class));
    }

    public static function availableVia(): array
    {
        return [
            'mail',
            'database',
            'broadcast'
        ];
    }

    public function toArray($notifiable, ?string $channel = null): array
    {
        return [
            'title' => $this->title($notifiable, $channel),
            'content' => $this->content($notifiable, $channel),
        ];
    }

    /**
     * @see \Illuminate\Notifications\Channels\MailChannel 
     * MailMessage is transformed to Email, using html template and css theme configurable in Laravel config/mail.php
     * Rendering html/markdown/css loaded from database would require writing custom MailChannel (or at least custom buildView and render methods)
     */
    public function toMail($notifiable): MailMessage
    {
        $template = EscolaLmsNotifications::findTemplateForNotification($this, 'mail');
        $message = (new MailMessage)
            ->subject($this->title($notifiable, 'mail'))
            ->line(new HtmlString($this->content($notifiable, 'mail')));
        if ($template->mail_markdown) {
            $message->template($template->mail_markdown);
        }
        if ($template->mail_theme) {
            $message->theme($template->mail_theme);
        }
        return $message;
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage($this->toArray($notifiable, 'database'));
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable, 'broadcast'));
    }

    public function title($notifiable, ?string $channel = null): string
    {
        $template = EscolaLmsNotifications::findTemplateForNotification($this, $channel);
        $title = ($template && $template->title_is_valid) ? $template->title : static::defaultTitleTemplate();

        return EscolaLmsNotifications::replaceNotificationVariables($this, $title, $notifiable);
    }

    public function content($notifiable, ?string $channel = null): string
    {
        $template = EscolaLmsNotifications::findTemplateForNotification($this, $channel);
        $content = ($template && $template->is_valid) ? $template->content : static::defaultContentTemplate();

        return EscolaLmsNotifications::replaceNotificationVariables($this, $content, $notifiable);
    }

    public function via($notifiable): array
    {
        return static::availableVia();
    }

    /**
     * For example, a mail with course certificate should pass Course model to retrieve template data
     *
     * @return array
     */
    public function additionalDataForVariables($notifiable): array
    {
        return [];
    }
}
