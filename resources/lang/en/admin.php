<?php

return [
    'toolbar' => [
        'to_site' => 'Go to site',
        'catalog' => 'Catalog',
    ],
    'menu' => [
        'dashboard' => 'Dashboard',
        'categories' => 'Categories',
        'products' => 'Products',
        'orders' => 'Orders',
        'users' => 'Users',
        'support' => 'Support chat',
        'reviews' => 'Reviews',
    ],
    'actions' => [
        'add_product' => 'Add product',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'reset_filters' => 'Reset filters',
        'apply_filters' => 'Apply filters',
    ],
    'products' => [
        'title' => 'Products',
        'columns' => [
            'name' => 'Name',
            'category' => 'Category',
            'price' => 'Price',
            'stock' => 'Stock',
            'actions' => 'Actions',
        ],
        'empty' => 'No products match the selected filters.',
        'filters' => [
            'search' => 'Search by name',
            'search_placeholder' => 'Type product name...',
            'category' => 'Category',
            'all_categories' => 'All categories',
            'price_from' => 'Min price',
            'price_to' => 'Max price',
            'stock' => 'Availability',
            'stock_all' => 'Any',
            'stock_in' => 'In stock',
            'stock_out' => 'Out of stock',
        ],
        'flash' => [
            'saved' => 'Product saved.',
            'deleted' => 'Product deleted.',
            'specs_synced' => 'Specifications synchronized.',
            'images_failed' => 'Could not save images. Please try again.',
        ],
    ],
];
