@extends('layouts.printables')

@section('content')
    @include('partials.invoice')
@endsection

@push('scripts')
    <script>
        window.print();
    </script>
@endpush
