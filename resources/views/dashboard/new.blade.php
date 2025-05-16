@extends('layouts.dashboard')

@section('content')
<div id="app">
    <dashboard></dashboard>
</div>
@endsection

@push('scripts')
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
@endpush
