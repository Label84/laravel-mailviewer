<?php

namespace Label84\MailViewer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Label84\MailViewer\Tests\Database\Factories\MailViewerItemFactory;

/**
 * @property string $uuid
 * @property string $body
 * @property array $recipients
 */
class MailViewerItem extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('mailviewer.table_name', 'mail_viewer_items'));
        $this->setConnection(config('mailviewer.database_connection', 'mysql'));
    }

    public static function booted(): void
    {
        static::creating(function (self $mailViewerItem) {
            $mailViewerItem->uuid = Str::uuid();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sent_at', 'desc');
        });
    }

    protected static function newFactory(): MailViewerItemFactory
    {
        return MailViewerItemFactory::new();
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
