@extends('user.layouts.master')

@section('content')

@include('user.home.sections.hero')
@include('user.home.sections.products')
@include('user.home.sections.banner')
@include('user.home.sections.featured_products', [
    'featuredProducts' => $featuredProducts ?? collect()
])
@include('user.home.sections.facts')
@include('user.home.sections.testimonial')

@endsection
