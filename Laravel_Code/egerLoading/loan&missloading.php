$post = Post::with('author')->first();
$post->loadMissing('comments');


$post->load(['comments' => function ($query) {
    $query->where('approved', true);
}]);
