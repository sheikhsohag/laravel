in models..   

public function scopeLocal($query)
{
    return $query->where('is_local', true);
}


use this,,


user::Local()->get();