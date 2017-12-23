<?php

namespace App\Console\Commands;

use App\LogicalSensor;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
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
        $factor = $minutes*(2/1440*2);

        foreach (LogicalSensor::get() as $ls) {

            switch ($ls->type) {
                case 'temperature_celsius':
                    $rawvalue = sqrt(sin($factor+0.75*pi())+3) * 15 + rand(2, 2.4);
                    break;

                case 'humidity_percent':
                    $rawvalue = (sin($factor/2)+1)*25+50 + rand(0.1, 2);
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
