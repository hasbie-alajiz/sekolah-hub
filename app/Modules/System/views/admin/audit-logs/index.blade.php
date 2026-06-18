<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Audit Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    <div class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="input input-bordered input-sm w-full max-w-xs">
                    </div>
                    <div class="form-control">
                        <input type="text" name="action" value="{{ request('action') }}" placeholder="Filter aksi (cth: user)..." class="input input-bordered input-sm w-full max-w-xs">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    @if(request()->anyFilled(['search', 'action']))
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full text-xs">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pengguna</th>
                                    <th>Aksi</th>
                                    <th>Objek</th>
                                    <th>IP Address</th>
                                    <th>Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @if($log->user)
                                                <div class="font-bold text-gray-800">{{ $log->user->name }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $log->user->email }}</div>
                                            @else
                                                <span class="text-gray-400">Sistem/Guest</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-sm badge-ghost font-mono">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            @if($log->auditable_type)
                                                <div class="font-semibold text-gray-700">{{ class_basename($log->auditable_type) }}</div>
                                                <div class="text-[10px] text-gray-400">ID: {{ $log->auditable_id }}</div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            @if($log->old_values || $log->new_values)
                                                <div class="collapse collapse-arrow bg-base-100 rounded-box border border-gray-100 max-w-md">
                                                    <input type="checkbox" class="peer" /> 
                                                    <div class="collapse-title text-[10px] font-medium py-2 min-h-0">
                                                        Lihat Detail Perubahan
                                                    </div>
                                                    <div class="collapse-content overflow-auto max-h-40 py-2"> 
                                                        @if($log->old_values)
                                                            <div class="mb-2">
                                                                <div class="font-bold text-rose-600 text-[10px]">Sebelum:</div>
                                                                <pre class="bg-gray-50 p-2 rounded text-[10px] font-mono whitespace-pre-wrap">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                        @endif
                                                        @if($log->new_values)
                                                            <div>
                                                                <div class="font-bold text-emerald-600 text-[10px]">Sesudah:</div>
                                                                <pre class="bg-gray-50 p-2 rounded text-[10px] font-mono whitespace-pre-wrap">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">Tidak ada perubahan nilai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
