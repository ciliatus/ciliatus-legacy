<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\Http\Transformers\PumpTransformer;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


/**
 * Class PumpController
 * @package App\Http\Controllers
 */
class PumpController extends ApiController
{
    /**
     * @var PumpTransformer
     */
    protected $pumpTransformer;

    /**
     * PumpController constructor.
     * @param PumpTransformer $_pumpTransformer
     */
    public function __construct(PumpTransformer $_pumpTransformer)
    {
        parent::__construct();
        $this->pumpTransformer = $_pumpTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $pumps = Pump::with('valves')
                     ->with('controlunit');

        $pumps = $this->filter($request, $pumps);

        return $this->respondTransformedAndPaginated(
            $request,
            $pumps,
            $this->pumpTransformer
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

        $pump = Pump::with('valves')
                    ->with('controlunit')
                    ->find($id);

        if (!$pump) {
            return $this->respondNotFound('Pump not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->pumpTransformer->transform(
                $pump->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::find($id);
        if (is_null($pump)) {
            return $this->respondNotFound('Pump not found');
        }

        $pump->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::create();
        $pump->name = $request->input('name');
        $pump->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $pump->id
            ],
            [
                'redirect' => [
                    'uri'   => url('pumps/' . $pump->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::find($id);
        if (is_null($pump)) {
            return $this->respondNotFound('Pump not found');
        }

        if ($request->has('controlunit') && strlen($request->input('controlunit')) > 0) {
            $controlunit = Controlunit::find($request->input('controlunit'));
            if (is_null($controlunit)) {
                return $this->setStatusCode(422)->respondWithError('Controlunit not found');
            }
        }

        $this->updateModelProperties($pump, $request, [
            'name', 'controlunit_id' => 'controlunit'
        ]);

        $this->updateExternalProperties($pump, $request, [
            'ControlunitConnectivity' => [
                'bus_type', 'i2c_address', 'i2c_multiplexer_address', 'i2c_multiplexer_port', 'gpio_pin'
            ]
        ]);
        $pump->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps'),
                'delay' => 1000
            ]
        ]);

    }

}
