<?php

namespace App\Models\Blogs;

/**
 * Thin alias so legacy Blade templates referencing App\Models\Blogs\BlogCategory
 * resolve to the flat App\Models\BlogCategory model used in the new project.
 */
class BlogCategory extends \App\Models\BlogCategory
{
}
