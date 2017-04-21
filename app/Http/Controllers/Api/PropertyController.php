<?php

namespace App\Http\Controllers\Api;

use App\Events\ReadFlagSet;
use App\Property;
use App\Http\Transformers\PropertyTransformer;
use Cache;
use Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


/**
 * Class PropertyController
 * @package App\Http\Controllers
 */
class PropertyController extends ApiController
{
    /**
     * @var PropertyTransformer
     */
    protected $propertyTransformer;

    /**
     * PropertyController constructor.
     * @param PropertyTransformer $_propertyTransformer
     */
    public function __construct(PropertyTransformer $_propertyTransformer)
    {
        parent::__construct();
        $this->propertyTransformer = $_propertyTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $properties = Property::query();

        $properties = $this->filter($request, $properties);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->propertyTransformer->transformCollection(
                    $properties->orderBy('name')->get()->toArray()
                )
            );
        }

        $properties = $properties->orderBy('name')->paginate(env('PAGINATION_PER_PAGE', 100));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->propertyTransformer->transformCollection(
                $properties->toArray()['data']
            ),
            $properties
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $property = Property::with('properties')->find($id);

        if (!$property) {
            return $this->respondNotFound('Property not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->propertyTransformer->transform(
                $property->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('api-write:property')) {
            return $this->respondUnauthorized();
        }

        $property = Property::find($id);
        if (is_null($property)) {
            return $this->respondNotFound('Property not found');
        }

        $property->delete();

        return $this->respondWithData([]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('api-write:property')) {
            return $this->respondUnauthorized();
        }

        $required_fields = ['belongsTo_type', 'belongsTo_id', 'type', 'name'];
        if (!$this->checkInput($required_fields, $request)) {
            return $this->setStatusCode(422)->respondWithError("Missing fields. Required: " . implode(',', $required_fields));
        }

        $belongs_to = ('App\\' . $request->input('belongsTo_type'))::find($request->input('belongsTo_id'));
        if (is_null($belongs_to)) {
            return $this->setStatusCode(422)->respondWithError("Object " . $request->input('belongsTo_id') .
                                                               " of type " . $request->input('belongsTo_type') . " not found.");
        }

        $p = Property::create([
            'belongsTo_type' => $request->input('belongsTo_type'),
            'belongsTo_id' => $request->input('belongsTo_id'),
            'type' => $request->input('type'),
            'name' => $request->input('name')
        ]);

        if ($request->has('value')) {
            $p->value = $request->input('value');
        }

        $p->save();

        if ($request->has('update_belongs_to')) {
            $belongs_to->save();
        }

        return $this->respondWithData([]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:property')) {
            return $this->respondUnauthorized();
        }

        $property = Property::find($request->input('property_id'));
        if (is_null($property)) {
            return $this->respondNotFound('Property not found');
        }

        $this->updateModelProperties($property, $request, [
            'name' => 'property_name'
        ]);
        $property->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('propertys'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @param $target_type
     * @param $target_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setReadFlag($target_type, $target_id)
    {
        if (Gate::denies('api-write:property')) {
            return $this->respondUnauthorized();
        }

        try {
            $target = ('App\\' . $target_type)::find($target_id);
        }
        catch (ModelNotFoundException $ex) {
            return $this->respondNotFound();
        }

        Property::create([
            'belongsTo_type' => $target_type,
            'belongsTo_id' => $target_id,
            'type' => 'ReadFlag'
        ]);

        broadcast(new ReadFlagSet($target_type, $target_id));

        return $this->respondWithData([]);

    }

}
