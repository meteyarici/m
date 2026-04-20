<?php

return [
    [
        'key'  => 'suggestion',
        'name' => 'suggestion::app.admin.system.search-suggestion',
        'info' => 'suggestion::app.admin.system.set-search-setting',
        'sort' => 1,
    ], [
        'key'  => 'suggestion.suggestion',
        'name' => 'suggestion::app.admin.system.settings',
        'info' => 'suggestion::app.admin.system.set-search-setting',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'suggestion.suggestion.general',
        'name'   => 'suggestion::app.admin.system.general',
        'info'   => 'suggestion::app.admin.system.set-search-setting',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'status',
                'title'         => 'suggestion::app.admin.system.status',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'min_search_terms',
                'title'         => 'suggestion::app.admin.system.min-search-terms',
                'type'          => 'text',
                'validation'    => 'required|numeric|between:1,5',
                'channel_based' => true,
            ], [
                'name'          => 'show_products',
                'title'         => 'suggestion::app.admin.system.show-products',
                'type'          => 'text',
                'validation'    => 'required|numeric|between:1,5',
                'channel_based' => true,
            ], [
                'name'          => 'display_terms_toggle',
                'title'         => 'suggestion::app.admin.system.display-terms',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'display_product_toggle',
                'title'         => 'suggestion::app.admin.system.display-product',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'display_categories_toggle',
                'title'         => 'suggestion::app.admin.system.display-categories',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ],
];
