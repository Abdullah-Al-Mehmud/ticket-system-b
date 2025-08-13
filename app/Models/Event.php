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
        'is_featured',
        'status',
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function organizerPivots()
    {
        return $this->hasMany(EventOrganizer::class);
    }

    public function organizers()
    {
        return $this->belongsToMany(User::class, 'event_organizers');
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
