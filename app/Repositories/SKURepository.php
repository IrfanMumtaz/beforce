<?php

namespace App\Repositories;

use App\Models\SKU;
use InfyOm\Generator\Common\BaseRepository;

class SKURepository extends BaseRepository
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
        return SKU::class;
    }
}
