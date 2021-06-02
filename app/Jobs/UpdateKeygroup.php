<?php

namespace App\Jobs;

use App\Models\Keygroup;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateKeygroup
{
    use Dispatchable;

    private Keygroup $group;

    private string $name;

    private ?string $description;

    private array $keys;

    /**
     * UpdateKeygroup constructor.
     *
     * @param  \App\Models\Keygroup  $group
     * @param  string  $name
     * @param  string|null  $description
     * @param  array  $keys
     */
    public function __construct(Keygroup $group, string $name, ?string $description, ?array $keys)
    {
        $this->group = $group;
        $this->name = $name;
        $this->description = $description;
        $this->keys = $keys ?? [];
    }

    public function handle(): Keygroup
    {
        $this->group->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $this->group->keys()->sync($this->keys, true);

        return $this->group;
    }
}
