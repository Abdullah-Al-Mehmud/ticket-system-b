<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'category_id',
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
        return $this->hasMany(TicketCategory::class)->orderBy('id', 'desc');
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, TicketCategory::class, 'event_id', 'ticket_category_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
