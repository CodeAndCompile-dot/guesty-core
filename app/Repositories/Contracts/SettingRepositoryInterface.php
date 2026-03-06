<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SettingRepositoryInterface
{
    /**
     * Get a single setting value by key.
     */
    public function getValue(string $key): ?string;

    /**
     * Set a single setting value by key (upsert).
     */
    public function setValue(string $key, ?string $value): void;

    /**
     * Get all settings as a key-value collection.
     */
    public function getAll(): Collection;

    /**
     * Bulk update settings from an associative array.
     */
    public function setMany(array $settings): void;
}
