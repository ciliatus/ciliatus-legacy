<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use Illuminate\Http\Request;

class ApiAiController extends ApiController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        \Log::debug($request->input('result'));
        switch ($request->input('result')['metadata']['intentName']) {
            case 'get_animal_health':
                $animalOrResponse = $this->getAnimalOrRespond($request);
                if (!is_a($animalOrResponse, 'App\Animal')) {
                    return $animalOrResponse;
                }

                $animal = $animalOrResponse;

                if (is_null($animal->terrarium)) {
                    return $this->respondToApiAi($animal->display_name . 'scheint kein zu Hause zu haben.');
                }

                $temperature = $animal->terrarium->getCurrentTemperature();
                $humidity = $animal->terrarium->getCurrentHumidity();
                return $this->respondToApiAi(
                    'Bei ' . $animal->display_name . ' hat es ' . $temperature . '°C und eine Luftfeuchtigkeit von ' . $humidity . '%',
                    'Bei ' . $animal->display_name . ' hat es ' . $temperature . '°C und eine Luftfeuchtigkeit von ' . $humidity . '%'
                );

            case 'get_feedings_today':
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
                    $text .= 'Heute muss niemand gefüttert werden.';
                }
                else {
                    foreach ($schedules_by_food as $type=>$animals) {
                        $text .= $type . ' bekommen heute: ' . implode(',', $animals) . '. ';
                    }
                }

                return $this->respondToApiAi(
                    $text
                );

            case 'start_action_sequence':
                $animalOrResponse = $this->getAnimalOrRespond($request);
                if (!is_a($animalOrResponse, 'App\Animal')) {
                    return $animalOrResponse;
                }

                $animal = $animalOrResponse;

                if (is_null($animal->terrarium)) {
                    return $this->respondToApiAi($animal->display_name . 'scheint kein zu Hause zu haben.');
                }

                return $this->respondToApiAi(
                    'Funktion noch nicht implementiert.'
                );

            default:
                return $this->respondToApiAi('Ciliatus hat deine Frage nicht verstanden.');
        }
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
            return $this->respondToApiAi($animal_name . ' kenne ich nicht.');
        }

        if ($animal->count() > 1) {
            return $this->respondToApiAi($animal_name . ' heißen mehrere Tiere.');
        }

        $animal = $animal->first();

        return $animal;
    }

}
