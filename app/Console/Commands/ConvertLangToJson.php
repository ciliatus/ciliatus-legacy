<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;

/**
 * Class ConvertLangToJson
 * @package App\Console\Commands
 */
class ConvertLangToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:convert_lang_to_json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts all language files to JSON and writes them to resources/assets/js/lang.js for usage with Vue';

    /**
     * @param $str
     * @return mixed
     */
    private function replace_placeholder_syntax($str)
    {
        if (is_array($str)) {
            return array_map(array($this, 'replace_placeholder_syntax'), $str);
        }
        return preg_replace('/(\:)([A-Za-z0-9]{1,})/', '{\2}', $str);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $languages = array_diff(scandir(resource_path() . '/lang'), array('..', '.'));
        
        $groups = [
            'buttons', 'components', 'errors', 'labels', 'languages',
            'menu', 'messages', 'product', 'setup', 'tooltips', 'units',
            'weekdays'
        ];

        $language_array = [];
        foreach ($languages as $lang) {
            $language_array[$lang] = [];
            foreach ($groups as $group) {
                $language_array[$lang][$group] = [];
            }

            array_walk($language_array[$lang],
                function(&$item, $val) use ($lang) {
                    $item = array_map(array($this, 'replace_placeholder_syntax'), Lang::get($val, [], $lang));
                }
            );
        }

        $fp = fopen(resource_path() . '/assets/js/lang.js', 'w');
        fwrite($fp, 'module.exports = ' . json_encode($language_array));
    }
}
