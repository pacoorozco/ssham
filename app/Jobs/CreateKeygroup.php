<?php

namespace App\Jobs;

use App\Models\Keygroup;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateKeygroup
{
    use Dispatchable;

    private string $name;

    private ?string $description;

    private array $keys;

    /**
     * CreateKeygroup constructor.
     *
     * @param  string  $name
     * @param  string|null  $description
     * @param  array  $keys
     */
    public function __construct(string $name, ?string $description, ?array $keys)
    {
        $this->name = $name;
        $this->description = $description;
        $this->keys = $keys ?? [];
    }

    public function handle(): Keygroup
    {
        $group = Keygroup::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $group->keys()->sync($this->keys);

        return $group;
    }
}
