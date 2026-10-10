@props(['judul', 'penulis', 'tahun'])

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition duration-200 p-6 flex flex-col justify-between h-full">
    <div>
        <div class="flex justify-between items-center mb-3">
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wider">
                {{ $slot }}
            </span>
            <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md">
                {{ $tahun }}
            </span>
        </div>
        <h3 class="text-base font-bold text-slate-900 mb-2 leading-snug line-clamp-2">
            {{ $judul }}
        </h3>
        <p class="text-xs text-slate-500">
            Penulis: <span class="font-medium text-slate-700">{{ $penulis }}</span>
        </p>
    </div>

    <div class="mt-6 pt-4 border-t border-slate-100">
        {{ $footerSlot ?? '' }}
    </div>
</div>