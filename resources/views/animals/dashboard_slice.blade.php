@foreach ($animals as $a)
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 dashboard-box">
        @include('animals.show_slice', ['animal' => $a])

        @if(isset($show_extended))
            @include('terraria.show_slice', ['terrarium' => $animal->terrarium])
        @endif
    </div>
@endforeach