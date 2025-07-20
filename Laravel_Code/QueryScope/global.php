// app/Scopes/ActiveScope.php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // This will be added to EVERY query for models using this scope
        $builder->where('is_active', true);
    }
}




// app/Models/User.php
namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }
}





Now, you apply any queuy this query apply automatically.


bypass this 


// Get ALL users, including inactive ones
User::withoutGlobalScope(ActiveScope::class)->get();

// Remove all global scopes
User::withoutGlobalScopes()->get();

// Remove multiple specific scopes
User::withoutGlobalScopes([FirstScope::class, SecondScope::class])->get();