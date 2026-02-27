<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategorySpecField;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            'smartphones' => [
                'name' => 'Смартфони',
                'description' => 'Флагмани та новинки для будь-якого бюджету.',
                'order' => 10,
            ],
            'laptops' => [
                'name' => 'Ноутбуки',
                'description' => 'Тонкі, легкі та стильні ноутбуки.',
                'order' => 20,
            ],
            'gaming-laptops' => [
                'name' => 'Ігрові ноутбуки',
                'description' => 'Геймерські станини з потужною графікою.',
                'order' => 30,
            ],
            'tvs' => [
                'name' => 'Телевізори',
                'description' => 'OLED, QLED та розумні телевізори.',
                'order' => 40,
            ],
            'headphones' => [
                'name' => 'Навушники',
                'description' => 'Студійні, геймерські та true wireless.',
                'order' => 50,
            ],
            'vacuums' => [
                'name' => 'Пилососи',
                'description' => 'Роботизовані та вертикальні пилососи.',
                'order' => 60,
            ],
            'coffee-machines' => [
                'name' => 'Кавомашини',
                'description' => 'Кавомашини для дома і офісу.',
                'order' => 70,
            ],
        ];

        $categories = [];

        foreach ($definitions as $slug => $data) {
            $categories[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                array_merge(['slug' => $slug], $data),
            );
        }

        $categories['gaming-laptops']->update([
            'parent_id' => $categories['laptops']->id,
        ]);

        $specFields = [
            'smartphones' => [
                ['label' => 'Дисплей', 'key' => 'display', 'field_type' => 'text'],
                ['label' => 'ОЗУ (ГБ)', 'key' => 'ram', 'field_type' => 'number'],
                ['label' => 'Пам’ять (ГБ)', 'key' => 'storage', 'field_type' => 'number'],
                ['label' => 'Акумулятор (мАг)', 'key' => 'battery', 'field_type' => 'number'],
            ],
            'laptops' => [
                ['label' => 'Процесор', 'key' => 'processor', 'field_type' => 'text'],
                ['label' => 'Відеокарта', 'key' => 'gpu', 'field_type' => 'text'],
                ['label' => 'Оперативна пам’ять (ГБ)', 'key' => 'ram', 'field_type' => 'number'],
                ['label' => 'Накопичувач (ГБ)', 'key' => 'storage', 'field_type' => 'number'],
            ],
            'gaming-laptops' => [
                ['label' => 'Охолодження', 'key' => 'cooling', 'field_type' => 'text'],
                ['label' => 'Дисплей', 'key' => 'display', 'field_type' => 'text'],
                ['label' => 'Потужність (Вт)', 'key' => 'power', 'field_type' => 'number'],
            ],
            'tvs' => [
                ['label' => 'Роздільна здатність', 'key' => 'resolution', 'field_type' => 'text'],
                ['label' => 'Технологія', 'key' => 'panel', 'field_type' => 'text'],
                ['label' => 'Частота', 'key' => 'refresh_rate', 'field_type' => 'text'],
            ],
            'headphones' => [
                ['label' => 'Тип', 'key' => 'type', 'field_type' => 'text'],
                ['label' => 'Час роботи', 'key' => 'battery', 'field_type' => 'text'],
                ['label' => 'Підключення', 'key' => 'connection', 'field_type' => 'text'],
            ],
            'vacuums' => [
                ['label' => 'Тип', 'key' => 'type', 'field_type' => 'text'],
                ['label' => 'Потужність', 'key' => 'power', 'field_type' => 'text'],
                ['label' => 'Об’єм', 'key' => 'capacity', 'field_type' => 'text'],
            ],
            'coffee-machines' => [
                [
                    'label' => 'Тип кавомашини',
                    'key' => 'machine_type',
                    'field_type' => 'select',
                    'options' => ['эспрессо', 'кавові зерна', 'капсули'],
                ],
                ['label' => 'Тиск (бар)', 'key' => 'pressure', 'field_type' => 'number'],
                ['label' => 'Кавове зерно / капсули', 'key' => 'beans_or_capsules', 'field_type' => 'text'],
            ],
        ];

        foreach ($specFields as $slug => $fields) {
            $category = $categories[$slug] ?? null;

            if (! $category) {
                continue;
            }

            foreach ($fields as $index => $field) {
                CategorySpecField::updateOrCreate(
                    ['category_id' => $category->id, 'key' => $field['key']],
                    [
                        'label' => $field['label'],
                        'field_type' => $field['field_type'],
                        'order' => $index,
                        'required' => true,
                        'options' => $field['options'] ?? null,
                    ],
                );
            }
        }
    }
}
