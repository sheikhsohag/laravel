// Simple put with expiration (minutes)
Cache::put('key', 'value', $minutes);

// Put with DateTime expiration
Cache::put('key', 'value', now()->addHours(2));

// Forever (until manually removed)
Cache::forever('key', 'value');




// Get with default value
$value = Cache::get('key', 'default');

// Check existence
if (Cache::has('key')) {
    //
}

// Retrieve and delete
$value = Cache::pull('key');


$lock = Cache::lock('processing', 10); // 10 second timeout

if ($lock->get()) {
    // Do work...
    $lock->release();
}