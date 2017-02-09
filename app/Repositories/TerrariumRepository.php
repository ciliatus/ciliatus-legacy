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

    /**
     * @param null $history_to
     * @param null $history_minutes
     * @return Terrarium
     */
    public function show($history_to = null, $history_minutes = null)
    {
        $terrarium = $this->scope;

        if (is_null($history_to))
            $history_to = Carbon::now();

        if (is_null($history_minutes))
            $history_minutes = 0;

        if ($history_minutes != 0) {
            /*
             * load temperature values and convert them to an array seperated by commata
             */
            $temperature_values = array_column($terrarium->getSensorReadingsTemperature($history_minutes, $history_to)->toArray(), 'avg_rawvalue');
            $terrarium->temperature_history = implode(',',
                array_map(
                    function($val) {
                        return round($val, 1);
                    },
                    $temperature_values
                )
            );

            /*
             * load humidity values and convert them to an array seperated by commata
             */
            $humidity_values = array_column($terrarium->getSensorReadingsHumidity($history_minutes, $history_to)->toArray(), 'avg_rawvalue');
            $terrarium->humidity_history = implode(',',
                array_map(
                    function($val) {
                        return round($val, 1);
                    },
                    $humidity_values
                )
            );
        }

        /*
         * Find background files
         */
        $files = $terrarium->files()->with('properties')->get();
        $terrarium->default_background_filepath = null;
        foreach ($files as $f) {
            if ($f->property('is_default_background') == true) {
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
                    if ($f->property('is_default_background') == true) {
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

        $terrarium->icon = $terrarium->icon();
        $terrarium->url = $terrarium->url();

        return $terrarium;
    }

}