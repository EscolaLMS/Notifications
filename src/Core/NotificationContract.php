<?php

namespace EscolaLms\Notifications\Core;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

interface NotificationContract
{
    public static function templateVariablesClass(): string;
    public static function templateVariablesSetName(): string;
    public static function availableVia(): array;

    public function via($notifiable): array;

    public function toArray($notifiable, ?string $channel = null): array;
    public function toMail($notifiable): MailMessage;
    public function toDatabase($notifiable): DatabaseMessage;
    public function toBroadcast($notifiable): BroadcastMessage;

    public function title($notifiable, ?string $channel = null): string;
    public function content($notifiable, ?string $channel = null): string;

    public function additionalDataForVariables(): array;
}
