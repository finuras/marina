<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class RemoteCommand extends Page
{
    protected static string $resource = ServerResource::class;

    protected static string $view = 'filament.resources.server-resource.pages.remote-command';

    public $activity;

    public $isKeepAliveOn = false;

    public function mount()
    {
//        $this->searchForId = 21;
//        $this->polling();
//        ray($this->activity);
    }

    public function runCommand()
    {
        ray()->clearAll();

        $this->activity = activity()
            ->withProperty('status', 'running')
            ->log('Running command');

        $this->isKeepAliveOn = true;

        dispatch(new \App\Jobs\ExecuteProcess($this->activity));
    }

    public function polling()
    {
        $this->activity->refresh();

        if ($this->activity->properties['status'] === 'finished') {
            $this->isKeepAliveOn = false;
        }
    }
}
