<div>
    @if ($logModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Detail Log Terakhir</h2>
                <button wire:click="$set('logModal', false)" class="text-gray-600 hover:text-red-500">&times;</button>
            </div>

            <div class="mb-3 text-sm text-gray-600">
                <p><strong>Waktu:</strong> {{ $logDetails['created_at'] ?? '-' }}</p>
                <p><strong>Oleh:</strong> {{ $logDetails['causer'] ?? '-' }}</p>
                <p><strong>Aksi:</strong> {{ $logDetails['action'] ?? '-' }}</p>
            </div>

            <div>
                <h3 class="font-medium text-gray-800 mb-2">Perubahan:</h3>

                @if (!empty($logDetails['changes']))
                <div class="space-y-1 max-h-64 overflow-y-auto text-sm font-mono bg-gray-50 border border-gray-200 rounded p-3">
                    @foreach ($logDetails['changes'] as $field => $diff)
                    @if ($field === 'permissions')
                    @php
                    $old = $diff['before'] ?? [];
                    $new = $diff['after'] ?? [];
                    $added = array_diff($new, $old);
                    $removed = array_diff($old, $new);
                    @endphp

                    @foreach ($removed as $perm)
                    <div class="text-red-600">- {{ $perm }}</div>
                    @endforeach

                    @foreach ($added as $perm)
                    <div class="text-green-600">+ {{ $perm }}</div>
                    @endforeach
                    @else
                    <div>
                        <span class="text-gray-700">{{ $field }}:</span>
                        <span class="text-red-500 line-through">{{ $diff['before'] ?? '-' }}</span>
                        <span class="text-green-600 ml-2">{{ $diff['after'] ?? '-' }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 italic">Tidak ada perubahan.</p>
                @endif
            </div>

            <div class="mt-5 text-right">
                <button wire:click="$set('logModal', false)" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>