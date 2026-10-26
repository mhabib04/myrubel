<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Data milik salah satu website (unit): 'bimbel' (My Rubel), 'literacy' (Literacy Center), atau 'motivation' (Motivation & Outbound Center).
 */
trait HasUnit
{
    public function initializeHasUnit(): void
    {
        $this->mergeFillable(['unit']);
    }

    public function scopeUnit(Builder $query, string $unit): Builder
    {
        return $query->where($this->qualifyColumn('unit'), $unit);
    }
}
