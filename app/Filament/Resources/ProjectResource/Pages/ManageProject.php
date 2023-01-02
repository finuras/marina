<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\File;

class ManageProject extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.manage-project';

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $project;

    public $record;

    public $composeContent;

    public $isFolderCreated;

    public $projectFolder;

    public function mount($record)
    {
        $this->record = $record;
        $this->loadProject();
        $this->loadComposeContent();
        $this->bootFolderCreated();
    }

    protected function loadComposeContent()
    {
        if (! File::exists(storage_path('app/marina/' . $this->projectFolder . '/docker-compose.yml'))) {
            return;
        }

        $this->composeContent = File::get(
            storage_path('app/marina/' . $this->projectFolder . '/docker-compose.yml')
        );
    }

    protected function loadProject()
    {
        $this->project = Project::query()->find($this->record);
        $this->projectFolder = $this->project->name;
    }

    protected function bootFolderCreated()
    {
        $this->isFolderCreated = File::isDirectory(
            storage_path('app/marina/' . $this->projectFolder)
        );
    }

    public function createFolder()
    {
        $this->loadProject();

        File::ensureDirectoryExists(
            storage_path('app/marina/' . $this->projectFolder)
        );

        $this->isFolderCreated = true;
    }

    public function deleteFolder()
    {
        $this->loadProject();

        File::deleteDirectory(
            storage_path('app/marina/' . $this->projectFolder)
        );
        $this->isFolderCreated = false;
    }

    public function saveComposeFile()
    {
        $this->loadProject();

        $this->project->update([
            'compose_file' => $this->composeContent,
        ]);

        $result = File::put(
            storage_path('app/marina/' . $this->projectFolder . '/docker-compose.yml'),
            $this->composeContent
        );

        if($result === false) {
            Notification::make()
                ->danger()
                ->title('Failed to save docker-compose.yml file')
                ->send();
            return;
        }

        Notification::make()
            ->success()
            ->title('docker-compose.yml file saved successfully')
            ->send();
    }
}
