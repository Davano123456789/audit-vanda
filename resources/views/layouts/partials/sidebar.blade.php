@php
    $user = auth()->user();

    // Daftar menu: [judul grup => [[route, pola aktif, ikon, label], ...]]
    $menus = ['' => [['dashboard', 'dashboard', 'layout-dashboard', 'Dashboard']]];

    if ($user->isAdmin()) {
        $menus['Data Master'] = [
            ['master.domain.index', 'master.domain.*', 'layers', 'Domain'],
            ['master.proses.index', 'master.proses.*', 'workflow', 'Process'],
            ['master.atribut.index', 'master.atribut.*', 'list-tree', 'Process Attribute'],
            ['master.pertanyaan.index', 'master.pertanyaan.*', 'clipboard-list', 'Question'],
            ['master.pengguna.index', 'master.pengguna.*', 'users', 'Pengguna'],
        ];
        $menus['Penilaian'] = [['assessment.index', 'assessment.*,laporan.*', 'folder-kanban', 'Assessment']];
    } else {
        $menus['Penilaian'] = [['penilaian.index', 'penilaian.*,laporan.*', 'clipboard-check', 'Assessment Saya']];
    }

    $menus['Akun'] = [['profil.edit', 'profil.*', 'user-round', 'Profil Saya']];
@endphp

{{-- Overlay untuk layar kecil --}}
<div id="sidebar-overlay" class="no-print fixed inset-0 z-30 hidden bg-black/30 lg:hidden" data-sidebar-close></div>

<aside id="sidebar"
       class="no-print fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-900 transition-transform duration-200 lg:static lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex h-16 items-center border-b border-white/10 px-5">
        <div class="min-w-0">
            <p class="text-[13px] font-bold leading-tight text-white">Maturity Level</p>
            <p class="text-[11px] text-brand-200">ITIL V3 Service Operation</p>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
        @foreach ($menus as $heading => $items)
            <div>
                @if ($heading)
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-brand-300">{{ $heading }}</p>
                @endif

                <div class="space-y-1">
                    @foreach ($items as [$route, $active, $icon, $label])
                        @php
                            // Menu yang route-nya belum dibuat tetap tampil, tapi tidak bisa diklik
                            $exists = Route::has($route);
                            $isActive = $exists && request()->routeIs(...explode(',', $active));
                        @endphp
                        <a href="{{ $exists ? route($route) : '#' }}"
                           @class([
                               'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                               'bg-white text-brand-800 shadow-sm' => $isActive,
                               'text-brand-100 hover:bg-white/10 hover:text-white' => ! $isActive && $exists,
                               'cursor-not-allowed text-brand-100/40' => ! $exists,
                           ])>
                            <i data-lucide="{{ $icon }}" class="h-4 w-4 shrink-0"></i>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach

                    @if ($heading === 'Akun')
                        <form method="POST" action="{{ route('logout') }}"
                              data-confirm="Keluar dari sistem?"
                              data-confirm-text="Anda perlu login kembali untuk mengakses sistem."
                              data-confirm-button="Ya, keluar">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-brand-100 transition-colors hover:bg-red-500/20 hover:text-white">
                                <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </nav>
</aside>
