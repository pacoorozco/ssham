<?php

namespace App\Jobs;

use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateHostgroup
{
    use Dispatchable;

    private string $name;

    private ?string $description;

    private array $hosts;

    public function __construct(string $name, ?string $description, ?array $hosts)
    {
        $this->name = $name;
        $this->description = $description;
        $this->hosts = $hosts ?? [];
    }

    public function handle(): Hostgroup
    {
        $group = Hostgroup::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $group->keys()->sync($this->hosts);

        return $group;
    }
}
