@extends('layouts.master')

@section('title', 'Profil Saya')

@section('content')
    @php
        // Tab yang terbuka: "password" jika ada error/berhasil ganti password, selain itu "profil"
        $activeTab = $errors->password->any() || session('tab') === 'password' ? 'password' : 'profil';
    @endphp

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Kartu profil --}}
        <div class="card overflow-hidden lg:self-start">
            <div class="h-24 bg-gradient-to-r from-brand-900 via-brand-700 to-brand-500"></div>

            <div class="-mt-12 px-6 pb-6 text-center">
                @if ($user->photo_url)
                    <img src="{{ $user->photo_url }}" alt="Foto {{ $user->name }}" class="mx-auto h-24 w-24 rounded-2xl object-cover ring-4 ring-white">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-brand-100 text-3xl font-bold text-brand-700 ring-4 ring-white">
                        {{ $user->initials }}
                    </div>
                @endif

                <h2 class="mt-4 text-lg font-bold">{{ $user->name }}</h2>
                <span class="badge {{ $user->isAdmin() ? 'badge-amber' : 'badge-blue' }} mt-1">{{ $user->role_label }}</span>
            </div>

            @if ($user->isAsesor())
                <div class="grid grid-cols-2 border-y border-gray-100 text-center">
                    <div class="border-r border-gray-100 py-4">
                        <p class="text-2xl font-bold">{{ $assessmentCount }}</p>
                        <p class="text-xs text-gray-500">Assessment ditugaskan</p>
                    </div>
                    <div class="py-4">
                        <p class="text-2xl font-bold">{{ $completedCount }}</p>
                        <p class="text-xs text-gray-500">Assessment selesai</p>
                    </div>
                </div>
            @endif

            <ul class="space-y-4 px-6 py-5 text-sm">
                @foreach ([
                    ['mail', 'Email', $user->email],
                    ['phone', 'Telepon', $user->phone],
                    ['map-pin', 'Alamat', $user->address],
                    ['calendar', 'Terdaftar sejak', $user->created_at?->translatedFormat('d F Y')],
                ] as [$icon, $label, $value])
                    <li class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500">{{ $label }}</p>
                            <p class="break-words font-medium">{{ $value ?: '-' }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Tab pengaturan akun --}}
        <div class="card lg:col-span-2">
            <div class="flex gap-1 border-b border-gray-100 px-4 pt-3">
                @foreach (['profil' => ['user-pen', 'Edit Profil'], 'password' => ['key-round', 'Ganti Password']] as $tab => [$icon, $label])
                    <button type="button" data-tab="{{ $tab }}"
                            class="tab-button -mb-px inline-flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition">
                        <i data-lucide="{{ $icon }}" class="h-4 w-4"></i> {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Tab: edit profil --}}
            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" data-panel="profil" novalidate>
                @csrf
                @method('PUT')

                <div class="space-y-6 p-6">
                    {{-- Foto --}}
                    <div class="flex flex-col gap-4 rounded-xl border border-dashed border-gray-300 p-4 sm:flex-row sm:items-center">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
                            <i data-lucide="image-plus" class="h-6 w-6"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">Foto Profil</p>
                            <p class="text-xs text-gray-500">JPG, PNG, atau WEBP, maksimal 2 MB.</p>
                            @error('photo') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
                                   class="block text-sm text-gray-500 file:mr-3 file:rounded-md file:border-0 file:bg-brand-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-brand-700">
                            @if ($user->photo)
                                <label class="flex items-center gap-2 text-xs text-gray-600">
                                    <input type="checkbox" name="remove_photo" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600">
                                    Hapus foto saat ini
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="label">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input id="name" name="name" value="{{ old('name', $user->name) }}" class="input @error('name') input-error @enderror">
                            @error('name') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="label">Email <span class="text-red-500">*</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="input @error('email') input-error @enderror">
                            @error('email') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="label">Nomor Telepon</label>
                            <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" class="input @error('phone') input-error @enderror">
                            @error('phone') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="address" class="label">Alamat</label>
                            <input id="address" name="address" value="{{ old('address', $user->address) }}" class="input @error('address') input-error @enderror">
                            @error('address') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-100 px-6 py-4">
                    <button type="submit" class="btn btn-primary"><i data-lucide="save" class="h-4 w-4"></i> Simpan Perubahan</button>
                </div>
            </form>

            {{-- Tab: ganti password (error-nya memakai error bag "password") --}}
            <form method="POST" action="{{ route('profil.password') }}" data-panel="password" class="hidden" novalidate>
                @csrf
                @method('PUT')

                <div class="space-y-5 p-6">
                    <div class="flex items-start gap-3 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                        <i data-lucide="shield-alert" class="mt-0.5 h-5 w-5 shrink-0"></i>
                        <p>Gunakan minimal 8 karakter dan berbeda dari password lama. Setelah diganti, gunakan password baru saat login berikutnya.</p>
                    </div>

                    <div class="max-w-md space-y-5">
                        @foreach ([
                            'current_password' => ['Password Saat Ini', 'current-password'],
                            'password' => ['Password Baru', 'new-password'],
                            'password_confirmation' => ['Ulangi Password Baru', 'new-password'],
                        ] as $field => [$label, $autocomplete])
                            <div>
                                <label for="{{ $field }}" class="label">{{ $label }} <span class="text-red-500">*</span></label>
                                <input id="{{ $field }}" name="{{ $field }}" type="password" autocomplete="{{ $autocomplete }}"
                                       class="input {{ $errors->password->has($field) ? 'input-error' : '' }}">
                                @if ($errors->password->has($field))
                                    <p class="error-text">{{ $errors->password->first($field) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-100 px-6 py-4">
                    <button type="submit" class="btn btn-primary"><i data-lucide="key-round" class="h-4 w-4"></i> Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Perpindahan tab Edit Profil / Ganti Password
        (function () {
            const buttons = document.querySelectorAll('.tab-button');
            const panels = document.querySelectorAll('[data-panel]');

            const open = (tab) => {
                buttons.forEach(button => {
                    const active = button.dataset.tab === tab;
                    button.classList.toggle('border-brand-600', active);
                    button.classList.toggle('text-brand-700', active);
                    button.classList.toggle('border-transparent', !active);
                    button.classList.toggle('text-gray-500', !active);
                });
                panels.forEach(panel => panel.classList.toggle('hidden', panel.dataset.panel !== tab));
            };

            buttons.forEach(button => button.addEventListener('click', () => open(button.dataset.tab)));
            open(@json($activeTab));
        })();
    </script>
@endpush
