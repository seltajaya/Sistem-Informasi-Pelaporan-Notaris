<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'region_id',
        'report_month',
        'report_year',
        'jumlah_akta',
        'jumlah_disahkan',
        'jumlah_dibukukan',
        'jumlah_wasiat',
        'jumlah_protes',
        'file_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (Report $report) {
            if ($report->user?->region_id) {
                $report->region_id = $report->user->region_id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}