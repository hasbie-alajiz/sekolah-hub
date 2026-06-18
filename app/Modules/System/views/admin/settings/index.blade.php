<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6 max-w-xl">
                            @foreach($settings as $setting)
                                <div class="form-control w-full">
                                    <label class="label font-semibold text-gray-700">
                                        <span class="label-text">{{ $setting->description ?: $setting->key }}</span>
                                    </label>
                                    
                                    @if($setting->key === 'theme.active')
                                        <select name="settings[{{ $setting->key }}]" class="select select-bordered w-full">
                                            <option value="school-classic" {{ $setting->value === 'school-classic' ? 'selected' : '' }}>School Classic</option>
                                        </select>
                                    @elseif($setting->key === 'cloudflare.turnstile.secret_key')
                                        <input type="password" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input input-bordered w-full" placeholder="Turnstile secret key">
                                    @else
                                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="input input-bordered w-full">
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-start">
                            <button type="submit" class="btn btn-primary">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
