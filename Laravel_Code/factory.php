<?php

namespace Database\Factories;

use App\Models\YourModel; // Always import the model the factory is for
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Useful if you need string helper functions

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\YourModel>
 */
class YourModelFactory extends Factory
{
    // --- 1. Core Property: Linking the Factory to a Model ---

    /**
     * The name of the factory's corresponding model.
     * This is the MOST IMPORTANT property. Without it, the factory doesn't know what to build.
     * @var class-string<\App\Models\YourModel>
     */
    protected $model = YourModel::class;

    // --- 2. Instance Property: The Faker Generator ---

    /**
     * The Faker instance for generating fake data.
     * You don't set this directly; it's automatically available as `$this->faker`
     * within the `definition()` method and state methods.
     * It's powered by the FakerPHP library.
     * @var \Faker\Generator
     */
    // protected $faker; // You don't declare this, it's injected


    // --- 3. Core Method: Defining the Model's Default State ---

    /**
     * Define the model's default state.
     * This method must be implemented. It returns an array of attributes for the model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Basic data types
            'string_field' => $this->faker->word(),
            'text_field' => $this->faker->paragraph(),
            'integer_field' => $this->faker->numberBetween(1, 100),
            'float_field' => $this->faker->randomFloat(2, 0, 1000),
            'boolean_field' => $this->faker->boolean(),
            'date_field' => $this->faker->date(),
            'datetime_field' => $this->faker->dateTime(),
            'email_field' => $this->faker->unique()->safeEmail(),
            'uuid_field' => $this->faker->uuid(), // If your field is a UUID

            // Common real-world examples
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'title' => $this->faker->sentence(5),
            'body' => $this->faker->text(500),

            // Timestamps (often handled automatically by Model, but can be set for specific ranges)
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),

            // Relationships:
            // 1. Creating a related model (e.g., a Post belongs to a User)
            // 'user_id' => \App\Models\User::factory(), // This will create a new User and get its ID

            // 2. Associating with an existing related model (e.g., a Product belongs to a random existing Category)
            // 'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            // Make sure you have categories seeded before running this factory if you use this method.
        ];
    }

    // --- 4. Methods for Defining Factory States ---

    /**
     * Define a state for the model.
     * States allow you to define variations of your model's default attributes.
     * You create custom methods that return `static` (the factory instance).
     *
     * Usage: YourModel::factory()->active()->create();
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }

    /**
     * Another example state.
     * Usage: YourModel::factory()->pending()->create();
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * You can pass parameters to states if needed.
     * Usage: YourModel::factory()->withViews(100)->create();
     */
    public function withViews(int $count): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => $count,
        ]);
    }


    // --- 5. Callback Methods: `configure()`, `afterMaking()`, `afterCreating()` ---

    /**
     * Configure the model factory.
     * This method is called once when the factory is first instantiated.
     * Useful for setting up internal factory properties or registering callbacks.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (YourModel $yourModel) {
            // Runs after a model instance is 'made' (not saved to DB yet)
            // Useful for modifying attributes before saving.
            // e.g., if you have a complex attribute that depends on others
            // $yourModel->slug = Str::slug($yourModel->title);
        })->afterCreating(function (YourModel $yourModel) {
            // Runs after a model instance is 'created' (saved to DB)
            // Useful for creating related models or performing actions after persistence.
            // e.g., create 3 comments for each post:
            // \App\Models\Comment::factory(3)->create(['post_id' => $yourModel->id]);
        });
    }

    // You can also define afterMaking/afterCreating as separate methods if you prefer:
    // public function afterMaking(YourModel $yourModel): void
    // {
    //     // ... logic
    // }

    // public function afterCreating(YourModel $yourModel): void
    // {
    //     // ... logic
    // }

    // --- 6. Factory Helper Methods (Not properties, but how you use them) ---

    // You don't define these in the factory, but you use them to interact with it:

    // YourModel::factory()->create(); // Creates one model and saves to DB
    // YourModel::factory(5)->create(); // Creates 5 models and saves to DB

    // YourModel::factory()->make(); // Creates one model instance, but does NOT save to DB
    // YourModel::factory(5)->make(); // Creates 5 model instances, but does NOT save to DB

    // YourModel::factory()->state(['name' => 'Specific Name'])->create(); // Create with overridden attributes
}


// php artisan make:Factory YourFactoryName