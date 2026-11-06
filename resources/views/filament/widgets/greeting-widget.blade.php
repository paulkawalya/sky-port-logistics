<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-900 p-6 text-white shadow-xl border border-slate-800">
        <!-- Background subtle decorative pattern -->
        <div class="absolute -right-8 -top-8 h-48 w-48 rounded-full bg-blue-500/10 blur-2xl"></div>
        <div class="absolute right-32 -bottom-8 h-32 w-32 rounded-full bg-indigo-500/10 blur-xl"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <!-- Left user & system summary -->
            <div class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-300 border border-blue-400/20">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ auth()->user()->user_type ?? 'Staff' }}
                    </span>
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <x-heroicon-m-calendar class="w-3.5 h-3.5 text-slate-400" />
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white md:text-3xl">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-sm text-slate-300 max-w-xl">
                    Sky Port Logistics Enterprise Operations Center. Monitor active cargo in transit, warehouse storage, billing, and budget allocations in real-time.
                </p>
            </div>

            <!-- Right quick actions -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ \App\Filament\Resources\ShippmentResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 transition-all focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <x-heroicon-m-plus-circle class="w-4 h-4" />
                    <span>New Cargo Consignment</span>
                </a>

                <a href="{{ \App\Filament\Resources\ManualInvoiceResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-slate-800/90 border border-slate-700 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition-all focus:ring-2 focus:ring-slate-500 focus:outline-none">
                    <x-heroicon-m-document-currency-dollar class="w-4 h-4 text-emerald-400" />
                    <span>Manual Invoice</span>
                </a>

                <a href="{{ \App\Filament\Resources\RequisitionResource::getUrl('create') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-slate-800/90 border border-slate-700 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-slate-700 transition-all focus:ring-2 focus:ring-slate-500 focus:outline-none">
                    <x-heroicon-m-clipboard-document-check class="w-4 h-4 text-amber-400" />
                    <span>Budget Requisition</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
