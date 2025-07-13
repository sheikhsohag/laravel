<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Don't forget to import if used

class MyModel extends Model
{
    // --- 1. Core Model Configuration ---

    /**
     * The table associated with the model.
     * If not set, Laravel will use the snake_case, plural form of the model name (e.g., 'my_models').
     * @var string
     */
    protected $table = 'custom_table_name';

    /**
     * The primary key for the model.
     * Defaults to 'id'.
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     * Defaults to 'int'. Use 'string' if your primary key is a UUID or non-integer.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * Defaults to true. Set to false if using UUIDs or custom IDs.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     * Defaults to true (creates 'created_at' and 'updated_at').
     * @var bool
     */
    public $timestamps = true;

    /**
     * The name of the "created at" column.
     * Only relevant if $timestamps is true. Defaults to 'created_at'.
     * @var string
     */
    const CREATED_AT = 'creation_date';

    /**
     * The name of the "updated at" column.
     * Only relevant if $timestamps is true. Defaults to 'updated_at'.
     * @var string
     */
    const UPDATED_AT = 'last_update';

    /**
     * The database connection that should be used by the model.
     * Defaults to the default connection defined in config/database.php.
     * @var string
     */
    protected $connection = 'mysql';


    // --- 2. Mass Assignment Protection ---

    /**
     * The attributes that are mass assignable.
     * Only allows fields listed here to be filled using `Model::create()` or `Model::fill()`.
     * RECOMMENDED APPROACH: Use $fillable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        // ... other attributes that can be mass assigned
    ];

    /**
     * The attributes that aren't mass assignable.
     * If using $guarded, any attribute *not* in $guarded can be mass assigned.
     * DANGEROUS: Setting $guarded = [] (an empty array) allows ALL attributes to be mass assigned.
     * Use $fillable for better security.
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        // 'admin_flag', // Example: You might not want this set via mass assignment
    ];


    // --- 3. Attribute Casting ---

    /**
     * The attributes that should be cast.
     * Converts database column values to specified PHP types automatically.
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // For Laravel 10+
        'is_admin' => 'boolean',
        'options' => 'array',    // JSON column in DB will be PHP array/object
        'settings' => 'collection', // JSON column will be Laravel Collection
        'price' => 'float',
        'metadata' => 'json',    // Another way to cast JSON
        'status' => \App\Enums\OrderStatus::class, // For Laravel 9+ Enum casting
    ];


    // --- 4. Date/Time Customization ---

    /**
     * The storage format of the model's date columns.
     * This defines how dates are stored in the database.
     * @var string
     */
    // protected $dateFormat = 'U'; // For Unix timestamp storage

    /**
     * The attributes that should be mutated to dates.
     * If not using $casts to 'datetime', you can list date columns here.
     * They will be converted to Carbon instances.
     * @var array<int, string>
     */
    protected $dates = [
        // 'deleted_at', // Handled by SoftDeletes trait, but can be listed
        // 'published_at',
    ];


    // --- 5. Soft Deletes (requires `use SoftDeletes;` trait) ---

    /**
     * The column to store the soft delete timestamp.
     * Defaults to 'deleted_at'.
     * @var string
     */
    // const DELETED_AT = 'my_deleted_column'; // Uncomment and change if your column is not 'deleted_at'


    // --- 6. Hiding/Showing Attributes in Arrays/JSON Output ---

    /**
     * The attributes that should be hidden for serialization.
     * These columns will NOT appear when you convert the model to an array or JSON.
     * Common for sensitive data like passwords.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be visible in serialization.
     * If you set $hidden, all other attributes are visible by default.
     * If you set $visible, only attributes listed here will be visible.
     * Use one or the other, not both.
     * @var array<int, string>
     */
    protected $visible = [
        'id',
        'name',
        'email',
        // 'custom_attribute', // If you have an appended attribute
    ];


    // --- 7. Appending Attributes ---

    /**
     * The accessors to append to the model's array form.
     * Allows you to add custom attributes (defined via accessor methods) to the JSON output.
     * @var array<int, string>
     */
    protected $appends = [
        'full_name', // Requires a getFullNameAttribute() method
        'is_active', // Requires an getIsActiveAttribute() method
    ];


    // --- 8. Global Scopes ---
    // Not a direct property, but often defined with a model.
    // Adds constraints to all queries on the model automatically.
    // protected static function booted(): void
    // {
    //     static::addGlobalScope(new ActiveUserScope);
    // }


    // --- 9. Traits ---
    // These add properties and methods to your model.

    use HasFactory; // Required for model factories
    use SoftDeletes; // Adds `deleted_at` functionality and related methods


    // --- 10. Accessors & Mutators (Methods that act like properties) ---

    /**
     * Get the user's full name.
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Set the user's password.
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }


    // --- 11. Relationships (Methods, not properties, but essential for models) ---

    // One-to-One
    public function phone(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Phone::class);
    }

    // One-to-Many
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Inverse of One-to-Many
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Many-to-Many
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Has Many Through
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }

    // Morph To
    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    // Morph One
    public function image(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // Morph Many
    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

// php artisan make:model User


// -R or --resource: Creates a resource controller for the model (includes methods for index, create, store, show, edit, update, destroy).

// --api: Creates an API resource controller (same as -R but without create and edit methods, as these are typically for web UIs).

// -f or --factory: Creates a new model factory for the model. Useful for generating fake data.

// -s or --seed: Creates a new seeder for the model. Used to populate the database with initial data.

// -P or --pivot: Indicates the model is a pivot table.

// -a or --all: A powerful option that creates a migration, seeder, factory, and resource controller for the model.

// Example: Creating a Product model with a migration, 