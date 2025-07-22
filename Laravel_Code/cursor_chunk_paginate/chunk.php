User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process each user
        echo $user->name . "\n";
    }
});



<!-- nagivate all data in those table -->