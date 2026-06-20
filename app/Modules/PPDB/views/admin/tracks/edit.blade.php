<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Jalur Pendaftaran') }}
            </h2>
            <a href="{{ route('admin.ppdb.tracks.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <form action="{{ route('admin.ppdb.tracks.update', $track->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Tahun Ajaran <span class="text-rose-500">*</span></label>
                            <select name="academic_year_id" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('academic_year_id') select-error @enderror" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $track->academic_year_id) == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Jalur Pendaftaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $track->name) }}" placeholder="Contoh: Jalur Zonasi, Jalur Prestasi Akademik" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('name') input-error @enderror" required />
                            @error('name')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Kuota Pendaftar (Kosongkan jika tidak terbatas)</label>
                            <input type="number" name="quota" value="{{ old('quota', $track->quota) }}" min="1" placeholder="Contoh: 100" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('quota') input-error @enderror" />
                            @error('quota')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Deskripsi Jalur</label>
                            <textarea name="description" placeholder="Penjelasan singkat mengenai persyaratan atau ketentuan jalur ini..." class="textarea textarea-bordered w-full h-24 rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('description') textarea-error @enderror">{{ old('description', $track->description) }}</textarea>
                            @error('description')
                                <span class="text-rose-600 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control mt-4">
                            <label class="flex items-center gap-3 cursor-pointer justify-start">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $track->is_active) ? 'checked' : '' }} class="checkbox checkbox-primary checkbox-sm rounded" />
                                <span class="text-sm text-gray-700 select-none font-semibold">Aktifkan Jalur ini</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.ppdb.tracks.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
