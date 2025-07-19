<div class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Log Aktivitas</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2">Waktu</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2">Aksi</th>
                    <th class="px-4 py-2">Deskripsi</th>
                    <th class="px-4 py-2">Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-t border-gray-300 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2">{{ $log->user_name }}</td>
                    <td class="px-4 py-2">{{ $log->action }}</td>
                    <td class="px-4 py-2">{{ $log->description }}</td>
                    <td class="px-4 py-2 text-xs">
                        <pre class="whitespace-pre-wrap">{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada log ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>