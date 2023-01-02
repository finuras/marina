<x-filament::page>

    <div>
        Create a Project
        <input wire:model="newProjectName">
        <x-filament::button wire:click="createProject"> Create </x-filament::button>
    </div>


    <div> Current Projects </div>
    @forelse($projectList as $project)
        <div> {{ $project }} </div>
    @empty
        <div> No projects found. Create a new one. </div>
    @endforelse


</x-filament::page>
