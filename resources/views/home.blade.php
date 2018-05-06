@extends('layout.app')

@section('title', 'Super-heroes manager')

@section('content')
    <div class="row">
        <div class="col-12">
            <superheroes-insert-form></superheroes-insert-form>
        </div>
    </div>

    <div class="row justify-content-center">
        @if ($superheroes->total() == 0)
            <div class="col-10 list-group-item text-center">No records.</div>
        @else
            <div class="container list-group-item">
                @include('layout.pagination', $pagination)

                <div class="row justify-content-center justify-content-md-start">
                    @foreach ($superheroes as $superhero)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <superhero :superhero-id="{{ $superhero->id }}" image="{{ $superhero->avatar }}" nickname="{{ $superhero->nickname }}"></superhero>
                        </div>
                    @endforeach
                </div>

                @php $pagination['bottom'] = true; @endphp
                @include('layout.pagination', $pagination)
            </div>
        @endif
    </div>
@endsection