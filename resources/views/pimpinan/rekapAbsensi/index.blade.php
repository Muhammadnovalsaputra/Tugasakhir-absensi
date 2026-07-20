<x-app-layout>
    <div class="flex min-h-screen bg-[#f8fafc]">
        <div class="flex-1 p-8">
            <form id="filter-form" method="GET" action="{{ route('pimpinan.rekapAbsensi.index') }}"
                  class="flex flex-col md:flex-row md:items-center gap-3 mb-6">

                <div class="relative w-full max-w-xs">
                    <span id="search-icon" class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                           placeholder="Search employee name..."
                           autocomplete="off"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                    <span class="text-gray-400 text-sm">to</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                </div>
                <select name="status" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="Hadir"           {{ request('status') == 'Hadir'           ? 'selected' : '' }}>Hadir</option>
                    <option value="Terlambat"       {{ request('status') == 'Terlambat'       ? 'selected' : '' }}>Terlambat</option>
                    <option value="Hadir (Koreksi)" {{ request('status') == 'Hadir (Koreksi)' ? 'selected' : '' }}>Hadir (Koreksi)</option>
                    <option value="Setengah Hari"   {{ request('status') == 'Setengah Hari'   ? 'selected' : '' }}>Setengah Hari</option>
                </select>

                <button type="submit" id="filter-submit-btn"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition flex items-center justify-center gap-2 min-w-[80px]">
                    <span id="filter-submit-text">Filter</span>
                    <svg id="filter-submit-spinner" class="hidden animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>

                <a href="{{ route('pimpinan.rekapAbsensi.index') }}" id="filter-reset-btn"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-medium transition">
                    Reset
                </a>

                <a href="{{ route('pimpinan.rekapAbsensi.export', request()->all()) }}" id="export-excel-link"
                   class="ml-auto flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm shadow-sm transition font-medium">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14.72 13L16.22 15.53H14.41L13.39 13.72L12.38 15.53H10.57L12.07 13L10.64 10.47H12.45L13.39 12.16L14.33 10.47H16.14L14.72 13Z"/>
                        <path d="M14 2H5C3.89543 2 3 2.89543 3 4V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V9L14 2ZM19 20H5V4H13V10H19V20Z"/>
                    </svg>
                    Export Excel
                </a>

                 <a href="{{ route('pimpinan.rekapAbsensi.exportPdf', request()->all()) }}" id="export-pdf-link"
                     class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm shadow-sm transition font-medium">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.819 14.427c.064.267.077.679-.021.948-.128.351-.381.528-.754.528h-.516v-2.202h.55c.246 0 .501.08.683.237.312.271.322.601.058.489zm1.33-4.981c-.167-.032-.363-.042-.55-.019v1.641l.55-.039c.333-.023.651-.218.651-.791 0-.426-.116-.752-.651-.792zm7.851-4.446v14c0 1.104-.896 2-2 2H4c-1.104 0-2-.896-2-2V4c0-1.104.896-2 2-2h10l6 6zm-6-4v4h4l-4-4zm-3 7.25c-.604-.036-1.185.068-1.586.25v5h1v-1.75h.617c.741 0 1.351-.239 1.678-.671.244-.324.354-.77.354-1.329 0-.547-.116-.967-.366-1.268-.309-.371-.777-.578-1.697-.232zm-3.5-.25h-1.5v5h1v-1.5h.5c.828 0 1.5-.672 1.5-1.5s-.672-1.5-1.5-1.5zm0 2h-.5v-1h.5c.275 0 .5.225.5.5s-.225.5-.5.5zm6.5-1c0 .275-.225.5-.5.5h-.5v-1h.5c.275 0 .5.225.5.5z"/>
                    </svg>
                    Export PDF
                </a>
            </form>
            <div id="tabel-wrapper" class="relative">
                <div id="tabel-loading-overlay"
                     class="hidden absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-sm text-gray-500">Memuat data...</span>
                    </div>
                </div>

                <div id="tabel-content">
                    @include('pimpinan.rekapAbsensi.partials.table', ['attendances' => $attendances])
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form           = document.getElementById('filter-form');
        const tabelContent    = document.getElementById('tabel-content');
        const loadingOverlay  = document.getElementById('tabel-loading-overlay');
        const submitBtn       = document.getElementById('filter-submit-btn');
        const submitText      = document.getElementById('filter-submit-text');
        const submitSpinner   = document.getElementById('filter-submit-spinner');
        const resetBtn        = document.getElementById('filter-reset-btn');
        const exportExcelLink = document.getElementById('export-excel-link');
        const exportPdfLink   = document.getElementById('export-pdf-link');
        const searchInput     = document.getElementById('search-input');
        const searchIcon      = document.getElementById('search-icon');

        let activeController = null;

        function setLoading(isLoading) {
            if (isLoading) {
                loadingOverlay.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                submitText.textContent = 'Loading...';
                submitSpinner.classList.remove('hidden');
                searchIcon.innerHTML = `
                    <svg class="animate-spin w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>`;
            } else {
                loadingOverlay.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                submitText.textContent = 'Filter';
                submitSpinner.classList.add('hidden');
                searchIcon.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>`;
            }
        }

        function buildQuery(formData) {
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                if (value !== '') params.append(key, value);
            }
            return params.toString();
        }

        function updateExportLinks(query) {
            const excelBase = exportExcelLink.dataset.baseUrl || exportExcelLink.href.split('?')[0];
            const pdfBase   = exportPdfLink.dataset.baseUrl   || exportPdfLink.href.split('?')[0];
            exportExcelLink.dataset.baseUrl = excelBase;
            exportPdfLink.dataset.baseUrl   = pdfBase;
            exportExcelLink.href = excelBase + (query ? ('?' + query) : '');
            exportPdfLink.href   = pdfBase   + (query ? ('?' + query) : '');
        }

        async function loadData(url, pushState = true) {
            if (activeController) activeController.abort();
            activeController = new AbortController();

            setLoading(true);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                    signal: activeController.signal,
                });

                if (!response.ok) throw new Error('Gagal memuat data');

                const html = await response.text();
                tabelContent.innerHTML = html;

                if (pushState) {
                    window.history.pushState({ ajaxFilter: true }, '', url);
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    tabelContent.innerHTML = `
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-12 text-center text-red-500 text-sm">
                            Terjadi kesalahan saat memuat data. Silakan coba lagi.
                        </div>`;
                    console.error(err);
                }
            } finally {
                setLoading(false);
            }
        }

        function triggerSearch(pushState = true) {
            const formData = new FormData(form);
            const query = buildQuery(formData);
            const url = form.action + (query ? ('?' + query) : '');
            updateExportLinks(query);
            loadData(url, pushState);
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            triggerSearch();
        });

        let searchDebounce = null;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(function () {
                triggerSearch();
            }, 500);
        });

        form.querySelectorAll('input[type="date"], select[name="status"]').forEach(function (el) {
            el.addEventListener('change', function () {
                triggerSearch();
            });
        });

        resetBtn.addEventListener('click', function (e) {
            e.preventDefault();
            clearTimeout(searchDebounce);
            form.reset();
            updateExportLinks('');
            loadData(resetBtn.href);
        });

        window.addEventListener('popstate', function () {
            loadData(window.location.href, false);
        });


        tabelContent.addEventListener('click', function (e) {
            const link = e.target.closest('nav[role="navigation"] a, .pagination a');
            if (!link || !link.href) return;

            e.preventDefault();
            loadData(link.href);
        });
    });
    </script>
    @endpush
</x-app-layout>