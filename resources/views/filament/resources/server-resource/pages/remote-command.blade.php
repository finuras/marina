<x-filament::page>

    <div>
        <div>Activity ID</div>
        <input wire:model="activityId" />
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
        @if($isKeepAliveOn) wire:poll.2750ms="polling" @endif
    >
        {{ data_get($activity, 'description') }}
    </pre>

    @if($isKeepAliveOn) Polling... @endif
</x-filament::page>
