<?php

namespace App\Services;

use App\Models\Market;

class MarketService
{
    /**
     * Get all markets.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMarkets()
    {
        return Market::all();
    }
}