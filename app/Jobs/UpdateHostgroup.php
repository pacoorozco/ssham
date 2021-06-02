<?php

namespace App\Jobs;

use App\Models\Hostgroup;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateHostgroup
{
    use Dispatchable;

    private Hostgroup $group;

    private string $name;

    private ?string $description;

    private array $hosts;

    public function __construct(Hostgroup $group, string $name, ?string $description, ?array $hosts)
    {
        $this->group = $group;
        $this->name = $name;
        $this->description = $description;
        $this->hosts = $hosts ?? [];
    }

    public function handle(): Hostgroup
    {
        $this->group->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $this->group->hosts()->sync($this->hosts, true);

        return $this->group;
    }
}
