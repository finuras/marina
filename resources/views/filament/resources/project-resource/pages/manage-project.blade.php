<x-filament::page>
    <x-filament::card>

        <div class=""> Folder status</div>
        @if($isFolderCreated)
            <div class="flex w-full justify-between">
                <div class=""> Folder is OK! </div>
                <x-filament::button color="danger" wire:click="deleteFolder"> Delete folder </x-filament::button>
            </div>
        @else
            <div class=""> Folder is NOT created! </div>
            <x-filament::button wire:click="createFolder"> Create folder </x-filament::button>
        @endif

    </x-filament::card>

    <x-filament::card>
        <div>
            <div> docker-compose file </div>
            <textarea
                cols="120"
                rows="30"
                wire:model.defer="composeContent">{{ $composeContent }}</textarea>
        </div>
        <x-filament::button wire:click="saveComposeFile"> Save docker-compose </x-filament::button>
    </x-filament::card>
</x-filament::page>
