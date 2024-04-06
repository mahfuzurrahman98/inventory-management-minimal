<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        // Generate a random filename for the image
        $filename = Str::random(10) . '.jpg';

        // Get the URL of a random image
        $imageUrl = $this->faker->imageUrl(640, 480, 'items', true);

        // Download the image and save it to the storage
        $contents = file_get_contents($imageUrl);
        Storage::put("public/images/{$filename}", $contents);


        return [
            'name' => $this->faker->word,
            'image'=> "{$filename}",
            'description' => $this->faker->text(255),
            'quantity' => $this->faker->numberBetween(1, 18)
        ];
    }
}
