<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pesan Masuk') }}
            </h2>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Message Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <div class="border-b border-gray-100 pb-4 mb-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase">Subjek</span>
                            <h3 class="text-xl font-bold text-gray-950 mt-1">{{ $contact->subject }}</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-semibold text-gray-400 uppercase">Isi Pesan</span>
                                <div class="mt-2 text-gray-700 text-sm whitespace-pre-wrap leading-relaxed bg-gray-50/50 p-4 rounded-lg border border-gray-100 min-h-[150px]">
                                    {{ $contact->message }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h4 class="font-semibold text-gray-800 mb-3 text-sm">Metadata & Informasi Teknis</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="bg-gray-50 p-3 rounded border border-gray-100">
                                <span class="text-gray-400 font-medium block">IP Address</span>
                                <span class="font-mono text-gray-700 mt-1 block">{{ $contact->ip_address ?: 'Tidak tercatat' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded border border-gray-100">
                                <span class="text-gray-400 font-medium block">User Agent</span>
                                <span class="text-gray-700 mt-1 block max-h-12 overflow-y-auto font-mono break-all leading-normal">{{ $contact->user_agent ?: 'Tidak tercatat' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="space-y-6">
                    <!-- Sender Profile Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Profil Pengirim</h3>
                        
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="text-gray-400 text-xs block">Nama Lengkap</span>
                                <span class="font-semibold text-gray-800 mt-0.5 block">{{ $contact->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs block">Email</span>
                                <a href="mailto:{{ $contact->email }}" class="text-primary font-medium hover:underline mt-0.5 block flex items-center gap-1">
                                    {{ $contact->email }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </a>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs block">Nomor Telepon/HP</span>
                                <span class="text-gray-800 mt-0.5 block">{{ $contact->phone ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs block">Tanggal Masuk</span>
                                <span class="text-gray-800 mt-0.5 block">{{ $contact->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Manager Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Status & Tindakan</h3>

                        <!-- Current Status Badge -->
                        <div class="mb-4 flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status Saat Ini:</span>
                            @if($contact->status === 'unread')
                                <span class="badge bg-amber-50 border border-amber-200 text-amber-800 font-semibold rounded-full">Belum Dibaca</span>
                            @elseif($contact->status === 'read')
                                <span class="badge bg-blue-50 border border-blue-200 text-blue-800 font-semibold rounded-full">Sudah Dibaca</span>
                            @elseif($contact->status === 'replied')
                                <span class="badge bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold rounded-full">Sudah Dibalas</span>
                            @else
                                <span class="badge bg-gray-100 border border-gray-200 text-gray-700 font-semibold rounded-full">Diarsipkan</span>
                            @endif
                        </div>

                        @if($contact->replied_at)
                            <div class="text-xs text-gray-400 mb-4 italic">
                                Dibalas pada: {{ $contact->replied_at->format('d M Y, H:i') }}
                            </div>
                        @endif

                        <!-- Form Change Status -->
                        <form action="{{ route('admin.contacts.update_status', $contact->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1 text-xs">Ubah Status Ke:</label>
                                <select name="status" class="select select-bordered select-sm w-full mt-1 rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                    <option value="unread" {{ $contact->status === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                                    <option value="read" {{ $contact->status === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                                    <option value="replied" {{ $contact->status === 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                                    <option value="archived" {{ $contact->status === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg text-xs font-bold active:scale-[0.98]">Update Status</button>
                        </form>

                        <div class="border-t border-gray-100 my-4 pt-4">
                            <!-- Reply Button -->
                            <a href="mailto:{{ $contact->email }}?subject=Re: {{ rawurlencode($contact->subject) }}" class="btn btn-outline btn-sm w-full flex items-center justify-center gap-1 rounded-lg text-xs font-bold active:scale-[0.98] border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5" /></svg>
                                Balas via Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
