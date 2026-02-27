@extends('layouts.admin')

@section('title', 'Редагувати користувача')

@section('content')
    <div class="admin-card">
        <h2>Редагувати {{ $user->name }}</h2>
        <form action="{{ route('admin.users.update', $user) }}" method="post" class="form-grid">
            @csrf
            @method('patch')

            <div class="field-group">
                <label for="name">Ім’я</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" />
            </div>
            <div class="field-group">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" />
            </div>
            <div class="field-group">
                <label for="role">Роль</label>
                <select id="role" name="role">
                    <option value="{{ \App\Models\User::ROLE_USER }}" {{ $user->role === \App\Models\User::ROLE_USER ? 'selected' : '' }}>User</option>
                    <option value="{{ \App\Models\User::ROLE_ADMIN }}" {{ $user->role === \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button class="btn" type="submit">Зберегти</button>
        </form>
    </div>
@endsection
