<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Spatie\Activitylog\Models\Activity;

class LongOutputCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:output {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');

        $activity = Activity::query()->find($id);
        $activity->update(['description' => '']);
        $activity->save();

        collect(range(1,50))->each(function() use ($activity) {
            $quote = Inspiring::quote();

            $activity->description .= '\n [' . now()->format('Y-m-d H:i:s') . '] ' . $quote;
            $activity->save();

            usleep(500_000);
        });

        $activity->description .= "\n\n Finished. ";
        $activity->save();


        return Command::SUCCESS;
    }
}
