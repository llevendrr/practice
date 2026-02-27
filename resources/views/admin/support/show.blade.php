@extends('layouts.admin')

@section('title', 'Чат підтримки')

@php
    use Illuminate\Support\Str;
    use App\Models\SupportThread;
@endphp

@section('content')
    <div class="admin-card">
        <div class="section-heading">
            <div>
                <h2>Чат підтримки</h2>
                <p>Відповідайте на звернення, змінюйте статус, спілкуйтесь безпосередньо з користувачами.</p>
            </div>
            <span class="status-badge status-badge--ghost">{{ $activeThread->status_label }}</span>
        </div>

        <div class="support-admin-grid">
            <div class="support-admin-sidebar">
                <h3>Звернення</h3>
                <div class="support-thread-list">
                    @foreach ($threads as $thread)
                        @php
                            $previewTime = optional($thread->latestMessage)->created_at ?? $thread->created_at;
                        @endphp
                        <a
                            href="{{ route('admin.support.show', $thread) }}"
                            class="support-thread {{ $activeThread->id === $thread->id ? 'active' : '' }}"
                        >
                            <div class="support-thread__top">
                                <strong>{{ $thread->subject }}</strong>
                                <span class="status-badge">{{ $thread->status_label }}</span>
                            </div>
                            <p>{{ Str::limit(optional($thread->latestMessage)->message ?? 'Очікує відповіді', 70) }}</p>
                            <small class="muted-note">{{ $previewTime->format('d.m.Y H:i') }}</small>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="support-admin-chat">
                <div class="support-chat__header">
                    <div>
                        <h3>{{ $activeThread->subject }}</h3>
                        <p class="muted-note">Користувач: {{ $activeThread->user->name }} · {{ $activeThread->user->email }}</p>
                    </div>
                    <span class="status-badge">{{ $activeThread->status_label }}</span>
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
                <div class="support-admin-actions">
                    <form action="{{ route('admin.support.status.update', $activeThread) }}" method="post" class="support-status-form">
                        @csrf
                        @method('patch')
                        <div class="field-group">
                            <label for="status">Статус</label>
                            <select name="status" id="status">
                                @foreach (SupportThread::statusLabels() as $key => $label)
                                    <option value="{{ $key }}" {{ $activeThread->status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="secondary-btn">Оновити статус</button>
                    </form>
                    <form action="{{ route('admin.support.messages.store', $activeThread) }}" method="post" class="support-chat__form">
                        @csrf
                        <div class="field-group">
                            <label for="message">Відповідь</label>
                            <textarea id="message" name="message">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn">Надіслати</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
