<?php

namespace App\Http\Controllers\Api;

use App\ReminderEvent;
use App\Events\ReminderDeleted;
use App\Events\ReminderUpdated;
use App\Http\Transformers\ReminderTransformer;
use App\Repositories\ReminderRepository;
use Illuminate\Http\Request;
use Gate;

/**
 * Class ReminderController
 * @package App\Http\Controllers\Api
 */
class ReminderController extends ApiController
{

    /**
     * @var ReminderTransformer
     */
    protected $reminderTransformer;


    /**
     * ReminderController constructor.
     * @param ReminderTransformer $_reminderTransformer
     */
    public function __construct(ReminderTransformer $_reminderTransformer)
    {
        parent::__construct();
        $this->reminderTransformer = $_reminderTransformer;
    }


    /**
     * @param Request $request
     * @param $belongsTo_type
     * @param $belongsTo_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $belongsTo_type = null, $belongsTo_id = null)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }


        if (!is_null($belongsTo_type) && !is_null($belongsTo_id)) {
            $belongsTo = ('App\\' . $belongsTo_type)::find($belongsTo_id);

            if (is_null($belongsTo)) {
                return $this->respondNotFound("$belongsTo_type not found");
            }

            $entries = $belongsTo->entries();
        }
        else {
            $entries = ReminderEvent::with('files');
        }

        $entries = $this->filter($request, $entries);

        return $this->respondTransformedAndPaginated(
            $request,
            $entries,
            $this->reminderTransformer,
            'ReminderRepository'
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('api-write:reminder')) {
            return $this->respondUnauthorized();
        }


        $belongsTo = $this->getBelongsTo($request);

        $e = ReminderEvent::create([
            'belongsTo_type' => $belongsTo['belongsTo_type'],
            'belongsTo_id' => $belongsTo['belongsTo_id'],
            'type' => 'Reminder',
            'name' => $request->input('name'),
            'value' => nl2br($request->input('value'))
        ]);

        broadcast(new ReminderUpdated($e));

        return $this->respondWithData([
            'id' => $e->id
        ], [
            'redirect' => [
                'uri' => url('reminders/' . $e->id . '/edit')
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $entry = ReminderEvent::find($id);

        if (!$entry) {
            return $this->respondNotFound('Entry not found');
        }

        $entry = (new ReminderRepository($entry))->show();

        return $this->respondWithData(
            $this->reminderTransformer->transform(
                $entry->toArray()
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:reminder')) {
            return $this->respondUnauthorized();
        }

        $e = ReminderEvent::find($id);
        if (is_null($e)) {
            return $this->respondNotFound();
        }

        $this->updateModelProperties($e, $request, [
            'name', 'value'
        ]);
        $e->save();

        broadcast(new ReminderUpdated($e));

        return $this->respondWithData([
            'id' => $e->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('api-write:reminder')) {
            return $this->respondUnauthorized();
        }

        $e = ReminderEvent::find($id);
        if (is_null($e)) {
            return $this->respondNotFound();
        }

        broadcast(new ReminderDeleted($e));

        $e->delete();

        return $this->respondWithData([]);
    }

}