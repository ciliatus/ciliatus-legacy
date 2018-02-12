<?php

namespace App\Http\Controllers\Api;

use App\BiographyEntryEvent;
use App\CiliatusModel;
use App\Events\BiographyEntryEventDeleted;
use App\Events\BiographyEntryEventUpdated;
use App\Property;
use Gate;
use Illuminate\Http\Request;

/**
 * Class BiographyEntryController
 * @package App\Http\Controllers\Api
 */
class BiographyEntryEventController extends ApiController
{

    /**
     * BiographyEntryEventController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @param $belongsTo_type
     * @param $belongsTo_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request, $belongsTo_type = null, $belongsTo_id = null)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }


        if (!is_null($belongsTo_type) && !empty($belongsTo_type) && !is_null($belongsTo_id)) {
            $belongsTo = ('App\\' . $belongsTo_type)::find($belongsTo_id);

            if (is_null($belongsTo)) {
                return $this->respondNotFound("$belongsTo_type not found");
            }

            $entries = $belongsTo->entries();
        }
        else {
            $entries = BiographyEntryEvent::with('files');
        }

        $entries = $this->filter($request, $entries);

        return $this->respondTransformedAndPaginated(
            $request,
            $entries
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
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
        if (Gate::denies('api-write:biography_entry')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CiliatusModel $belongsTo
         */
        $belongsTo = $this->getBelongsTo($request);

        /**
         * @var BiographyEntryEvent $e
         */
        $e = BiographyEntryEvent::create([
            'belongsTo_type' => $belongsTo['belongsTo_type'],
            'belongsTo_id' => $belongsTo['belongsTo_id'],
            'type' => 'BiographyEntry',
            'name' => $request->input('title'),
            'value' => nl2br($request->input('text'))
        ]);

        Property::create([
            'belongsTo_type' => 'Event',
            'belongsTo_id' => $e->id,
            'type' => 'BiographyEntryCategory',
            'name' => $request->input('category')
        ]);

        broadcast(new BiographyEntryEventUpdated($e));

        return $this->respondWithData([
            'id' => $e->id
        ], [
            'redirect' => [
                'uri' => url('biography_entries/' . $e->id . '/edit')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:biography_entry')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var BiographyEntryEvent $e
         */
        $e = BiographyEntryEvent::find($id);
        if (is_null($e)) {
            return $this->respondNotFound();
        }

        if ($request->filled('category')) {
            $cat = $e->property('BiographyEntryCategory');
            if (is_null($cat)) {
                return $this->setStatusCode(422)->respondWithError('Category property not found');
            }

            $cat_type = Property::where('belongsTo_type', 'System')
                                ->where('type', 'BiographyEntryCategoryType')
                                ->where('name', $request->input('category'))
                                ->get()->first();
            if (is_null($cat_type)) {
                return $this->setStatusCode(422)->respondWithError('Category not found');
            }

            $this->updateModelProperties($cat, $request, [
                'name' => 'category'
            ]);
            $cat->save();
        }

        $this->updateModelProperties($e, $request, [
            'name' => 'title', 'value' => 'text'
        ]);
        $e->save();

        broadcast(new BiographyEntryEventUpdated($e));

        $belongsTo_object = $e->belongsTo_object();
        if (!is_null($belongsTo_object)) {
            $url = $belongsTo_object->url() . '#tab_biography';
        }

        return $this->respondWithData([
            'id' => $e->id
        ], [
            'redirect' => [
                'uri' => $url
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Gate::denies('api-write:biography_entry')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var BiographyEntryEvent $e
         */
        $e = BiographyEntryEvent::find($id);
        if (is_null($e)) {
            return $this->respondNotFound();
        }
        $cat = $e->property('BiographyEntryCategory');
        if (!is_null($cat)) {
            $cat->delete();
        }

        $belongsTo_object = $e->belongsTo_object();
        if (!is_null($belongsTo_object)) {
            $url = $belongsTo_object->url() . '#tab_biography';
        }

        broadcast(new BiographyEntryEventDeleted($e));

        $e->delete();

        return $this->respondWithData([], [
            'redirect' => [
                'uri' => $url
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $ft = Property::where('type', 'BiographyEntryCategoryType');

        $ft = $this->filter($request, $ft);

        $return = [];
        foreach ($ft->get() as $t) {
            $return[] = $t->name;
        }

        return $this->respondWithData($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_type(Request $request)
    {
        if (Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        if (!$this->checkInput(['name'], $request)) {
            return $this->setErrorCode(422)->respondWithError('Missing fields');
        }

        Property::create([
            'belongsTo_type' => 'System',
            'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
            'type' => 'BiographyEntryCategoryType',
            'name' => $request->input('name'),
            'value' => $request->input('icon')
        ]);

        return $this->respondWithData([],
            [
                'redirect' => [
                    'uri' => url('categories#tab_bio_categories')
                ]
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete_type(Request $request, $id)
    {
        if (Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Property $type
         */
        $type = Property::find($id);
        $type->delete();

        return $this->respondWithData([],
            [
                'redirect' => [
                    'uri' => url('categories#tab_bio_categories')
                ]
            ]);
    }

}