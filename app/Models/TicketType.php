<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = ['name', 'description', 'status'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueKey(10);
        });
    }

    protected static function generateUniqueKey($length): string
    {
        $characters = 'ABCDEFGHOPQRSTUYZ0123456IJKLMN789VWX';
        $key = 'TITYPE_';

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }
        while (static::where('id', $key)->exists()) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }
}
