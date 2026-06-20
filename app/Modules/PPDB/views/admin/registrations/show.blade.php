<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Pendaftar: ') }} {{ $registration->registration_number }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Jalur {{ $registration->track->name }} — {{ $registration->academicYear->name }}</p>
            </div>
            <a href="{{ route('admin.ppdb.registrations.index') }}" class="btn btn-ghost btn-sm">
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="alert alert-success bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Details Panel (Values / EAV) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Biodata & Formulir Pendaftaran</h3>
                        <div class="space-y-4">
                            @forelse($registration->values as $value)
                                <div class="grid grid-cols-3 gap-2 py-2 border-b border-gray-50 text-sm">
                                    <span class="text-gray-500 font-medium">{{ $value->field->label }}</span>
                                    <span class="col-span-2 text-gray-900">
                                        @if(is_array($value->real_value))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($value->real_value as $val)
                                                    <span class="badge badge-sm badge-ghost">{{ $val }}</span>
                                                @endforeach
                                            </div>
                                        @elseif(is_bool($value->real_value))
                                            {{ $value->real_value ? 'Ya' : 'Tidak' }}
                                        @elseif($value->field->type === 'textarea')
                                            {!! nl2br(e($value->real_value)) !!}
                                        @else
                                            {{ $value->real_value }}
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm text-center py-6">Tidak ada formulir terisi.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Documents Verification Panel -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Berkas Dokumen Lampiran</h3>
                        <div class="space-y-6">
                            @forelse($registration->documents as $doc)
                                <div class="border border-gray-100 rounded-lg p-4 bg-gray-50/50">
                                    <div class="flex flex-wrap justify-between items-start gap-2 mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm">{{ $doc->field->label }}</h4>
                                            <p class="text-xs text-gray-400 mt-1">Nama File: {{ $doc->original_name }} ({{ number_format($doc->size / 1024, 2) }} KB)</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($doc->verification_status === 'pending')
                                                <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-800 px-2 py-0.5 rounded-full">Pending</span>
                                            @elseif($doc->verification_status === 'approved')
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-800 px-2 py-0.5 rounded-full">Approved</span>
                                            @else
                                                <span class="badge badge-sm bg-rose-50 border border-rose-200 text-rose-800 px-2 py-0.5 rounded-full">Rejected</span>
                                            @endif
                                            <a href="{{ route('admin.ppdb.documents.download', $doc->id) }}" class="btn btn-primary btn-xs text-white">Unduh Berkas</a>
                                        </div>
                                    </div>

                                    @if($doc->verification_notes)
                                        <div class="text-xs bg-white border border-gray-100 rounded p-2 mb-3 text-gray-600">
                                            <b>Catatan Verifikasi:</b> {{ $doc->verification_notes }}
                                        </div>
                                    @endif

                                    <!-- Verify Document Form -->
                                    <form action="{{ route('admin.ppdb.documents.verify', $doc->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 items-end justify-between border-t border-gray-100 pt-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-control w-full sm:w-1/3">
                                            <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Tindakan</label>
                                            <select name="verification_status" class="select select-bordered select-xs w-full rounded-md text-xs border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                                <option value="pending" {{ $doc->verification_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $doc->verification_status === 'approved' ? 'selected' : '' }}>Setujui (Approve)</option>
                                                <option value="rejected" {{ $doc->verification_status === 'rejected' ? 'selected' : '' }}>Tolak (Reject)</option>
                                            </select>
                                        </div>
                                        <div class="form-control flex-grow w-full">
                                            <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Catatan / Alasan (Opsional)</label>
                                            <input type="text" name="verification_notes" value="{{ old('verification_notes', $doc->verification_notes) }}" placeholder="Contoh: Berkas buram / SKL tidak sah..." class="input input-bordered input-xs w-full rounded-md text-xs border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                        </div>
                                        <button type="submit" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900 border border-gray-200 rounded-md font-semibold active:scale-[0.98]">Update</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm text-center py-6">Tidak ada dokumen dilampirkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Control Panel (Status / Actions) -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Status & Kelulusan</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-gray-400 font-medium">Status Pendaftaran Saat Ini:</span>
                                <div class="mt-1">
                                    @if($registration->status === 'draft')
                                        <span class="badge bg-gray-100 border border-gray-200 text-gray-700 font-medium rounded-full px-3 py-1 text-sm">Draft</span>
                                    @elseif($registration->status === 'submitted')
                                        <span class="badge bg-blue-50 border border-blue-200 text-blue-800 font-medium rounded-full px-3 py-1 text-sm">Submitted</span>
                                    @elseif($registration->status === 'under_review')
                                        <span class="badge bg-amber-50 border border-amber-200 text-amber-800 font-medium rounded-full px-3 py-1 text-sm">Sedang Ditinjau</span>
                                    @elseif($registration->status === 'verified')
                                        <span class="badge bg-indigo-50 border border-indigo-200 text-indigo-800 font-medium rounded-full px-3 py-1 text-sm">Terverifikasi</span>
                                    @elseif($registration->status === 'accepted')
                                        <span class="badge bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium rounded-full px-3 py-1 text-sm">Diterima</span>
                                    @elseif($registration->status === 'rejected')
                                        <span class="badge bg-rose-50 border border-rose-200 text-rose-800 font-medium rounded-full px-3 py-1 text-sm">Ditolak</span>
                                    @else
                                        <span class="badge bg-gray-200 border border-gray-300 text-gray-600 font-medium rounded-full px-3 py-1 text-sm">Mengundurkan Diri</span>
                                    @endif
                                </div>
                            </div>

                            @if($registration->notes)
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-xs text-gray-600">
                                    <b>Catatan Admin:</b> {{ $registration->notes }}
                                </div>
                            @endif

                            <form action="{{ route('admin.ppdb.registrations.update_status', $registration->id) }}" method="POST" class="border-t border-gray-100 pt-4 space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Ubah Status</label>
                                    <select name="status" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                        <option value="draft" {{ $registration->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ $registration->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="under_review" {{ $registration->status === 'under_review' ? 'selected' : '' }}>Sedang Ditinjau (Under Review)</option>
                                        <option value="verified" {{ $registration->status === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                        <option value="accepted" {{ $registration->status === 'accepted' ? 'selected' : '' }}>Diterima (Lulus)</option>
                                        <option value="rejected" {{ $registration->status === 'rejected' ? 'selected' : '' }}>Ditolak (Tidak Lulus)</option>
                                        <option value="withdrawn" {{ $registration->status === 'withdrawn' ? 'selected' : '' }}>Mengundurkan Diri</option>
                                    </select>
                                </div>

                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Catatan Internal / Pesan Kelulusan</label>
                                    <textarea name="notes" placeholder="Tuliskan catatan tambahan (terlihat oleh pendaftar saat cek status kelulusan)..." class="textarea textarea-bordered w-full h-24 rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">{{ old('notes', $registration->notes) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-full rounded-lg text-sm font-bold active:scale-[0.98]">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
