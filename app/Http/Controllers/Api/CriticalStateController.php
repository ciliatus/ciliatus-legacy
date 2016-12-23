<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\CriticalState;
use App\Events\ActionSequenceScheduleUpdated;
use App\Events\AnimalFeedingScheduleUpdated;
use App\Http\Transformers\CriticalStateTransformer;
use App\LogicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Gate;
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $critical_states = CriticalState::query();

        $critical_states = $this->filter($request, $critical_states);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->critical_stateTransformer->transformCollection(
                    $critical_states->get()->toArray()
                )
            );
        }

        $critical_states = $critical_states->paginate(env('PAGINATION_PER_PAGE', 20));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->critical_stateTransformer->transformCollection(
                $critical_states->toArray()['data']
            ),
            $critical_states
        );

    }
    
    /**
     *
     */
    public function evaluate()
    {

        if (Gate::denies('api-evaluate:critical_state')) {
            return $this->respondUnauthorized();
        }

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
                            $cs->save(['silent']);
                            $cs->notify();

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

        /*
         * Generate AnimalFeedingScheduleUpdated Events
         * to keep dashboard up to date
         */
         foreach (Animal::get() as $animal) {
            foreach ($animal->feeding_schedules as $afs) {
                broadcast(new AnimalFeedingScheduleUpdated($afs));
            }
        }

        /*
         * Generate ActionSequenceScheduleUpdated Events
         * to keep dashboard up to date
         */
        foreach (Terrarium::get() as $terrarium) {
            foreach ($terrarium->action_sequences as $as) {
                foreach ($as->schedules as $ass) {
                    broadcast(new ActionSequenceScheduleUpdated($ass));
                }
            }
        }

        return $this->respondWithData($response);
    }

}
