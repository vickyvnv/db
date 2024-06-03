<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbInstance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['prod', 'preprod', 'market_id'];

    /**
     * Get the market that owns the db instance.
     */
    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
