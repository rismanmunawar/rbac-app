@if ($showPermissionModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 w-full max-w-4xl relative">
            <div class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                Kelola Permission User
            </div>

            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                @foreach ($allPermissions as $module => $permissions)
                    <div class="border p-4 rounded-md bg-gray-50 dark:bg-gray-800 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-semibold uppercase text-sm text-gray-700 dark:text-gray-200">
                                {{ $module }}
                            </span>
                            <label class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox"
                                    wire:click="toggleModulePermissions('{{ $module }}', $event.target.checked)"
                                    class="mr-1">
                                Pilih Semua
                            </label>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach ($permissions as $perm)
                                @php
                                    $isViaRole =
                                        in_array($perm, $rolePermissions) && !in_array($perm, $directPermissions);
                                @endphp

                                <label class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" wire:model="userPermissions" value="{{ $perm }}"
                                        @if (in_array($perm, $rolePermissions)) disabled @endif>
                                    <span>
                                        {{ $perm }}
                                        @if ($isViaRole)
                                            <span class="ml-1 text-xs text-gray-400">(via role)</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button wire:click="$set('showPermissionModal', false)"
                    class="px-4 py-2 border rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Batal
                </button>
                <button wire:click="savePermissions" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endif
