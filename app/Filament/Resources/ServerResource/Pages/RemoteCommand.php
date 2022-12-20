<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class RemoteCommand extends Page
{
    protected static string $resource = ServerResource::class;

    protected static string $view = 'filament.resources.server-resource.pages.remote-command';

    public $searchForId;

    public $activity;

    public $isKeepAliveOn = true;

    public function mount()
    {
//        $this->searchForId = 21;
//        $this->polling();
//        ray($this->activity);
    }

    public function polling()
    {
//        $this->activity = Activity::query()->find($this->searchForId);
//
//        if (Str::contains($this->activity->description, 'Finished')) {
//            $this->isKeepAliveOn = false;
//        }
    }
}
