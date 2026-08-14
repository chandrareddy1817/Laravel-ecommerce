@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="alert alert-success">
        Welcome, {{ auth()->user()->name }}!
    </div>

</div>

@endsection