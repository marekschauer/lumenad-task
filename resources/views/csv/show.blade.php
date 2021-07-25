@extends('layouts.app')

@section('title', ' - Home')

@section('content')
<div class="container">
    <csv-display header="{{ json_encode($csv_header) }}" body="{{ json_encode($csv_data) }}">
    </csv-display>
</div>
@endsection
