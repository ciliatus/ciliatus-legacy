<?php

namespace App\Console\Commands;

use App\Helpers\AnomalyDetection;
use App\Log;
use App\Sensorreading;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class DetectSensorreadingAnomalies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:detect_sensorreading_anomalies 
                            {--terrarium_id= : Define if you want to only detect anomalies for a specific terrarium} 
                            {--history_minutes= : Define a different timespan for sensor readings (default 24h)}
                            {--simulate : Set if you do not want flag anomalies and only view debug output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $anomaly_count = 0;
        $history_minutes = 60*24;

        $rolling_avg_margin = env('OUTLIER_DETECTION_ROLLING_AVERAGE_MARGIN', 2);
        $max_deviation_percent = env('OUTLIER_DETECTION_MAX_DEVIATION_PERCENT', 25);

        if (is_null($this->option('terrarium_id'))) {
            $terraria = Terrarium::get();
        }
        else {
            $terrarium = Terrarium::find($this->option('terrarium_id'));
            if (is_null($terrarium)) {
                throw new ModelNotFoundException('Terrarium ' . $this->option('terrarium_id') . ' not found.');
            }
            $terraria = new Collection();
            $terraria->push($terrarium);
        }

        if (!is_null($this->option('history_minutes'))) {
            $history_minutes = $this->option('history_minutes');
        }


        echo "Running anomaly detection with:" . PHP_EOL;
        echo "  rolling average margin: $rolling_avg_margin" . PHP_EOL;
        echo "  maximum deviation percent: $max_deviation_percent" . PHP_EOL . PHP_EOL;
        echo "  history minutes: $history_minutes / " . $history_minutes / 60 . "h" . PHP_EOL;

        foreach ($terraria as $terrarium) {
            echo PHP_EOL . $terrarium->display_name . PHP_EOL;
            foreach (['temperature_celsius', 'humidity_percent'] as $reading_type) {
                echo $reading_type . PHP_EOL;

                $readings = $terrarium->getSensorreadingsByType(
                        $reading_type,
                        true,
                        Carbon::now(),
                        $history_minutes,
                        true,
                        false
                );

                $readings_clone = clone $readings;
                $readings_column = array_column($readings_clone->toArray(), 'avg_rawvalue');
                $rolling_avg_readings = array_splice($readings_column, 0, $rolling_avg_margin*2);

                $i = 0;
                foreach ($readings as $reading) {
                    if (empty($reading) || is_null($reading) || !$reading) {
                        continue;
                    }

                    $i++;
                    if ($i <= $rolling_avg_margin || $i >= $readings->count() - $rolling_avg_margin) {
                        continue;
                    }

                    array_shift($rolling_avg_readings);
                    $rolling_avg_readings[] = $reading->avg_rawvalue;

                    $rolling_avg = array_sum($rolling_avg_readings) / count($rolling_avg_readings);
                    if ($rolling_avg == 0) {
                        continue;
                    }

                    $deviation = abs(100 - $reading->avg_rawvalue / $rolling_avg * 100);

                    if ($deviation > $max_deviation_percent) {
                        $anomaly_count++;
                        echo '   Anomaly deviation of ' . round($deviation, 2) . '%: Value: ' .
                            round($reading->avg_rawvalue, 2) . ' Rolling average: ' . round($rolling_avg, 2) .
                            ' Average: ' . implode(', ', $rolling_avg_readings) . PHP_EOL;

                        if ($this->option('simulate') !== true) {
                            $sr = Sensorreading::find($reading->id);
                            $sr->is_anomaly = true;
                            $sr->save();

                            /*
                            foreach (Sensorreading::where('sensorreadinggroup_id', $reading->sensorreadinggroup_id)->get() as $sr) {
                                $sr->is_anomaly = true;
                                $sr->save();
                            }
                            */
                        }
                    }
                }

            }
        }

        \Log::info("$anomaly_count anomalies detected in the last " . $history_minutes/60 . " hours." . PHP_EOL);
        echo "$anomaly_count anomalies detected in the last " . $history_minutes/60 . " hours." . PHP_EOL;

        return true;
    }
}
