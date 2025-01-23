namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_translations()
    {
        // Create some translations
        Translation::factory()->create(['locale' => 'en', 'context' => ['web'], 'content' => 'Welcome!']);
        Translation::factory()->create(['locale' => 'fr', 'context' => ['mobile'], 'content' => 'Bienvenue!']);

        // Make a request to fetch translations
        $response = $this->getJson('/api/translations');

        // Assert the response is successful and contains the translations
        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonStructure([
                     '*' => ['id', 'key', 'locale', 'context', 'content', 'created_at', 'updated_at']
                 ]);
    }

    public function test_can_filter_translations_by_locale()
    {
        // ... create translations with different locales

        $response = $this->getJson('/api/translations?locale=en'); 

        $response->assertStatus(200)
                 ->assertJsonCount(1) // Assuming one English translation
                 ->assertJsonPath('0.locale', 'en');
    }

    public function test_can_filter_translations_by_context()
    {
        // ... create translations with different contexts

        $response = $this->getJson('/api/translations?context=web');

        $response->assertStatus(200)
                 ->assertJsonCount(1) // Assuming one "web" context translation
                 ->assertJsonPath('0.context', ['web']); 
    }

    public function test_can_create_a_translation()
    {
        $data = [
            'key' => 'test_key',
            'locale' => 'es',
            'context' => ['web'],
            'content' => 'Hola!',
        ];

        $response = $this->postJson('/api/translations', $data);

        $response->assertStatus(201)
                 ->assertJson($data);

        $this->assertDatabaseHas('translations', $data);
    }

    // ... add more tests for updating, deleting, exporting translations, etc.
}
