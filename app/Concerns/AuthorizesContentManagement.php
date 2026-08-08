<?php

namespace App\Concerns;

trait AuthorizesContentManagement
{
    /**
     * Abort with 403 unless the current user holds a CMS-capable role.
     *
     * Called at the top of every Livewire action that mutates content
     * (create, edit, save, delete, remove media) as a defense-in-depth
     * layer on top of the route-level middleware.
     */
    protected function authorizeCmsAction(): void
    {
        abort_unless(auth()->user()?->hasRole(['admin', 'super-admin']), 403);
    }
}
