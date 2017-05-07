<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $this->post(route('favorites.store', [$reply->id]));

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorites_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post(route('favorites.store', [$reply->id]));
            $this->post(route('favorites.store', [$reply->id]));
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }


        $this->assertCount(1, $reply->favorites);
    }
}
