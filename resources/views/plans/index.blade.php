@extends('layouts.app')

@section('content')
    <subscription-form :plans="{{ $plans }}"></subscription-form>
@endsection
