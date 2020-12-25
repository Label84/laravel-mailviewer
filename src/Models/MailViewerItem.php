<?php

namespace Label84\MailViewer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property string $body
 * @property array $recipients
 */
class MailViewerItem extends Model
{
    public static function booted(): void
    {
        static::creating(function (self $mailViewerItem) {
            $mailViewerItem->uuid = Str::uuid();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sent_at', 'desc');
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [
        'uuid',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'headers' => 'array',
        'recipients' => 'array',
    ];

    public function getRecipientsAttribute(string $value): ?Collection
    {
        return collect(json_decode($value));
    }

    public function setRecipientsAttribute(array $value): void
    {
        $this->attributes['recipients'] = json_encode($value);
    }

    public function getToRecipientsAttribute(): Collection
    {
        return collect($this->recipients['to'] ?? []);
    }

    public function getCcRecipientsAttribute(): Collection
    {
        return collect($this->recipients['cc'] ?? []);
    }

    public function getBccRecipientsAttribute(): Collection
    {
        return collect($this->recipients['bcc'] ?? []);
    }
}
