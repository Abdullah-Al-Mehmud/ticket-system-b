<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_category_id',
        'quantity',
        'unit_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_categories_id');
    }

    public function event()
    {
        return $this->hasOneThrough(Event::class, TicketCategory::class, 'id', 'id', 'ticket_categories_id', 'event_id');
    }
}
