@extends('layouts.product')

@section('title', 'Test Page')

@section('content')
<div class="container py-5">
    <h1>Test Page</h1>
    <p>This is a test page to verify the layout is working.</p>
    <div class="alert alert-success">
        If you can see this, the layout is working correctly!
    </div>
</div>
@endsection
