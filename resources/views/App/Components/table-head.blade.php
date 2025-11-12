<thead>
    <tr class="bg-gradient-to-r border-b border-gray-200 from-gray-50 to-gray-100">
        {{-- Fixed kolom --}}
        <th class="px-6 py-4 text-left border-r border-gray-200">
            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        </th>
        <th class="px-6 py-4 text-left border-r border-gray-200">
            <div class="flex items-center gap-2 font-[sans-serif] text-[12px] font-normal text-gray-500 uppercase tracking-wider cursor-pointer hover:text-blue-600">
                #
            </div>
        </th>

        {{-- Loop kolom dinamis --}}
        @foreach ($columns as $col)
            <th class="px-6 py-4 text-left border-r border-gray-200">
                @if($col['sortable'])
                    <div class="flex items-center gap-2 font-[sans-serif] text-[12px] font-normal text-gray-500 tracking-wider cursor-pointer hover:text-blue-600" data-sort="{{ $col['field'] }}">
                        {{ $col['label'] }}
                        <i class="fa fa-sort-amount-asc text-gray-400"></i>
                    </div>
                @else
                    <div class="flex items-center gap-2 font-[sans-serif] text-[12px] font-normal text-gray-500 tracking-wider">
                        {{ $col['label'] }}
                    </div>
                @endif
            </th>
        @endforeach

        {{-- Fixed kolom Action --}}
        <th class="px-6 py-4 text-center font-[sans-serif] text-[12px] font-normal text-gray-500 tracking-wider">
            Action
        </th>
    </tr>
</thead>
