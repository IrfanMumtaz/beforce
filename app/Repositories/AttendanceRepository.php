<?php

namespace App\Repositories;

use App\Models\Attendance;
use InfyOm\Generator\Common\BaseRepository;

class AttendanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Attendance::class;
    }
}
