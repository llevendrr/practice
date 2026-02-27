@extends('layouts.admin')

@section('title', 'Панель адміністратора')

@section('content')
    <div class="admin-grid">
        @foreach ($stats as $label => $value)
            <div class="admin-card">
                <h3>{{ ucfirst($label) }}</h3>
                <p style="font-size: 2.2rem; margin: 0;">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="admin-card analytics-panel">
        <h3>Аналітика продажів</h3>
        <div class="analytics-grid">
            <div class="analytics-card">
                <p>Загальний дохід</p>
                <strong>{{ number_format($analytics['total_revenue'], 0, ',', ' ') }}₴</strong>
            </div>
            <div class="analytics-card">
                <p>Дохід за 7 днів</p>
                <strong>{{ number_format($analytics['revenue_week'], 0, ',', ' ') }}₴</strong>
            </div>
            <div class="analytics-card">
                <p>Замовлень (7 дн)</p>
                <strong>{{ $analytics['orders_7'] }}</strong>
            </div>
            <div class="analytics-card">
                <p>Замовлень (30 дн)</p>
                <strong>{{ $analytics['orders_30'] }}</strong>
            </div>
            <div class="analytics-card">
                <p>Середній чек</p>
                <strong>{{ number_format($analytics['avg_check'], 0, ',', ' ') }}₴</strong>
            </div>
        </div>

        <div class="analytics-chart-wrapper">
            <h4>Продажі за останні 14 днів</h4>
            <div class="analytics-chart">
                @foreach ($chartData as $entry)
                    @php
                        $percentage = $chartMax ? ($entry['total'] / $chartMax) * 100 : 0;
                    @endphp
                    <div class="analytics-chart__bar">
                    <div class="analytics-chart__fill" style="--bar-height: {{ $percentage }}%;"></div>
                        <span class="analytics-chart__value">{{ number_format($entry['total'], 0, ',', ' ') }}₴</span>
                        <span class="analytics-chart__label">{{ $entry['day']->format('d M') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="analytics-top">
            <h4>Топ 5 товарів</h4>
            <div class="table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Кількість</th>
                            <th>Дохід</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topProducts as $product)
                            <tr>
                                <td>{{ $product->name }}<br /><span class="muted-note">{{ $product->brand }}</span></td>
                                <td>{{ number_format($product->quantity, 0, ',', ' ') }}</td>
                                <td>{{ number_format($product->revenue, 0, ',', ' ') }}₴</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Недостатньо даних для аналітики.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
