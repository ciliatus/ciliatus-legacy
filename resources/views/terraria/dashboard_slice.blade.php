@foreach ($terraria as $t)
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-xs-12 dashboard-box">
        @include('terraria.show_slice', ['terrarium' => $t])

        @if(isset($show_extended))
            @foreach ($t->animals as $a)
                @include('animals.show_slice', ['animal' => $a])
            @endforeach
        @endif
    </div>
@endforeach