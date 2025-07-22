<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'event_description',
        'location',
        'start_date',
        'end_date',
        'privacy_policy',
        'image_url',
        'status',
    ];


    public function organizer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, TicketCategory::class, 'event_id', 'ticket_categories_id');
    }
}
