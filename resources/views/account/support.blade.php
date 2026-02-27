@extends('layouts.app')

@section('title', 'Підтримка')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    @include('account.partials.tabs')

    <section class="section support-page">
        <div class="support-grid">
            <div class="support-sidebar">
                <div class="support-panel">
                    <div class="support-panel__header">
                        <h2>Ваші звернення</h2>
                        <p>Напишіть нам, і ми відповімо якомога швидше.</p>
                    </div>
                    <div class="support-thread-list">
                        @forelse ($threads as $thread)
                            @php
                                $previewTime = optional($thread->latestMessage)->created_at ?? $thread->created_at;
                                $previewText = $thread->latestMessage->message ?? 'Чекає на відповідь';
                            @endphp
                            <a
                                href="{{ route('support.show', $thread) }}"
                                class="support-thread {{ optional($activeThread)->id === $thread->id ? 'active' : '' }}"
                            >
                                <div class="support-thread__top">
                                    <strong>{{ $thread->subject }}</strong>
                                    <span class="status-badge">{{ $thread->status_label }}</span>
                                </div>
                                <p>{{ Str::limit($previewText, 80) }}</p>
                                <small class="muted-note">
                                    {{ $previewTime->format('d.m.Y H:i') }}
                                </small>
                            </a>
                        @empty
                            <p class="muted-note">Ще немає звернень. Напишіть першими.</p>
                        @endforelse
                    </div>
                </div>
                <form action="{{ route('support.store') }}" method="post" class="support-form">
                    @csrf
                    <h3>Нове звернення</h3>
                    <div class="field-group">
                        <label for="subject">Тема</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}" />
                        @error('subject')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="field-group">
                        <label for="initial_message">Повідомлення</label>
                        <textarea id="initial_message" name="initial_message">{{ old('initial_message') }}</textarea>
                        @error('initial_message')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn">Надіслати</button>
                </form>
            </div>

            <div class="support-chat">
                @if ($activeThread)
                    <div class="support-chat__header">
                        <div>
                            <h2>{{ $activeThread->subject }}</h2>
                            <p class="muted-note">Статус: {{ $activeThread->status_label }}</p>
                        </div>
                        <span class="status-badge status-badge--ghost">{{ $activeThread->user->name }}</span>
                    </div>
                    <div class="support-messages" data-chat-scroll>
                        @foreach ($activeThread->messages as $message)
                            <div class="chat-bubble {{ $message->user->isAdmin() ? 'chat-bubble--admin' : 'chat-bubble--user' }}">
                                <p>{{ $message->message }}</p>
                                <span class="chat-meta">
                                    {{ $message->user->name }} · {{ $message->created_at->format('d.m.Y H:i') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('support.messages.store', $activeThread) }}" method="post" class="support-chat__form">
                        @csrf
                        <div class="field-group">
                            <label for="message">Ваше повідомлення</label>
                            <textarea id="message" name="message">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn">Надіслати</button>
                    </form>
                @else
                    <p class="muted-note">Створіть звернення, щоб почати чат із підтримкою.</p>
                @endif
            </div>
        </div>
    </section>
@endsection
