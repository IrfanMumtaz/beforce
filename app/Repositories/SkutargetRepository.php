<?php

namespace App\Repositories;

use App\Models\Skutarget;
use InfyOm\Generator\Common\BaseRepository;

class SkutargetRepository extends BaseRepository
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
        return Skutarget::class;
    }
}
