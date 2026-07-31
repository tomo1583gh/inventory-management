<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    protected $fillable = [
        'item_id',
        'type',
        'qty',
        'note',
        'acted_at',
        'user_id',
        'corrected_log_id',
        'correction_reason',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'acted_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function correctedLog()
    {
        return $this->belongsTo(
            StockLog::class,
            'corrected_log_id'
        );
    }

    public function correctionLog()
    {
        return $this->hasOne(
            StockLog::class,
            'corrected_log_id'
        );
    }
}
