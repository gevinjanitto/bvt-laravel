@extends('layouts.site')
@section('title', $policy['title'] . ' — ' . site('brand.name'))
@section('content')
<section class="max-w-4xl mx-auto px-6 py-20" data-testid="policy-page"><h1 class="font-display text-4xl font-bold text-ink" data-testid="policy-title">{{ $policy['title'] }}</h1><p class="whitespace-pre-line mt-8 leading-relaxed text-sand" data-testid="policy-body">{{ $policy['body'] }}</p></section>
@endsection
