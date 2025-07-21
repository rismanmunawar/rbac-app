@if($showDetailModal && $detailData)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-xl p-8 w-full max-w-lg space-y-6 shadow-xl relative">
        <button wire:click="closeDetailModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl">&times;</button>
        <div class="flex flex-col items-center gap-3">
            @if($detailData->image)
            <img src="{{ Storage::url($detailData->image) }}" alt="foto" class="w-24 h-24 rounded-full object-cover border border-gray-300 dark:border-gray-600 shadow" />
            @else
            <div class="w-24 h-24 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-3xl text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            @endif
            <div class="font-semibold text-lg">{{ $detailData->name }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $detailData->designation ?? '-' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">NIK:</span>
                <div>{{ $detailData->nik ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Alias:</span>
                <div>{{ $detailData->alias ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Email:</span>
                <div>{{ $detailData->email }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Telepon:</span>
                <div>{{ $detailData->phone ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Status:</span>
                <div>
                    <span class="inline-flex px-2 py-1 rounded font-medium text-xs
                        {{ $detailData->status ? 'bg-green-100 text-green-700 dark:bg-green-900/30' : 'bg-gray-300 text-gray-700 dark:bg-gray-900/40 dark:text-gray-400' }}">
                        {{ $detailData->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">ID:</span>
                <div>{{ $detailData->id }}</div>
            </div>
        </div>
    </div>
</div>
@endif