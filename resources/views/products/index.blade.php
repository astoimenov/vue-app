@extends('layouts.app')

@section('content')
    <checkout-form :products="{{ $products }}"></checkout-form>
@endsection
