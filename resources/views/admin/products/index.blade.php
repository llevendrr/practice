@extends('layouts.admin')

@section('title', __('admin.products.title'))

@section('content')
    <div class="admin-card">
        <div class="admin-products-header">
            <h2>{{ __('admin.products.title') }}</h2>
            <a class="btn" href="{{ route('admin.products.create', request()->query()) }}">{{ __('admin.actions.add_product') }}</a>
        </div>

        <form method="get" class="admin-filters" data-admin-product-filters>
            <div class="field-group">
                <label for="q">{{ __('admin.products.filters.search') }}</label>
                <input id="q" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('admin.products.filters.search_placeholder') }}" data-filter-autosubmit="debounce" />
            </div>

            <div class="field-group">
                <label for="category_id">{{ __('admin.products.filters.category') }}</label>
                <select id="category_id" name="category_id" data-filter-autosubmit="change">
                    <option value="">{{ __('admin.products.filters.all_categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="price_min">{{ __('admin.products.filters.price_from') }}</label>
                <input id="price_min" name="price_min" type="number" min="0" step="1" value="{{ $filters['price_min'] ?? '' }}" data-filter-autosubmit="debounce" />
            </div>

            <div class="field-group">
                <label for="price_max">{{ __('admin.products.filters.price_to') }}</label>
                <input id="price_max" name="price_max" type="number" min="0" step="1" value="{{ $filters['price_max'] ?? '' }}" data-filter-autosubmit="debounce" />
            </div>

            <div class="field-group">
                <label for="stock">{{ __('admin.products.filters.stock') }}</label>
                <select id="stock" name="stock" data-filter-autosubmit="change">
                    <option value="">{{ __('admin.products.filters.stock_all') }}</option>
                    <option value="in" @selected(($filters['stock'] ?? '') === 'in')>{{ __('admin.products.filters.stock_in') }}</option>
                    <option value="out" @selected(($filters['stock'] ?? '') === 'out')>{{ __('admin.products.filters.stock_out') }}</option>
                </select>
            </div>

            <div class="field-group">
                <label for="sort">{{ __('admin.products.filters.sort') }}</label>
                <select id="sort" name="sort" data-filter-autosubmit="change">
                    <option value="">{{ __('admin.products.filters.sort_default') }}</option>
                    <option value="name_asc" @selected(($filters['sort'] ?? '') === 'name_asc')>{{ __('admin.products.filters.sort_name_asc') }}</option>
                    <option value="name_desc" @selected(($filters['sort'] ?? '') === 'name_desc')>{{ __('admin.products.filters.sort_name_desc') }}</option>
                </select>
            </div>

            <div class="admin-filters__actions">
                <button type="submit" class="secondary-btn">{{ __('admin.actions.apply_filters') }}</button>
                <a class="secondary-btn" href="{{ route('admin.products.index') }}">{{ __('admin.actions.reset_filters') }}</a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>{{ __('admin.products.columns.name') }}</th>
                    <th>{{ __('admin.products.columns.category') }}</th>
                    <th>{{ __('admin.products.columns.price') }}</th>
                    <th>{{ __('admin.products.columns.stock') }}</th>
                    <th>{{ __('admin.products.columns.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name }}</td>
                        <td>{{ number_format($product->discounted_price, 0, ',', ' ') }}&#8372;</td>
                        <td>{{ $product->stock }}</td>
                        <td class="table-actions">
                            <a class="btn btn--compact" href="{{ route('admin.products.edit', ['product' => $product] + request()->query()) }}">{{ __('admin.actions.edit') }}</a>
                            <form
                                action="{{ route('admin.products.destroy', ['product' => $product] + request()->query()) }}"
                                method="post"
                                data-confirm="{{ __('admin.products.confirm.delete_product') }}"
                            >
                                @csrf
                                @method('delete')
                                <button
                                    class="secondary-btn secondary-btn--danger"
                                    type="submit"
                                    data-confirm="{{ __('admin.products.confirm.delete_product') }}"
                                >
                                    {{ __('admin.actions.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">{{ __('admin.products.empty') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $products->appends(request()->query())->links('vendor.pagination.techno') }}
    </div>
@endsection
