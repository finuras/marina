<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index()
    {
        return view('processes.index');
    }

    public function mock()
    {    // defining a route in Laravel
        set_time_limit(0);              // making maximum execution time unlimited
        ob_implicit_flush(1);           // Send content immediately to the browser on every statement which produces output
        ob_end_flush();                 // deletes the topmost output buffer and outputs all of its contents

        sleep(1);
        echo json_encode(['data' => 'test 1']);

        sleep(1);
        echo json_encode(['data' => 'test 2']);

        sleep(1);
        echo json_encode(['data' => 'test 3']);
        die(1);
    }

    public function sse()
    {
//        set_time_limit(0);              // making maximum execution time unlimited
//        ob_implicit_flush(1);           // Send content immediately to the browser on every statement which produces output
//
//
//        header('Content-Type: text/event-stream');
//        header('Cache-Control: no-cache');
//
//        while (true) {
//            $data = ['data' => 'test 1'];
//            echo "data: " . json_encode($data);
//            $time = date('r');
//            echo "data: The server time is: {$time}\n\n";
//            flush();
//            sleep(3);
//       }
//
//
//
//        return;
        header("Cache-Control: no-store");
        header("Content-Type: text/event-stream");

        $counter = rand(1, 10);
        while (true) {
            // Every second, send a "ping" event.

            echo "event: ping\n";
            $curDate = date(DATE_ISO8601);
            echo 'data: {"time": "' . $curDate . '"}';
            echo "\n\n";

            // Send a simple message at random intervals.

            echo now()->format('Y-m-d H:i:s') . " - " . $counter;

            $counter--;

            if (!$counter) {
                echo 'data: This is a message at time ' . $curDate . "\n\n";
                $counter = rand(1, 10);
            }

            ob_end_flush();
            flush();

            // Break the loop if the client aborted the connection (closed the page)

            if (connection_aborted()) break;

            sleep(1);
        }

    }
}
