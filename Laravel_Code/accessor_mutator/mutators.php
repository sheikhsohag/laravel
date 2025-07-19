namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }
}




$user = new User;
$user->first_name = 'John'; // Will be stored as "john"
$user->save();


public function setEmailAttribute($value)
{
    $this->attributes['email'] = strtolower(trim($value));
}


public function setPasswordAttribute($value)
{
    $this->attributes['password'] = bcrypt($value);
}

-----------------------------------------------------


// Use database functions instead of PHP mutators
DB::table('users')->update([
    'email' => DB::raw('LOWER(email)')
]);



When to Avoid Mutators
Mass imports - Use raw SQL or disable mutators temporarily

High-frequency updates - Consider database triggers

Complex calculations - Move to service layer

External API calls - Handle asynchronously





Best Practices for Performance

Keep mutators lightweight - Move complex logic elsewhere

Avoid database queries in mutators - Causes N+1 problems

Use eager loading when mutators reference relationships

Profile regularly with tools like Laravel Telescope