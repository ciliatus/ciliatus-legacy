<?php

namespace App\Repositories;

use App\ActionSequence;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\LogicalSensor;
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
     * @param Terrarium $scope
     */
    public function __construct(Terrarium $scope = null)
    {

        $this->scope = $scope ? : new Terrarium();

    }

    /**
     * @param Carbon $history_to
     * @param null $history_minutes
     * @return Terrarium
     */
    public function show(Carbon $history_to = null, $history_minutes = null)
    {
        $terrarium = $this->scope;

        if ($history_minutes != 0) {
            foreach (LogicalSensor::types() as $type) {
                $field = $type . '_history';
                $terrarium->$field = $terrarium->getSensorreadingsByType($type, false, $history_to, $history_minutes, true);
            }
        }

        /*
         * Find background files
         */
        $files = $terrarium->files()->with('properties')->get();
        $terrarium->default_background_filepath = null;
        foreach ($files as $f) {
            if ($f->property('generic', 'is_default_background', true) == true) {
                if (!is_null($f->thumb())) {
                    $terrarium->default_background_filepath = $f->thumb()->path_external();
                }
                else {
                    $terrarium->default_background_filepath = $f->path_external();
                }

                break;
            }
        }

        if (is_null($terrarium->default_background_filepath)) {
            foreach ($terrarium->animals as $a) {
                foreach ($a->files as $f) {
                    if ($f->property('generic', 'is_default_background', true) == true) {
                        if (!is_null($f->thumb())) {
                            $terrarium->default_background_filepath = $f->thumb()->path_external();
                        }
                        else {
                            $terrarium->default_background_filepath = $f->path_external();
                        }
                        break;
                    }
                }
                if (!is_null($terrarium->default_background_filepath)) {
                    break;
                }
            }
        }

        $terrarium->capabilities = $terrarium->capabilities();
        $terrarium->icon = $terrarium->icon();
        $terrarium->url = $terrarium->url();

        return $terrarium;
    }

}