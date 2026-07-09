<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_chat_id',
        'sender_type',
        'sender_id',
        'content',
        'image_url',
        'video_url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function adminChat(): BelongsTo
    {
        return $this->belongsTo(AdminChat::class);
    }
}