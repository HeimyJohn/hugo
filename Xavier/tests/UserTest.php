<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_getRouteKeyNames()
    {
//        $user = new User();
        $user = User::factory()->create();
        $this->assertTrue($user->getRouteKeyName() === 'username');
    }

    public function test_articles()
    {
        // Create a fake user in the database
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'toto@titi.com',
        ]);
        // Create 10 articles for the user
        $articles = Article::factory()->count(10)->create(['user_id' => $user->id]);

        // Get the articles for the user, using the API
        $response = $this->get('api/articles/?author=' . $user->username);

        $expectedArticles = $articles->map(function ($article) {
            return [
                'slug' => $article->slug,
                'title' => $article->title,
                'body' => $article->body,
                'description' => $article->description,
                'tagList' => [],
                'createdAt' => $article->created_at,
                'updatedAt' => $article->updated_at,
                'favorited' => false,
                'favoritesCount' => 0,
                'author' => [
                    'username' => $article->user->username,
                    'bio' => $article->user->bio,
                    'image' => $article->user->image,
                    'following' => false
                ]
            ];
        })->toArray();

        $response->assertExactJson(['articles' => $expectedArticles, 'articlesCount' => count($articles)]);
    }
}
