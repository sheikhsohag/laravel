use illuminate\Support\Facedes\DB;

$user = DB::table('users')
    ->select('name')
    ->addSelect(['latest_order_amount'=> BD::table('orders')
                                                ->select('amount')
                                                ->whereColumn('orders.user_id', 'users.id')
                                                ->latest('created_at')
                                                ->limit(1)
                                                ])->get();




$users = User::whereHas('orders', function ($query){
    $query->where('amount', '>' , 500);
})


$users = User::whereIn('id', function ($query) {
    $query->select('user_id')
        ->from('orders')
        ->where('amount', '>', 500);
})->get();
