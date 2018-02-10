<?php

namespace App\Console\Commands;

use App\LogicalSensor;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GenerateDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:demo:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates demo data, e.g. adding humidity and temp values for the current timestamp';

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        $token = env('DEMO_USER_TOKEN');
        $srg = Uuid::uuid4();
        $minutes = Carbon::today()->diffInMinutes(Carbon::now());

        foreach (LogicalSensor::get() as $ls) {

            switch ($ls->type) {
                case 'temperature_celsius':
                    $rawvalue = 20 + 3 * (sin($minutes/1440*2*pi()-0.5*pi()) + 1) + mt_rand(0, 10) / 100;
                    break;

                case 'humidity_percent':
                    $rawvalue = 50 + 25 * (sin($minutes/1440*2*pi()+0.5*pi()) + 1) + mt_rand(0, 10) / 100;
                    $rawvalue = $rawvalue > 100 ? 100 : $rawvalue;
                    break;

                default:
                    $rawvalue = null;
            }

            if (is_null($rawvalue))
                continue;

            $client = new Client();
            $data = [
                'headers' => [
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Authorization' => 'Bearer ' . $token
                ],
                'body' => json_encode([
                    'group_id' => $srg,
                    'logical_sensor_id' => $ls->id,
                    'rawvalue' => $rawvalue
                ])
            ];

            echo "Writing $rawvalue to ls " . $ls->name . " (" . $ls->id . ") via " . url('/api/v1/sensorreadings') . PHP_EOL;

            try {
                $response = $client->post(url('/api/v1/sensorreadings'), $data);
            }
            catch (ClientException $ex) {
                \Log::error($ex->getMessage() . PHP_EOL . $ex->getResponse()->getBody());
                throw $ex;
            }
        }

        return true;
    }
}
