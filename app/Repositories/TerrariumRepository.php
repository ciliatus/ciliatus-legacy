<?php

namespace App\Repositories;

use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Terrarium;
use Cache;
use Carbon\Carbon;
use DB;
use App\Sensorreading;

/**
 * Class TerrariumRepository
 * @package App\Repositories
 */
class TerrariumRepository extends Repository
{


    /**
     * TerrariumRepository constructor.
     * @param null $scope
     */
    public function __construct(Terrarium $scope = null)
    {

        $this->scope = $scope ? : new Terrarium();

    }

    public function show($history_to = null, $history_minutes = null)
    {
        $terrarium = $this->scope;

        /*
         * Find background files
         */
        $files = $terrarium->files()->with('properties')->get();
        $terrarium->default_background_filepath = null;
        foreach ($files as $f) {
            if ($f->property('is_default_background') == true) {
                $terrarium->default_background_filepath = $f->path_external();
                break;
            }
        }

        if (is_null($terrarium->default_background_filepath)) {
            foreach ($terrarium->animals as $a) {
                foreach ($a->files as $f) {
                    if ($f->property('is_default_background') == true) {
                        $terrarium->default_background_filepath = $f->path_external();
                        break;
                    }
                }
                if (!is_null($terrarium->default_background_filepath)) {
                    break;
                }
            }
        }

        $terrarium->icon = $terrarium->icon();
        $terrarium->url = $terrarium->url();

        return $terrarium;
    }

}