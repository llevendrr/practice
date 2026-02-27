@extends('layouts.admin')

@section('title', 'Користувачі')

@section('content')
    <div class="admin-card">
        <h2>Користувачі</h2>
    </div>

    <div class="table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Ім’я</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td class="table-actions">
                            <a class="secondary-btn" href="{{ route('admin.users.edit', $user) }}">Редагувати</a>

                            @if (auth()->id() !== $user->id)
                                <form
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    method="post"
                                    onsubmit="return confirm('Ви дійсно хочете видалити цього користувача?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="secondary-btn secondary-btn--danger">Видалити</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $users->appends(request()->query())->links('vendor.pagination.techno') }}
    </div>
@endsection
