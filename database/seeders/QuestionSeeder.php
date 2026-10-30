<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

/**
 * Mengisi pertanyaan lembar kerja dari database/seeders/data/questions.php.
 * Aman dijalankan berulang: pertanyaan yang sudah ada tidak dibuat ganda.
 */
class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/data/questions.php');

        foreach ($data as $processCode => $attributes) {
            foreach ($attributes as $attributeCode => $questions) {
                foreach ($questions as $index => $text) {
                    Question::updateOrCreate(
                        ['process_code' => $processCode, 'attribute_code' => $attributeCode, 'number' => $index + 1],
                        ['question_text' => $text]
                    );
                }
            }
        }
    }
}
