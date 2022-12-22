<x-filament::page>

    <div>
        <div>Activity</div>
        <div>{{ $activity?->id }}</div>

        <button class="bg-blue-500" wire:click="runCommand">
            Run command
        </button>
    </div>

    <div class="w-full h-10"></div>

    <pre
        style="
            background-color: #BADA55;
            width: 1200px;
            height: 600px;
            overflow-y: scroll;
            display: flex;
            flex-direction: column-reverse;
        "
        placeholder="Build output"
        @if($isKeepAliveOn) wire:poll.1500ms="polling" @endif
    >
        {{ data_get($activity, 'description') }}
    </pre>

    @if($isKeepAliveOn) Polling... @endif
</x-filament::page>
