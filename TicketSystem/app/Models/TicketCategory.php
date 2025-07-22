<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'sales_start',
        'sales_end',
        'total_quantity',
        'sold_quantity',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_categories_id');
    }
}
