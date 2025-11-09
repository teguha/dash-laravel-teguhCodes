<div class="flex flex-row lg:flex-wrap items-center gap-2 justify-end">
    @if(!empty($fields['add']))
        <button onclick="openModal()" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
            <i class="fas fa-file mr-2"></i>
            Add New
        </button>
    @endif


    @if(!empty($fields['export']))
        <button class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
            <i class="fas fa-file-excel mr-2"></i>
            Export Excel
        </button>
        <button class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
            <i class="fas fa-file-pdf mr-2"></i>
            Export PDF
        </button>
    @endif

    @if(!empty($fields['import']))
        <label class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium cursor-pointer">
            <i class="fas fa-file-import mr-2"></i>
            Import Excel
            <input type="file" accept=".xlsx,.xls" class="hidden" id="importExcel">
        </label>
    @endif
</div>