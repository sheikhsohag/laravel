Schema::table('posts', function (Blueprint $table) {
    $table->softDeletes(); // Adds a 'deleted_at' column
});

Models 

use SoftDeletes;


delete 

$post = Post::find(1);
$post->delete();


$deletedPosts = Post::onlyTrashed()->get();

$allPosts = Post::withTrashed()->get();


Post::withTrashed()
    ->where('id', 1)
    ->restore();


$post->restore();

Post::onlyTrashed()
    ->where('author_id', 5)
    ->restore();

permanently delete

$post->forceDelete();

Post::onlyTrashed()->forceDelete();