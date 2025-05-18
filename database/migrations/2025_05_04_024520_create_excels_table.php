<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excels', function (Blueprint $table) {
             $table->id();
            $table->string('website')->nullable();
            $table->string('gender')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('title')->nullable();
            $table->string('brandName')->nullable();
            $table->string('email')->nullable();
            $table->string('result')->nullable();
            $table->string('emailStatus')->nullable();
            $table->string('seniority')->nullable();
            $table->string('departments')->nullable();
            $table->string('mobilePhone')->nullable();
            $table->string('employees')->nullable();
            $table->string('industry')->nullable();
            $table->string('keywords')->nullable();
            $table->string('personLinkedin')->nullable();
            $table->string('brandLinkedinUrl')->nullable();
            $table->string('facebookUrl')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('brandAddress')->nullable();
            $table->string('brandCity')->nullable();
            $table->string('brandState')->nullable();
            $table->string('brandCountry')->nullable();
            $table->string('brandPhone')->nullable();
            $table->string('category')->nullable();
            $table->string('combinedFollowers')->nullable();
            $table->string('currency')->nullable();
            $table->string('genericEmails')->nullable();
            $table->string('estimatedMonthlyRevenue')->nullable();
            $table->string('facebookCategories')->nullable();
            $table->string('facebookUrl_1')->nullable(); // renamed to avoid conflict
            $table->string('instagramFollowers')->nullable();
            $table->string('instagramUrl')->nullable();
            $table->string('languageCode')->nullable();
            $table->string('phone')->nullable();
            $table->string('plan')->nullable();
            $table->string('platform')->nullable();
            $table->string('productVariants')->nullable();
            $table->string('productsSold')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('technologies')->nullable();
            $table->string('Technologies_2')->nullable(); // renamed to avoid duplicate
            $table->string('foundedYear')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('storeStatus')->nullable();
            $table->string('lastUpdatedDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excels');
    }
};
