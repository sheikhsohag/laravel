    protected $appends = ['full_name'];


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
-----------------------------------------------------------------------

For this 15-20 %  slowter…

Instead of this use eger loading DB facade. 

Or this.
4. Use API Resources
php
// UserResource.php
public function toArray($request)
{
    return [
        'full_name' => $this->first_name.' '.$this->last_name,
        // other fields
    ];
}

// Only computes when actually used in response
return UserResource::collection($users);
