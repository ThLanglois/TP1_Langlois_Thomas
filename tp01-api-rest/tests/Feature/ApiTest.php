<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private const OK = 200;
    private const CREATED = 201;
    private const NO_CONTENT = 204;
    private const SERVER_ERROR = 500;
    private const VALIDATION_ERROR = 422;

    // Pour les fonctions create, j'ai demandé à l'AI de me les faire pour accélérer les créations de test.
    // Peux tu, pour des tests PHPUnit, me fournir des fonctions qui me permettent de créer des instances facilement dans les tests. (En fournissant les migrations)
    private function createCategory(string $name = 'Raquette'): int
    {
        return DB::table('categories')->insertGetId([
            'name' => $name,
        ]);
    }

    private function createEquipment(array $overrides = []): int
    {
        $categoryId = $this->createCategory();

        return DB::table('equipment')->insertGetId(array_merge([
            'name' => 'Raquette test',
            'description' => 'Description test',
            'daily_price' => 19.99,
            'category_id' => $categoryId,
        ], $overrides));
    }

    private function createUser(array $overrides = []): int
    {
        return DB::table('users')->insertGetId(array_merge([
            'first_name' => 'Thomas',
            'last_name' => 'Langlois',
            'email' => 'thomas@example.com',
            'phone' => '4181234567',
        ], $overrides));
    }

    private function createRental(array $overrides = []): int
    {
        $userId = $this->createUser([
            'email' => 'user' . uniqid() . '@example.com',
        ]);

        $equipmentId = $this->createEquipment();

        return DB::table('rentals')->insertGetId(array_merge([
            'start_date' => '2026-01-10',
            'end_date' => '2026-01-12',
            'total_price' => 100.00,
            'user_id' => $userId,
            'equipment_id' => $equipmentId,
        ], $overrides));
    }

    private function createReview(array $overrides = []): int
    {
        $userId = $this->createUser([
            'email' => 'reviewer' . uniqid() . '@example.com',
        ]);

        $rentalId = $this->createRental();

        return DB::table('reviews')->insertGetId(array_merge([
            'rating' => 4,
            'comment' => 'Très bon',
            'user_id' => $userId,
            'rental_id' => $rentalId,
        ], $overrides));
    }

    public function test_get_all_equipment_returns_200_and_all_records(): void
    {
        // L'AI m'a aussi dit comment créer ces instances avec les bons paramètres.
        $this->createEquipment(['name' => 'Ski']);
        $this->createEquipment(['name' => 'Planche']);
        $this->createEquipment(['name' => 'Casque']);

        $response = $this->getJson('/api/equipment');

        $response->assertStatus(self::OK);
        $response->assertJsonCount(3, 'data'); // https://laravel.com/docs/12.x/http-tests#assert-json-count
        $response->assertJsonFragment(['name' => 'Ski']); //https://laravel.com/docs/12.x/http-tests#assert-json-fragment
        $response->assertJsonFragment(['name' => 'Planche']);
        $response->assertJsonFragment(['name' => 'Casque']);
    }

    public function test_get_one_equipment_returns_200_and_equipment_data(): void
    {
        $equipmentId = $this->createEquipment([
            'name' => 'Patins',
            'description' => 'Patins de test',
            'daily_price' => 25.50,
        ]);

        $response = $this->getJson('/api/equipment/' . $equipmentId);

        $response->assertStatus(self::OK);
        $response->assertJsonFragment([
            'id' => $equipmentId,
            'name' => 'Patins',
            'description' => 'Patins de test',
        ]);
    }

    public function test_get_one_equipment_returns_500_when_equipment_does_not_exist(): void
    {
        $response = $this->getJson('/api/equipment/999999');

        $response->assertStatus(self::SERVER_ERROR);
    }

    public function test_get_equipment_popularity_returns_200_and_calculated_value(): void
    {
        $equipmentId = $this->createEquipment();

        $userId1 = $this->createUser(['email' => 'a@example.com']);
        $userId2 = $this->createUser(['email' => 'b@example.com']);

        $rentalId1 = DB::table('rentals')->insertGetId([ // https://laravel.com/docs/12.x/http-tests#assert-json-fragment
            'start_date' => '2026-01-10',
            'end_date' => '2026-01-12',
            'total_price' => 100.00,
            'user_id' => $userId1,
            'equipment_id' => $equipmentId,
        ]);

        $rentalId2 = DB::table('rentals')->insertGetId([
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-18',
            'total_price' => 150.00,
            'user_id' => $userId2,
            'equipment_id' => $equipmentId,
        ]);

        DB::table('reviews')->insert([ //https://laravel.com/docs/12.x/http-tests#assert-json-fragment
            [
                'rating' => 4,
                'comment' => 'Bon',
                'user_id' => $userId1,
                'rental_id' => $rentalId1,
            ],
            [
                'rating' => 2,
                'comment' => 'Correct',
                'user_id' => $userId2,
                'rental_id' => $rentalId2,
            ],
        ]);

        $response = $this->getJson('/api/equipment/' . $equipmentId . '/popularity');

        $expectedPopularity = (2 * 0.6) + (((4 + 2) / 2) * 0.4);

        $response->assertStatus(self::OK);
        $response->assertJson([ // https://laravel.com/docs/12.x/http-tests#assert-json
            'popularity' => $expectedPopularity,
        ]);
    }

    public function test_get_equipment_popularity_returns_500_when_equipment_does_not_exist(): void
    {
        $response = $this->getJson('/api/equipment/999999/popularity');

        $response->assertStatus(self::SERVER_ERROR);
    }

    public function test_post_user_returns_201_and_creates_user(): void
    {
        $json = [
            'first_name' => 'Jean',
            'last_name' => 'Tremblay',
            'email' => 'jean.tremblay@example.com',
            'phone' => '5812223333',
        ];

        $response = $this->postJson('/api/users', $json);

        $response->assertStatus(self::CREATED);
        $response->assertJsonFragment($json);

        $this->assertDatabaseHas('users', $json); // https://laravel.com/docs/12.x/database-testing#asserting-database-state
    }

    public function test_post_user_returns_422_when_required_fields_are_missing(): void
    {
        $json = [
            'email' => 'incomplet@example.com',
        ];

        $response = $this->postJson('/api/users', $json);

        $response->assertStatus(self::VALIDATION_ERROR);
        $response->assertJsonValidationErrors([ // https://laravel.com/docs/12.x/database-testing#asserting-database-state
            'first_name',
            'last_name',
            'phone',
        ]);
    }

    public function test_put_user_returns_200_and_updates_user(): void
    {
        $userId = $this->createUser([
            'email' => 'old@example.com',
        ]);

        $json = [
            'first_name' => 'Nouveau',
            'last_name' => 'Nom',
            'email' => 'nouveau@example.com',
            'phone' => '5145551111',
        ];

        $response = $this->putJson('/api/users/' . $userId, $json);

        $response->assertStatus(self::OK);
        $response->assertJsonFragment($json);

        $this->assertDatabaseHas('users', array_merge(
            ['id' => $userId],
            $json
        ));
    }

    public function test_put_user_returns_500_when_user_does_not_exist(): void
    {
        $json = [
            'first_name' => 'Test',
            'last_name' => 'Absent',
            'email' => 'absent@example.com',
            'phone' => '4180000000',
        ];

        $response = $this->putJson('/api/users/999999', $json);

        $response->assertStatus(self::SERVER_ERROR);
    }

    public function test_put_user_returns_422_when_payload_is_incomplete(): void
    {
        $userId = $this->createUser([
            'email' => 'user-update@example.com',
        ]);

        $json = [
            'first_name' => 'SeulementPrenom',
        ];

        $response = $this->putJson('/api/users/' . $userId, $json);

        $response->assertStatus(self::VALIDATION_ERROR);
        $response->assertJsonValidationErrors([
            'last_name',
            'email',
            'phone',
        ]);
    }

    public function test_delete_review_returns_204_and_deletes_review(): void
    {
        $reviewId = $this->createReview();

        $response = $this->deleteJson('/api/reviews/' . $reviewId);

        $response->assertStatus(self::NO_CONTENT);

        $this->assertDatabaseMissing('reviews', [
            'id' => $reviewId,
        ]);
    }

    public function test_delete_review_returns_500_when_review_does_not_exist(): void
    {
        $response = $this->deleteJson('/api/reviews/999999');

        $response->assertStatus(self::SERVER_ERROR);
    }
}