@extends('layouts.admin')

@section('title', 'Чат підтримки')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="admin-card">
        <div class="section-heading">
            <div>
                <h2>Чат підтримки</h2>
                <p>Переглядайте всі звернення та переходьте до деталей.</p>
            </div>
            <span class="status-badge">{{ $threads->count() }} {{ Str::plural('звернення', $threads->count()) }}</span>
        </div>

        @if ($threads->isEmpty())
            <p>Поки що немає звернень.</p>
        @else
            <div class="support-admin-list">
                @foreach ($threads as $thread)
                    @php
                        $lastMessage = $thread->latestMessage;
                        $previewTime = optional($lastMessage)->created_at ?? $thread->created_at;
                    @endphp
                    <div class="support-admin-row">
                        <div>
                            <strong>{{ $thread->subject }}</strong>
                            <p class="muted-note">{{ $thread->user->name }}</p>
                            <p class="muted-note">
                                {{ Str::limit($lastMessage->message ?? 'Очікує відповіді', 90) }}
                            </p>
                        </div>
                        <div class="support-admin-row__meta">
                            <span class="status-badge">{{ $thread->status_label }}</span>
                            <small class="muted-note">{{ $previewTime->format('d.m.Y H:i') }}</small>
                        </div>
                        <a href="{{ route('admin.support.show', $thread) }}" class="secondary-btn">Відкрити</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
