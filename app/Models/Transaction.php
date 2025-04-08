<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payer_id',
        'payee_id',
        'value',
        'status',
        'notification_status',
        'notified_at'
    ];

    /**
     * Get the payer user
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Get the payee user
     */
    public function payee()
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'notified_at' => 'datetime'
    ];
}
