<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Attendance extends Model
{

    public $table = 'attendance';
    


    public $fillable = [
        'undefined',
        'date',
        'empid',
        'startTime',
        'StartImage',
        'EndTime',
        'EndImage',
        'break',
        'namaz',
	'created_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empid' => 'integer',
        'StartImage' => 'string',
        'EndImage' => 'string',
        'break' => 'string',
        'namaz' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
}
