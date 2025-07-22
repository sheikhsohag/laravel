use App\Models\User;

public function index()
{
    $users = User::orderBy('id', 'desc')->paginate(10); // 10 per page

    return response()->json([
        'data' => $users->items(), // actual user data
        'links' => [
            'first' => $users->url(1),
            'last' => $users->url($users->lastPage()),
            'prev' => $users->previousPageUrl(),
            'next' => $users->nextPageUrl(),
        ],
        'meta' => [
            'current_page' => $users->currentPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
            'last_page' => $users->lastPage(),
        ]
    ]);
}
