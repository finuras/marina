<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class LocalProjects extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.local-projects';

    public $projectList;

    public $newProjectName;

    public function mount()
    {
        $this->reloadProjectsList();
    }
    public function reloadProjectsList()
    {
        $folders = File::directories(storage_path('app/marina'));

        $this->projectList = collect($folders)->map(fn($path) => str($path)->afterLast('/'));

        ray($this->projectList);
    }

    public function createProject()
    {
        File::ensureDirectoryExists(storage_path('app/marina/' . $this->newProjectName));
        $this->newProjectName = '';
        $this->reloadProjectsList();
    }

}
