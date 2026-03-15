<?php

namespace App\Models\Guesty;

/**
 * Thin alias so legacy Blade templates referencing App\Models\Guesty\GuestyProperty
 * resolve to the flat App\Models\GuestyProperty model used in the new project.
 */
class GuestyProperty extends \App\Models\GuestyProperty
{
}
