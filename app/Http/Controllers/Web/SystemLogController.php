<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Gate;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class SystemLogController extends Controller
{
    protected $request;

    public function __construct ()
    {
        $this->request = app('request');
    }

    public function index()
    {

        if (Gate::denies('admin')) {
            return view('errors.401', [], 401);
        }

        if ($this->request->input('l')) {
            LaravelLogViewer::setFile(base64_decode($this->request->input('l')));
        }

        if ($this->request->input('dl')) {
            return $this->download(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('dl'))));
        }
        elseif ($this->request->has('del')) {
            app('files')->delete(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('del'))));
            return $this->redirect($this->request->url());
        }
        elseif ($this->request->has('delall')) {
            foreach(LaravelLogViewer::getFiles(true) as $file){
                app('files')->delete(LaravelLogViewer::pathToLogFile($file));
            }
            return $this->redirect($this->request->url());
        }

        $logs = LaravelLogViewer::all();
        foreach ($logs as &$log) {
            switch ($log['level_img']) {
                case 'emergency':
                case 'alert':
                case 'critical':
                case 'error':
                $log['level_icon'] = 'error_outline';
                $log['level_color'] = 'red';
                    break;

                case 'warning':
                    $log['level_icon'] = 'error_outline';
                    $log['level_color'] = 'orange';
                    break;

                case 'notice':
                case 'info':
                case 'debug':
                case 'processed':
                    $log['level_icon'] = 'info_outline';
                    $log['level_color'] = 'teal';
                    break;
            }
        }

        return view('logs.system-index', [
            'logs' => $logs,
            'files' => LaravelLogViewer::getFiles(true),
            'current_file' => LaravelLogViewer::getFileName()
        ]);
    }

    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }
}
