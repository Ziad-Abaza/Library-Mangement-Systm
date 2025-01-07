<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Keyword;
use App\Models\Book;

class KeywordsSeeder extends Seeder
{
    public function run(): void
    {
        $keywords = [
            'Classic', 'Drama', 'Fiction', 'Adventure', 'Romance', 
            'Historical', 'Dystopian', 'Literature', 'Tragedy', 
            'Social Issues', 'Psychological', 'Fantasy', 'Philosophical',
            'Society', 'Political', 'War', 'Satire', 'Human Nature',
            'Morality', 'Love', 'Justice', 'Revenge'
        ];

        $keywordIds = [];

        foreach ($keywords as $keyword) {
            $keywordIds[] = Keyword::create(['name' => $keyword])->id;
        }

        $bookKeywords = [
            1 => ['Classic', 'Drama', 'Fiction', 'Tragedy', 'Social Issues', 'Love', 'Human Nature'],
            2 => ['Dystopian', 'Fiction', 'Literature', 'Political', 'Society', 'Satire', 'Philosophical'],
            3 => ['Classic', 'Drama', 'Historical', 'Justice', 'Morality', 'Social Issues', 'Psychological'],
            4 => ['Romance', 'Classic', 'Literature', 'Love', 'Society', 'Human Nature', 'Psychological'],
            5 => ['Adventure', 'Classic', 'Fiction', 'Revenge', 'Philosophical', 'Tragedy', 'Human Nature'],
            6 => ['Historical', 'Classic', 'Literature', 'War', 'Society', 'Philosophical', 'Love'],
            7 => ['Drama', 'Fiction', 'Literature', 'Psychological', 'Social Issues', 'Human Nature', 'Morality'],
        ];

        foreach ($bookKeywords as $bookId => $keywordsForBook) {
            foreach ($keywordsForBook as $keywordName) {
                $keywordId = array_search($keywordName, $keywords);
                DB::table('book_keyword')->insert([
                    'book_id' => $bookId,
                    'keyword_id' => $keywordIds[$keywordId],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
