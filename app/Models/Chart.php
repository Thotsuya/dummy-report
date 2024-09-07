<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'chart_labels' => 'array',
        'chart_values' => 'array',
    ];

    public function report() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
