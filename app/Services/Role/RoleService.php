<?php

namespace App\Services\Role;

use App\Models\Role;

class RoleService
{
    public function all()
    {
        return Role::query()
            ->oldest('id')
            ->get();
    }

    public function create(array $attributes)
    {
        return Role::create($attributes);
    }

    public function update(array $attributes, Role $role)
    {
        return $role->update($attributes);
    }

    public function delete(Role $role)
    {
        return $role->delete();
    }
}
