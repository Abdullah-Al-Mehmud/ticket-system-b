<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

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
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueKey(10);
        });
    }
    protected static function generateUniqueKey($length): string
    {
        $characters = "ABCDEFGHOPQRSTUYZ0123456IJKLMN789VWX";
        $key = "EVT_";

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }
        // Ensure the key is unique
        while (static::where('id', $key)->exists()) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $key;
    }

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
