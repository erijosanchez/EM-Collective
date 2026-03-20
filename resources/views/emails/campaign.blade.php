@extends('emails.layout')
@php $unsubscribeUrl = $trackingToken ? route('newsletter.unsubscribe', $trackingToken) : null; @endphp

@section('body')
{{-- Pixel de tracking de apertura --}}
@if($trackingToken)
<img src="{{ route('newsletter.track', $trackingToken) }}" width="1" height="1" alt="" style="display: block; width: 1px; height: 1px; border: 0;">
@endif

{!! $campaign->content !!}
@endsection
