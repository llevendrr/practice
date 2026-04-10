@extends('layouts.app')

@section('title', __('pages.delivery.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <div>
                <h2>{{ __('pages.delivery.heading') }}</h2>
                <p>{{ __('pages.delivery.subtitle') }}</p>
            </div>
        </div>
        <div class="section-grid">
            <div class="feature-card">
                <h3>{{ __('pages.delivery.methods.nova_title') }}</h3>
                <p>{{ __('pages.delivery.methods.nova_text') }}</p>
            </div>
            <div class="feature-card">
                <h3>{{ __('pages.delivery.methods.ukr_title') }}</h3>
                <p>{{ __('pages.delivery.methods.ukr_text') }}</p>
            </div>
            <div class="feature-card">
                <h3>{{ __('pages.delivery.methods.pickup_title') }}</h3>
                <p>{{ __('pages.delivery.methods.pickup_text') }}</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <h2>{{ __('pages.delivery.payment_returns_title') }}</h2>
        </div>
        <div class="grid-cards">
            <article class="product-card">
                <h3>{{ __('pages.delivery.payment_title') }}</h3>
                <p>{{ __('pages.delivery.payment_text') }}</p>
            </article>
            <article class="product-card">
                <h3>{{ __('pages.delivery.returns_title') }}</h3>
                <p>{{ __('pages.delivery.returns_text') }}</p>
            </article>
        </div>
        <p class="muted-note" style="color: var(--muted); font-size: 0.9rem;">{{ __('pages.delivery.note') }}</p>
    </section>
@endsection
