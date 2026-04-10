<?php

return [
    'cart' => [
        'out_of_stock' => 'Product is temporarily unavailable.',
        'added' => 'Product added to cart.',
        'updated' => 'Quantity updated.',
        'removed' => 'Product removed from cart.',
        'empty' => 'Cart is empty.',
    ],
    'checkout' => [
        'empty_cart' => 'Cart is empty.',
    ],
    'auth' => [
        'mail_failed' => 'Could not send email, please check mail settings.',
    ],
    'orders' => [
        'cancel_forbidden' => 'Order status does not allow cancellation.',
        'cancelled' => 'Order cancelled.',
        'created' => 'Order created.',
    ],
    'payment' => [
        'success' => 'Payment completed successfully.',
        'cod_selected' => 'Your order will be processed as cash on delivery.',
        'status_not_updated' => 'Order status has not been updated yet.',
    ],
    'profile' => [
        'updated' => 'Profile updated.',
        'password_updated' => 'Password changed.',
    ],
    'support' => [
        'thread_created' => 'Support ticket created.',
        'message_sent' => 'Message sent.',
        'reply_sent' => 'Reply sent.',
        'status_updated' => 'Status updated.',
    ],
    'reviews' => [
        'thanks' => 'Thanks for your review! We will process it shortly.',
        'status_updated' => 'Review status updated.',
        'deleted' => 'Review deleted.',
    ],
    'admin' => [
        'category' => [
            'saved' => 'Category saved.',
            'self_parent' => 'Category cannot be its own parent.',
            'updated' => 'Category updated.',
            'deleted' => 'Category deleted.',
        ],
        'spec' => [
            'saved' => 'Specification saved.',
            'updated' => 'Specification updated.',
            'deleted' => 'Specification deleted.',
        ],
        'order' => [
            'statuses_updated' => 'Statuses updated.',
        ],
        'user' => [
            'updated' => 'User updated.',
            'delete_self' => 'You cannot delete yourself.',
            'delete_blocked' => 'User cannot be deleted because they have orders or reviews.',
            'deleted' => 'User deleted.',
        ],
    ],
];
