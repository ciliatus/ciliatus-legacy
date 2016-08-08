<?php

namespace App\Http\Controllers;

use App\CriticalState;
use App\Http\Transformers\CriticalStateTransformer;
use App\LogicalSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class CriticalStateController
 * @package App\Http\Controllers
 */
class CriticalStateController extends ApiController
{

    /**
     * @var CriticalStateTransformer
     */
    protected $critical_stateTransformer;

    /**
     * CriticalStateController constructor.
     * @param CriticalStateTransformer $_critical_stateTransformer
     */
    public function __construct(CriticalStateTransformer $_critical_stateTransformer)
    {
        parent::__construct();
        $this->critical_stateTransformer = $_critical_stateTransformer;
    }
    
    /**
     *
     */
    public function evaluate()
    {
        $response = [
            'created'   => [],
            'notified'  => [],
            'deleted'   => 0,
            'processed' => 0
        ];

        /*
         * Evaluate LogicalSensor states
         * and create CriticalStates for
         * critical sensors
         */
        foreach (LogicalSensor::get() as $ls) {
            if (!$ls->stateOk()) {
                $existing_cs = CriticalState::where('belongsTo_type', 'LogicalSensor')
                                            ->where('belongsTo_id', $ls->id)
                                            ->whereNull('recovered_at')
                                            ->get();
                if ($existing_cs->count() < 1) {
                    $new_cs = CriticalState::create([
                        'belongsTo_type' => 'LogicalSensor',
                        'belongsTo_id'   => $ls->id
                    ]);

                    $response['created'][] = $this->critical_stateTransformer->transform($new_cs->toArray());
                }
                else {
                    foreach ($existing_cs as $cs) {
                        if ($cs->created_at->diffInMinutes(Carbon::now()) > $ls->soft_state_duration_minutes
                            && is_null($cs->notifications_sent_at)) {

                            $cs->is_soft_state = false;
                            $cs->notify();
                            $cs->save(['silent']);

                            $response['notified'][] = $this->critical_stateTransformer->transform($cs->toArray());
                        }
                    }
                }
            }
        }

        /*
         * Evaluate active CriticalStates
         * and recover them in case they are stateOk
         *
         * Delete them in case their belonging
         * doest not exist
         */
        foreach (CriticalState::whereNull('recovered_at')->get() as $cs) {
            $response['processed']++;
            if (!is_null($cs->belongsTo_type) && !is_null($cs->belongsTo_id)) {
                try {
                    $cs_belongs = ('App\\' . $cs->belongsTo_type)::find($cs->belongsTo_id);
                }
                catch (FatalThrowableError $ex) {
                    $cs->delete();
                    $response['deleted']++;
                }

                if (is_null($cs_belongs)) {
                    $cs->delete();
                    $response['deleted']++;
                }

                if ($cs_belongs->stateOk()) {
                    $cs->recover();
                    $response['deleted']++;
                }
            }
        }

        return $this->respondWithData($response);
    }

}
