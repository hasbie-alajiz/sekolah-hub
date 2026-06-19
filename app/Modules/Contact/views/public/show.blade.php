<x-public-layout>
    @section('title', 'Hubungi Kami - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumbs -->
        <div class="text-sm breadcrumbs mb-10 text-gray-500">
            <ul>
                <li><a href="/" class="hover:text-primary transition-colors">Beranda</a></li>
                <li class="text-gray-400">Hubungi Kami</li>
            </ul>
        </div>

        <!-- Header -->
        <div class="mb-12 text-center md:text-left">
            <span class="inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-primary mb-3">
                HUBUNGI KAMI
            </span>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                {{ $themeSettings['contact_title'] ?? 'Hubungi Kami' }}
            </h1>
            @if(!empty($themeSettings['contact_subtitle']))
                <p class="text-gray-500 text-sm mt-2 max-w-xl">
                    {{ $themeSettings['contact_subtitle'] }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Info & Map (5 columns) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Info cards -->
                <div class="space-y-4">
                    <!-- Address -->
                    <div class="flex gap-4 items-start p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                        <div class="text-primary mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Alamat Sekolah</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                {{ $themeSettings['contact_address'] ?? 'Jl. Pendidikan No. 12, Jakarta' }}
                            </p>
                        </div>
                    </div>

                    <!-- Call & Email Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Phone -->
                        <div class="flex gap-4 items-start p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="text-primary mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Telepon</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $themeSettings['contact_phone'] ?? '+62 21-789-0123' }}
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex gap-4 items-start p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="text-primary mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Email</h4>
                                <p class="text-xs text-gray-500 mt-1 truncate max-w-[150px]" title="{{ $themeSettings['contact_email'] ?? 'info@sekolah.sch.id' }}">
                                    {{ $themeSettings['contact_email'] ?? 'info@sekolah.sch.id' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                @if(!empty($themeSettings['contact_maps_embed']))
                    <div class="aspect-video w-full rounded-2xl overflow-hidden border border-gray-150 shadow-sm bg-gray-50">
                        <iframe 
                            src="{{ $themeSettings['contact_maps_embed'] }}" 
                            class="w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                @endif
            </div>

            <!-- Right: Form (7 columns) -->
            <div class="lg:col-span-7 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                <!-- Alerts inside form container -->
                @if(session('success'))
                    <div class="alert alert-success bg-emerald-50 border-emerald-100 text-emerald-800 p-4 rounded-xl flex items-center mb-6 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @error('email')
                    @if(str_contains($message, 'Terlalu banyak'))
                        <div class="alert alert-error bg-rose-50 border-rose-100 text-rose-800 p-4 rounded-xl mb-6 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5 mr-2 text-rose-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @endif
                @enderror

                @if($errors->any() && !session('success') && !$errors->has('email'))
                    <div class="alert alert-error bg-rose-50 border-rose-100 text-rose-800 p-4 rounded-xl mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div class="form-control w-full">
                        <label class="label pb-1">
                            <span class="label-text font-semibold text-gray-700 text-xs">Nama Lengkap</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full text-sm h-10 rounded-lg @error('name') input-error @enderror" placeholder="Masukkan nama lengkap Anda" required />
                        @error('name')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div class="form-control w-full">
                            <label class="label pb-1">
                                <span class="label-text font-semibold text-gray-700 text-xs">Alamat Email</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input input-bordered w-full text-sm h-10 rounded-lg @error('email') input-error @enderror" placeholder="contoh@email.com" required />
                            @error('email')
                                @if(!str_contains($message, 'Terlalu banyak'))
                                    <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-control w-full">
                            <label class="label pb-1">
                                <span class="label-text font-semibold text-gray-700 text-xs">Nomor Telepon (Opsional)</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered w-full text-sm h-10 rounded-lg @error('phone') input-error @enderror" placeholder="0812xxxxxx" />
                            @error('phone')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="form-control w-full">
                        <label class="label pb-1">
                            <span class="label-text font-semibold text-gray-700 text-xs">Subjek Pesan</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="input input-bordered w-full text-sm h-10 rounded-lg @error('subject') input-error @enderror" placeholder="Tuliskan tujuan pesan" required />
                        @error('subject')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="form-control w-full">
                        <label class="label pb-1">
                            <span class="label-text font-semibold text-gray-700 text-xs">Isi Pesan</span>
                        </label>
                        <textarea name="message" class="textarea textarea-bordered w-full text-sm rounded-lg min-h-[100px] @error('message') textarea-error @enderror" placeholder="Ketik pesan Anda di sini..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cloudflare Turnstile -->
                    @if(!empty($turnstileSiteKey))
                        <div class="pt-2">
                            <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"></div>
                            @error('cf-turnstile-response')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary w-full rounded-lg text-sm font-bold active:scale-[0.98]">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Turnstile JS Script Loader -->
    @if(!empty($turnstileSiteKey))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</x-public-layout>
