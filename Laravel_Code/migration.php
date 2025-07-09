<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('everything', function (Blueprint $table) {
            $table->id(); // Primary key: auto-increment integer
            $table->uuid('uuid_column')->unique(); // UUID
            $table->string('name'); // VARCHAR(255)
            $table->string('email')->unique(); // Unique string
            $table->char('code', 5); // Fixed length char
            $table->text('bio')->nullable(); // Long text
            $table->longText('description')->nullable(); // Very long text
            $table->integer('age'); // Integer
            $table->tinyInteger('status')->default(0); // Small integer
            $table->unsignedBigInteger('views')->default(0); // Unsigned big int
            $table->boolean('is_active')->default(true); // Boolean
            $table->float('rating', 5, 2)->nullable(); // Float
            $table->decimal('amount', 10, 2)->default(0.00); // Decimal number
            $table->date('birth_date')->nullable(); // Date only
            $table->dateTime('appointment_at')->nullable(); // Date and time
            $table->timestamp('published_at')->nullable(); // Timestamp
            $table->enum('role', ['admin', 'editor', 'user'])->default('user'); // Enum
            $table->json('preferences')->nullable(); // JSON data
            $table->ipAddress('login_ip')->nullable(); // IP Address
            $table->macAddress('device_mac')->nullable(); // MAC Address
            $table->year('graduation_year')->nullable(); // Year only

            // Foreign key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Soft delete + timestamps
            $table->softDeletes(); // deleted_at column
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('everything');
    }
};




// php artisan migrate
// php artisan make:migration create_everything_table
// php artisan make:model name -m...(models + migration file,,, -c, -s,-p),
