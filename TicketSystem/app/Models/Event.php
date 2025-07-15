<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'category',
        'event_description',
        'location',
        'start_date',
        'end_date',
        'ticket_price',
        'status',
        'privacy_policy',
        'image_url',
    ];


    public function organizer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
