<?php

namespace App\Repositories;

use App\Models\Brands;
use InfyOm\Generator\Common\BaseRepository;

class BrandsRepository extends BaseRepository
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
        return Brands::class;
    }
}
