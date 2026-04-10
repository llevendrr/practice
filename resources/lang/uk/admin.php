<?php

return [
    'toolbar' => [
        'to_site' => 'Перейти на сайт',
        'catalog' => 'Каталог',
    ],
    'menu' => [
        'dashboard' => 'Панель',
        'categories' => 'Категорії',
        'products' => 'Товари',
        'orders' => 'Замовлення',
        'users' => 'Користувачі',
        'support' => 'Чат підтримки',
        'reviews' => 'Відгуки',
    ],
    'actions' => [
        'add_product' => 'Додати товар',
        'edit' => 'Редагувати',
        'delete' => 'Видалити',
        'reset_filters' => 'Скинути фільтри',
        'apply_filters' => 'Застосувати фільтри',
    ],
    'products' => [
        'title' => 'Товари',
        'columns' => [
            'name' => 'Назва',
            'category' => 'Категорія',
            'price' => 'Ціна',
            'stock' => 'Наявність',
            'actions' => 'Дії',
        ],
        'empty' => 'За вибраними фільтрами товарів не знайдено.',
        'filters' => [
            'search' => 'Пошук по назві',
            'search_placeholder' => 'Введіть назву товару...',
            'category' => 'Категорія',
            'all_categories' => 'Усі категорії',
            'price_from' => 'Ціна від',
            'price_to' => 'Ціна до',
            'stock' => 'Наявність',
            'stock_all' => 'Будь-яка',
            'stock_in' => 'В наявності',
            'stock_out' => 'Немає в наявності',
        ],
        'flash' => [
            'saved' => 'Товар збережено.',
            'deleted' => 'Товар видалено.',
            'specs_synced' => 'Характеристики підвантажено.',
            'images_failed' => 'Не вдалося зберегти зображення. Спробуйте ще раз.',
        ],
    ],
];
