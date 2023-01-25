<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        'ticket',
        'user_id',
        'category_id',
        'status_id',
        'subject',
        'description',
    ];

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }

    public function status(): object
    {
        return $this->hasOne(Status::class);
    }
}
