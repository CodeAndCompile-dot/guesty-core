<?php

namespace App\Models\Blogs;

/**
 * Thin alias so legacy Blade templates referencing App\Models\Blogs\Blog
 * resolve to the flat App\Models\Blog model used in the new project.
 */
class Blog extends \App\Models\Blog
{
}
