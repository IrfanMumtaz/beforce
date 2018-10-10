<?php

namespace App\Repositories;

use App\Models\EBStag;
use InfyOm\Generator\Common\BaseRepository;

class EBStagRepository extends BaseRepository
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
        return EBStag::class;
    }
}
