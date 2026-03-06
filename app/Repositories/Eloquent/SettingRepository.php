<?php

namespace App\Repositories\Eloquent;

use App\Models\BasicSetting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Collection;

class SettingRepository implements SettingRepositoryInterface
{
    public function getValue(string $key): ?string
    {
        $setting = BasicSetting::where('name', $key)->first();

        return $setting?->value;
    }

    public function setValue(string $key, ?string $value): void
    {
        BasicSetting::updateOrCreate(
            ['name' => $key],
            ['value' => $value],
        );
    }

    public function getAll(): Collection
    {
        return BasicSetting::pluck('value', 'name');
    }

    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->setValue($key, $value);
        }
    }
}
