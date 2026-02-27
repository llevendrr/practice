<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::active()
            ->withApprovedRatings()
            ->where('is_new', true)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $hitProducts = Product::active()
            ->withApprovedRatings()
            ->where('is_hit', true)
            ->orderByDesc('popularity')
            ->limit(6)
            ->get();

        $whyHighlights = [
            [
                'icon' => '⚡',
                'title' => 'Миттєвий доступ до новинок',
                'description' => 'Щодня шукаємо найгарячіші релізи й готуємо грижі партії з повним сервісом.',
            ],
            [
                'icon' => '🛠️',
                'title' => 'Техпідтримка 24/7',
                'description' => 'Консультанти, дистанційна діагностика і сервіс без вихідних чекають на вас.',
            ],
            [
                'icon' => '🚚',
                'title' => 'Доставка тоді, коли вам зручно',
                'description' => 'Нова Пошта, Укрпошта або самовивіз зі складу — ми страхуємо вантажі та показуємо трек.',
            ],
            [
                'icon' => '💜',
                'title' => 'Прозорі умови',
                'description' => 'Ніяких “костилів” — лише чесна ціна, зрозумілі повернення й увага до кожного клієнта.',
            ],
        ];

        return view('home', compact('newProducts', 'hitProducts', 'whyHighlights'));
    }
}
