@extends('master')

@section('breadcrumbs')
    <a href="/biography_entries" class="breadcrumb hide-on-small-and-down">@choice('components.biography_entries', 2)</a>
    <a href="/biography_entries/{{ $entry->id }}" class="breadcrumb hide-on-small-and-down">{{ $entry->name }}</a>
@stop

@section('content')
<div class="container">
    <div class="card-content">
        <biography_entries-widget entry-id="{{ $entry->id }}"></biography_entries-widget>
    </div>
</div>
@stop