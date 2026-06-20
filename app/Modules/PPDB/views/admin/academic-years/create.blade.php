<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Tahun Ajaran PPDB') }}
            </h2>
            <a href="{{ route('admin.ppdb.academic-years.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <form action="{{ route('admin.ppdb.academic-years.store') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Tahun Ajaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Tahun Ajaran 2026/2027" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('name') input-error @enderror" required />
                            @error('name')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Kode <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: 2026/2027" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('code') input-error @enderror" required />
                            @error('code')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Tanggal & Waktu Pendaftaran Dibuka <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="registration_open_at" value="{{ old('registration_open_at') }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('registration_open_at') input-error @enderror" required />
                            @error('registration_open_at')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Tanggal & Waktu Pendaftaran Ditutup <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="registration_close_at" value="{{ old('registration_close_at') }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('registration_close_at') input-error @enderror" required />
                            @error('registration_close_at')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Tanggal & Waktu Pengumuman Kelulusan <span class="text-rose-500">*</span></label>
                            <input type="datetime-local" name="announcement_at" value="{{ old('announcement_at') }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('announcement_at') input-error @enderror" required />
                            @error('announcement_at')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="flex items-center gap-3 cursor-pointer justify-start">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="checkbox checkbox-primary checkbox-sm rounded" />
                                <span class="text-sm text-gray-700 select-none font-semibold">Jadikan Tahun Ajaran Aktif (Otomatis menonaktifkan tahun ajaran aktif lainnya)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.ppdb.academic-years.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
