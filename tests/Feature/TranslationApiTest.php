<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Translation;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_translation_with_tags()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'welcome_message',
            'content' => 'Welcome to our application!',
            'tags' => ['web', 'home']
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('locale', 'en')
                 ->assertJsonCount(2, 'tags');

        $this->assertDatabaseHas('translations', ['key' => 'welcome_message']);
        $this->assertDatabaseHas('tags', ['name' => 'web']);
    }

    public function test_can_update_translation()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $translation = Translation::create([
            'locale' => 'fr',
            'key' => 'hello',
            'content' => 'Bonjour'
        ]);

        $response = $this->putJson("/api/translations/{$translation->id}", [
            'content' => 'Salut',
            'tags' => ['mobile']
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('content', 'Salut')
                 ->assertJsonCount(1, 'tags')
                 ->assertJsonPath('tags.0.name', 'mobile');
    }

    public function test_can_filter_translations()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $t1 = Translation::create(['locale' => 'en', 'key' => 'btn_submit', 'content' => 'Submit']);
        $tag = Tag::create(['name' => 'form']);
        $t1->tags()->attach($tag);

        $t2 = Translation::create(['locale' => 'en', 'key' => 'header_title', 'content' => 'Main Title']);

        $response = $this->getJson('/api/translations?tag=form');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.key', 'btn_submit');

        // Filter by key
        $response = $this->getJson('/api/translations?key=header');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.key', 'header_title');
    }

    public function test_export_structure()
    {
        Translation::create(['locale' => 'en', 'key' => 'yes', 'content' => 'Yes']);
        Translation::create(['locale' => 'en', 'key' => 'no', 'content' => 'No']);
        Translation::create(['locale' => 'fr', 'key' => 'yes', 'content' => 'Oui']);

        $response = $this->getJson('/api/export?locale=en');

        $response->assertStatus(200)
                 ->assertJson([
                     'yes' => 'Yes',
                     'no' => 'No'
                 ])
                 ->assertJsonMissing(['Oui']);
    }
}
