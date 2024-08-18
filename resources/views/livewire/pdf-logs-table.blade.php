<div class="p-4 shadow sm:rounded-lg">
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('file_name')">
                            File Name
                            @if($sortField === 'file_name')
                                @if($sortDirection === 'asc')
                                    <span>&uarr;</span> <!-- Indicatore di ordinamento ascendente -->
                                @else
                                    <span>&darr;</span> <!-- Indicatore di ordinamento discendente -->
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('imported_at')">
                            Import Date
                            @if($sortField === 'imported_at')
                                @if($sortDirection === 'asc')
                                    <span>&uarr;</span>
                                @else
                                    <span>&darr;</span>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="#" wire:click.prevent="sortBy('successful')">
                            Status
                            @if($sortField === 'successful')
                                @if($sortDirection === 'asc')
                                    <span>&uarr;</span>
                                @else
                                    <span>&darr;</span>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error Message</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($logs as $log)
                    <tr>
                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->file_name }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500">{{ $log->imported_at->format('Y-m-d H:i:s') }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500">
                            @if($log->successful)
                                <span class="text-green-600">Success</span>
                            @else
                                <span class="text-red-600">Failed</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500">
                            @if($log->successful)
                                <span>-</span>
                            @else
                                {{ $log->error_message }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 px-6 text-center text-sm text-gray-500">No logs available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
