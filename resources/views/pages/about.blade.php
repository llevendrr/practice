@extends('layouts.app')

@section('title', __('pages.about.title'))

@section('content')
    <section class="section">
        <div class="section-heading">
            <div>
                <h2>{{ __('pages.about.heading') }}</h2>
                <p>{{ __('pages.about.subtitle') }}</p>
            </div>
        </div>
        <div class="section-grid">
            <div class="feature-card">
                <h3>{{ __('pages.about.cards.warranty_title') }}</h3>
                <p>{{ __('pages.about.cards.warranty_text') }}</p>
            </div>
            <div class="feature-card">
                <h3>{{ __('pages.about.cards.service_title') }}</h3>
                <p>{{ __('pages.about.cards.service_text') }}</p>
            </div>
            <div class="feature-card">
                <h3>{{ __('pages.about.cards.delivery_title') }}</h3>
                <p>{{ __('pages.about.cards.delivery_text') }}</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <h2>{{ __('pages.about.advantages_title') }}</h2>
        </div>
        <div class="grid-cards">
            <article class="product-card">
                <h3>{{ __('pages.about.advantages.expert_title') }}</h3>
                <p>{{ __('pages.about.advantages.expert_text') }}</p>
            </article>
            <article class="product-card">
                <h3>{{ __('pages.about.advantages.clear_service_title') }}</h3>
                <p>{{ __('pages.about.advantages.clear_service_text') }}</p>
            </article>
            <article class="product-card">
                <h3>{{ __('pages.about.advantages.innovation_title') }}</h3>
                <p>{{ __('pages.about.advantages.innovation_text') }}</p>
            </article>
        </div>
    </section>
@endsection
