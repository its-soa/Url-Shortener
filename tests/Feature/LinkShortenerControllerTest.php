<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ShortUrl;

class LinkShortenerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the /encode endpoint.
     */
    public function test_encode_url_web_route()
    {
        $originalUrl = $this->faker->url;
        $response = $this->post(route('encode'), ['url' => $originalUrl]);

        $response->assertRedirect();
        $response->assertSessionHas('original_url', $originalUrl);
        $response->assertSessionHas('short_url');

        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl
        ]);
    }

    /**
     * Test returns original route if duplicate.
     */
    public function test_return_existing_short_url()
    {
        $originalUrl = $this->faker->url;
        $existingShortUrl = ShortUrl::create([
            'short_code' => substr(md5($originalUrl), 0, 6),
            'original_url' => $originalUrl
        ]);

        $response = $this->post(route('encode'), ['url' => $originalUrl]);

        $response->assertRedirect();
        $response->assertSessionHas('short_url', 'http://short.est/' . $existingShortUrl->short_code);
    }


    /**
     * Test /encode endpoint with invalid url.
     */
    public function test_encode_invalid_url()
    {
        $invalidUrl = 'not-a-valid-url';

        $response = $this->post(route('encode'), ['url' => $invalidUrl]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('url');
        $response->assertSessionHas('error', 'Invalid URL');
    }


    /**
     * Test the /decode exsisting url.
     */
    public function test_decode_short_url()
    {
        $originalUrl = $this->faker->url;
        $shortCode = substr(md5($originalUrl), 0, 6);
        ShortUrl::create([
            'short_code' => $shortCode,
            'original_url' => $originalUrl
        ]);

        $response = $this->post(route('decode'), ['short_url' => 'http://short.est/' . $shortCode]);

        $response->assertRedirect();
        $response->assertSessionHas('decoded_url', $originalUrl);
    }


    /**
     * Test the /decode non-exsisting url.
     */
    public function test_decode_false_url()
    {
        $nonExistingShortUrl = 'http://short.est/notfound';

        $response = $this->post(route('decode'), ['short_url' => $nonExistingShortUrl]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Opps! Short URL Not Found');
    }


    /**
     * Test the /api/encode url via API.
     */
    public function test_encode_url_api()
    {
        $originalUrl = $this->faker->url;

        $response = $this->postJson(route('encode.api'), ['url' => $originalUrl]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'original_url',
            'short_url'
        ]);
        $response->assertJson([
            'original_url' => $originalUrl
        ]);

        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl
        ]);
    }


    /**
     * Test /api/encode invalid url via API.
     */
    public function test_encode_invalid_url_api()
    {
        $invalidUrl = 'dummy url';

        $response = $this->postJson(route('encode.api'), ['url' => $invalidUrl]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'error',
            'details'
        ]);
    }


    /**
     * Test the /api/decode url via API.
     */
    public function test_decode_url_api()
    {
        $originalUrl = $this->faker->url;
        $shortCode = substr(md5($originalUrl), 0, 6);
        ShortUrl::create([
            'short_code' => $shortCode,
            'original_url' => $originalUrl
        ]);

        $response = $this->postJson(route('decode.api'), ['short_url' => 'http://short.est/' . $shortCode]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['original_url']);
        $response->assertJson([
            'original_url' => $originalUrl
        ]);
    }


    /**
     * Test the /api/decode non-exsisting url via API.
     */
    public function test_decode_false_url_api()
    {
        $nonExistingShortUrl = 'http://short.est/notfound';

        $response = $this->postJson(route('decode.api'), ['short_url' => $nonExistingShortUrl]);

        $response->assertStatus(404);
        $response->assertJsonStructure(['error']);
    }
}