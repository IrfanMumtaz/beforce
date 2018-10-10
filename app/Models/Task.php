<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use SoftDeletes;

    public $table = 'Task';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'emp_id',
        'assign_by',
        'Tasktype',
        'StartDate',
        'EndDate',
        'description',
        'Status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'emp_id' => 'interger',
        'Tasktype' => 'string',
        'shop_id' => 'interger'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'SelectEmployee' => 'required',
        'Tasktype' => 'required',
        'SelectStore' => 'required',
        'StartDate' => 'date',
        'EndDate' => 'date',
        'TaskDetails' => 'required'
    ];
}
