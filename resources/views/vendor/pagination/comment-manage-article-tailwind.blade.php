@if ($paginator->hasPages())
    <nav role="navigation" class="flex justify-center my-4">
        <ul class="inline-flex items-center space-x-1">

            {{-- Previous Arrow --}}
            @if ($paginator->onFirstPage())
                <li class="text-gray-400 cursor-not-allowed">&lsaquo;</li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="text-orange-500 hover:underline">&lsaquo;</a>
                </li>
            @endif

            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $range = 5;
                $half = floor($range / 2);

                if ($last <= $range) {
                    $start = 1;
                    $end = $last;
                } elseif ($current <= $half + 1) {
                    $start = 1;
                    $end = $range;
                } elseif ($current >= $last - $half) {
                    $start = $last - ($range - 1);
                    $end = $last;
                } else {
                    $start = $current - $half;
                    $end = $current + $half;
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li>
                        <span class="px-3 py-1 bg-orange-500 text-white rounded-full font-bold">{{ $i }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}" class="px-3 py-1 text-gray-700 hover:text-orange-500">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Next Arrow --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="text-orange-500 hover:underline">&rsaquo;</a>
                </li>
            @else
                <li class="text-gray-400 cursor-not-allowed">&rsaquo;</li>
            @endif

        </ul>
    </nav>
@endif
