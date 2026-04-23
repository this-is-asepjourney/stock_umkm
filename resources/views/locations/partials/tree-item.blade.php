<div class="tree-item">
    <div class="flex items-center py-1 hover:bg-gray-700/50 rounded px-2 cursor-pointer"
        wire:click="selectLocation({{ $location->id }})">

        <!-- Expand/Collapse Button -->
        @if($location->children->count() > 0)
            <button wire:click.stop="toggleNode({{ $location->id }})" class="mr-2 text-gray-400 hover:text-white w-4">
                @if(in_array($location->id, $expandedNodes))
                    ▼
                @else
                    ▶
                @endif
            </button>
        @else
            <span class="w-4 mr-2"></span>
        @endif

        <!-- Location Icon -->
        <span class="mr-2">
            @if($location->type === 'warehouse')
                🏭
            @elseif($location->type === 'rack')
                📦
            @else
                🗃️
            @endif
        </span>

        <!-- Location Name -->
        <span class="text-white text-sm flex-1">{{ $location->name }}</span>

        <!-- Badge Code -->
        <span class="text-gray-500 text-xs font-mono">{{ $location->code }}</span>
    </div>

    <!-- Children -->
    @if($location->children->count() > 0 && in_array($location->id, $expandedNodes))
        <div class="ml-6 border-l border-gray-700 pl-4">
            @foreach($location->children as $child)
                @include('livewire.locations.partials.tree-item', ['location' => $child])
            @endforeach
        </div>
    @endif
</div>