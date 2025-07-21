<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        // Logic to run after a user is created
    }

    public function updating(User $user)
    {
        // Logic to run before a user is updated
    }
    
    // Other event methods...
}




Here are the main events you can observe:

retrieved - After a model is retrieved from the database

creating / created - Before/after a record is created

updating / updated - Before/after a record is updated

saving / saved - Before/after a record is saved (created or updated)

deleting / deleted - Before/after a record is deleted

restoring / restored - Before/after a soft-deleted record is restored

replicating - When a model is being replicated  