<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Traits\SendsApiAiRequests;
use Auth;
use Illuminate\Http\Request;

/**
 * Class ApiAiController
 * @package App\Http\Controllers\Api
 */
class ApiAiController extends ApiController
{

    use SendsApiAiRequests;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        $this->setLanguage($request);

        switch ($request->input('result')['metadata']['intentName']) {
            case 'get_animal_health':
                return $this->respond_get_animal_health($request);

            case 'get_feedings_today':
                return $this->respond_get_feedings_today($request);

            case 'get_next_feeding':
                return $this->respond_get_next_feeding($request);

            default:
                return $this->respondToApiAi(trans('apiai.errors.query_not_understood'));
        }
    }

    /**
     * Receive text and send it to API.ai
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function parseAndSendRequest(Request $request)
    {
        $this->appendDebugInfo($request->input('speech'));

        $result = $this->sendApiAiRequest($request->input('speech'), Auth::user());
        if (!$result) {
            $result = trans('apiai.errors.could_not_send_request');
        }
        return $this->respondWithData([
            'api_result' => $result
        ]);
    }

    /**
     * @param $speech
     * @param null $displayText
     * @param array $data
     * @param array $contextOut
     * @param string $source
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondToApiAi($speech, $displayText = null, $data = [], $contextOut = [], $source = 'Ciliatus')
    {
        if (is_null($displayText)) {
            $displayText = $speech;
        }

        return $this->respond([
            'speech'        => $speech,
            'displayText'   => $displayText,
            'data'          => $data,
            'contextOut'    => $contextOut,
            'source'        => $source
        ]);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function getAnimalOrRespond($request)
    {
        $animal_name = $request->input('result')['parameters']['animal'];
        if (is_array($animal_name)) {
            $animal_name = $animal_name['given-name'];
        }
        $animal = Animal::where('display_name', $animal_name)->get();
        if (is_null($animal->first())) {
            return $this->respondToApiAi(trans('apiai.errors.animal_not_found', [
                'display_name' => $animal_name
            ]));
        }

        if ($animal->count() > 1) {
            return $this->respondToApiAi(trans('apiai.errors.animal_multiple_options', [
                'display_name' => $animal_name
            ]));
        }

        $animal = $animal->first();

        return $animal;
    }

    /**
     * @param Request $request
     */
    private function setLanguage(Request $request)
    {
        app()->setLocale($request->input('result')['parameters']['lang']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond_get_animal_health(Request $request)
    {
        $animalOrResponse = $this->getAnimalOrRespond($request);
        if (!is_a($animalOrResponse, 'App\Animal')) {
            return $animalOrResponse;
        }

        $animal = $animalOrResponse;

        if (is_null($animal->terrarium)) {
            return $this->respondToApiAi(trans('apiai.errors.animal_no_terrarium', [
                'display_name' => $animal->display_name
            ]));
        }

        $temperature = $animal->terrarium->getCurrentTemperature();
        $humidity = $animal->terrarium->getCurrentHumidity();
        return $this->respondToApiAi(trans('apiai.fulfillment.animal_health', [
            'display_name'  => $animal->display_name,
            'temperature'   => $temperature,
            'humidity'      => $humidity
        ]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond_get_feedings_today(Request $request)
    {
        $schedules_by_food = [];
        foreach (Animal::get() as $animal) {
            foreach ($animal->getDueFeedingSchedules() as $type=>$schedules) {
                foreach ($schedules as $schedule) {
                    $schedules_by_food[$schedule->name][] = $animal->display_name;
                }
            }
        }

        $text = '';
        if (count($schedules_by_food) < 1) {
            $text .= trans('apiai.fulfillment.feedings_today_none');
        }
        else {
            foreach ($schedules_by_food as $type=>$animals) {
                $text .= trans('apiai.fulfillment.feedings_today_list', [
                    'type'          => $type,
                    'display_names' => implode(', ', $animals)
                ]);
            }
        }

        return $this->respondToApiAi(
            $text
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond_get_next_feeding(Request $request)
    {
        $animalOrResponse = $this->getAnimalOrRespond($request);
        if (!is_a($animalOrResponse, 'App\Animal')) {
            return $animalOrResponse;
        }

        $animal = $animalOrResponse;

        $next = null;
        foreach ($animal->feeding_schedules as $schedule) {
            $afs = (new AnimalFeedingScheduleRepository($schedule))->show();
            if ((is_null($next) || $afs->next_feeding_at_diff < $next->next_feeding_at_diff)) {
                $next = $afs;
            }
        }

        if (is_null($next)) {
            return $this->respondToApiAi(
                trans('apiai.fulfillment.animal_next_feeding_none')
            );
        }
        else {
            return $this->respondToApiAi(
                trans('apiai.fulfillment.feedings_today_list', [
                    'display_name'  => $animal->display_name,
                    'time'          => $next->next_feeding_at_diff < 1 ?
                        trans('labels.today') :
                        trans('units.days_in', ['val' => $next->next_feeding_at_diff ]),
                    'type'          => $next->name
                ])
            );
        }
    }

}
