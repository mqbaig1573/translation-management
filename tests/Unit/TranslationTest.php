namespace Tests\Unit;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_translation_has_expected_attributes()
    {
        $translation = Translation::factory()->create();

        $this->assertInstanceOf(Translation::class, $translation);
        $this->assertIsString($translation->key);
        $this->assertIsString($translation->locale);
        $this->assertIsArray($translation->context); // Since we're using JSON column
        $this->assertIsString($translation->content);
    }

    // ... you can add more unit tests for model methods, relationships, etc. 
}
