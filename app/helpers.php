<?php

if (! function_exists('ukrainianPluralForm')) {
    /**
     * Return correct Ukrainian plural form for the given count.
     *
     * @param  int  $count
     * @param  array<int, string>  $forms
     * @return string
     */
    function ukrainianPluralForm(int $count, array $forms = ['товар', 'товари', 'товарів']): string
    {
        $n = abs($count);

        if ($n % 10 === 1 && $n % 100 !== 11) {
            return $forms[0];
        }

        if ($n % 10 >= 2 && $n % 10 <= 4 && ! in_array($n % 100, [12, 13, 14], true)) {
            return $forms[1];
        }

        return $forms[2];
    }
}

if (! function_exists('cartItemsLabel')) {
    function cartItemsLabel(int $count): string
    {
        if (app()->getLocale() === 'uk') {
            return ukrainianPluralForm($count, ['товар', 'товари', 'товарів']);
        }

        return $count === 1 ? 'item' : 'items';
    }
}
