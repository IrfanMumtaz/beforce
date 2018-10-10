<?php

namespace App\Repositories;

use App\Models\Asset;
use InfyOm\Generator\Common\BaseRepository;

class AssetRepository extends BaseRepository
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
        return Asset::class;
    }
}
