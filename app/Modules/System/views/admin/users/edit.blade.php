<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Errors Alert -->
            @if($errors->any())
                <div class="alert alert-error mb-6 bg-rose-50 border-rose-200 text-rose-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="max-w-xl">
                        @csrf
                        @method('PUT')

                        <div class="form-control w-full mb-4">
                            <label class="label font-semibold text-gray-700">
                                <span class="label-text">Nama Lengkap</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input input-bordered w-full" required>
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label font-semibold text-gray-700">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input input-bordered w-full" required>
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label font-semibold text-gray-700">
                                <span class="label-text">Role</span>
                            </label>
                            <select name="role" class="select select-bordered w-full" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 border-t border-gray-100 pt-4">
                            <p class="text-sm text-gray-400 mb-2">Kosongkan kolom sandi di bawah jika Anda tidak ingin merubah sandi pengguna.</p>
                            
                            <div class="form-control w-full mb-4">
                                <label class="label font-semibold text-gray-700">
                                    <span class="label-text">Password Baru (Opsional)</span>
                                </label>
                                <input type="password" name="password" class="input input-bordered w-full">
                            </div>

                            <div class="form-control w-full mb-6">
                                <label class="label font-semibold text-gray-700">
                                    <span class="label-text">Konfirmasi Password Baru</span>
                                </label>
                                <input type="password" name="password_confirmation" class="input input-bordered w-full">
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
