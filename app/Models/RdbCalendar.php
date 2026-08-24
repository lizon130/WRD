<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RdbCalendar extends Model
{
    protected $table = 'rdb_calendars';

    protected $fillable = [
        'calendar_date',
        'section_type',
        'is_working_day',
        'reason',
    ];

    protected $casts = [
        'calendar_date' => 'date:Y-m-d',
        'is_working_day' => 'boolean',
    ];

    /**
     * Allowed section types (column groups in the RDB report)
     */
    const SECTION_RECEIVE = 'receive';
    const SECTION_DELIVERY = 'delivery';

    public static function allowedSections()
    {
        return [self::SECTION_RECEIVE, self::SECTION_DELIVERY];
    }
}
