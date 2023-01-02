<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Inspiring;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Process\Process;

class ExecuteProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Activity $activity,
        public string $command = 'php artisan inspire',
        public ?string $cwd = null,
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        $name = Str::uuid();
//        $command = ['ls', '-lsa'];
//        $command = ['sudo', 'docker', 'ps'];
//        $command = ['curl','--unix-socket','/var/run/docker.sock','http://127.0.0.1/version'];
//        $process = new Process($command);
//        $process->run(function ($type, $buffer) use ($name) {
//            if (Process::ERR === $type) {
//                Storage::append($name.'.log', 'ERR > '.$buffer);
//            } else {
//                Storage::append($name.'.log', 'OUT > '.$buffer);
//            }
//            ray($buffer);
//            $this->activity->description .= $buffer;
//            $this->activity->save();
//        });

        Process::fromShellCommandline($this->command, $this->cwd, null, null, 60)
            ->setTimeout(300)
            ->run(function ($type, $buffer) {
                $this->activity->description .= '[' . now()->format('Y-m-d H:i:s') . '] ' . $buffer;
                $this->activity->save();
            });

        $this->activity->description .= "\n\n Finished.";
        $this->activity->properties = $this->activity->properties->merge(['status' => 'finished']);
        $this->activity->save();
    }
}
