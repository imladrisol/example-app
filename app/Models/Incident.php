<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $id)
 * @method static create(array $data)
 */
class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_id',
        'client_id',
        'name',
        'reason',
        'occurred_at',
        'deadline',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'deadline' => 'datetime',
    ];
}
