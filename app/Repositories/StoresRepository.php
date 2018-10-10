<?php

namespace App\Repositories;

use App\Models\Stores;
use InfyOm\Generator\Common\BaseRepository;

class StoresRepository extends BaseRepository
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
        return Stores::class;
    }
}
