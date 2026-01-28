@props(['forms'])

@foreach ($forms as $form)
    @if ($form['startPage'] === $form['endPage'])
        <div class="page-number" data-start-page="{{ $form['startPage'] }}" data-current-page="{{ $form['startPage'] }}">
            <div class="page-number-display">Página {{ $form['startPage'] }} de 34</div>
        </div>
    @else
        @for ($page = $form['startPage']; $page <= $form['endPage']; $page++)
            <div class="page-number" data-start-page="{{ $form['startPage'] }}" data-current-page="{{ $page }}">
                <div class="page-number-display">Página {{ $page }} de 34</div>
            </div>
        @endfor
    @endif
@endforeach