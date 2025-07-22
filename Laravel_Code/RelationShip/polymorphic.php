<!-- ✅ When to Use Polymorphic?
Use polymorphic when:

One model can belong to multiple types of parent models.

Examples: Comment, Like, Tag, Image, Review, etc. -->


Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->timestamps();
});


Schema::create('videos', function (Blueprint $table) {
    $table->id();
    $table->string('url');
    $table->timestamps();
});


Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->text('body');
    $table->morphs('commentable'); // creates commentable_id and commentable_type
    $table->timestamps();
});






class Comment extends Model
{
    use HasFactory;

    public function commentable()
    {
        return $this->morphTo();
    }
}


class Post extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}



class Video extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}



$post = Post::create(['title' => 'Laravel Polymorphic']);
$video = Video::create(['url' => 'youtube.com/12345']);


$post->comments()->create([
    'body' => 'Great article!'
]);

$video->comments()->create([
    'body' => 'Awesome video!'
]);


$post = Post::with('comments')->find(1);
foreach ($post->comments as $comment) {
    echo $comment->body;
}

$comment = Comment::find(1);
$parent = $comment->commentable; // Could be Post or Video

