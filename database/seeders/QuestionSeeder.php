<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            // Insert a question
            $questionId = DB::table('questions')->insertGetId([
                'company_id'        => '671b35c259c6e-comp-37685793406052083', // Fixed value
                'custom_data'       => null, // Always null
                'title'             => 'Question ' . $i . ': ' . Str::random(20), // Randomized question title
                'question_type'     => 2, // Fixed value
                'segmentation'      => json_encode(["1", "2", "3"]), // Fixed value
                'difficulty_level'  => 3, // Fixed value
                'exam_purpose'      => 7, // Fixed value
                'media_type'        => 10, // Fixed value
                'short_description' => Str::random(50), // Randomized short description
                'marks'             => 5.0, // Fixed marks value
                'user_id'           => rand(1, 10), // Random user ID
                'status'            => 1, // Fixed as Active
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Insert 4 options for each question
            for ($j = 1; $j <= 4; $j++) {
                DB::table('question_options')->insert([
                    'question_id' => $questionId,
                    'title'       => 'Option ' . $j . ' for Question ' . $i,
                    'is_correct'  => $j === 1 ? 1 : 0, // First option is correct
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
