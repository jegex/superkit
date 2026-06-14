<?php

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     * Defaults to Str::slug (https://laravel.com/docs/master/helpers#method-str-slug)
     */
    'slugger' => null,

    /*
     * The fully qualified class name of the tag model.
     */
    'tag_model' => Taxonomy::class,

    /*
     * The name of the table associated with the taggable morph relation.
     */
    'taggable' => [
        'table_name' => 'taxonomables',
        'morph_name' => 'taxonomable',

        /*
         * The fully qualified class name of the pivot model.
         */
        'class_name' => MorphPivot::class,
    ],
];
