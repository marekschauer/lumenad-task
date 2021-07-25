@extends('layouts.app')

@section('title', ' - Home')

@section('content')
<div class="container">
    <h1 class="text-center font-bold text-3xl">Select the data origin</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex flex-col md:grid md:grid-cols-2 md:gap-4">
        <div class="text-center">
            <i class="fas fa-file-csv text-7xl p-5"></i>
            <form method="POST" action="{{ route('csv.upload') }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file">
                <input type="submit" class="button">
            </form>
        </div>
        <div class="text-center">
            <i class="fas fa-search text-7xl p-5"></i>
            <csv-database-search />
        </div>
    </div>
</div>
@endsection
