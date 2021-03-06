<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourOperatorsControllerTest extends TestCase
{
    /**
     * @dataProvider importToursProvider
     */
    public function testImportToursReturns422IfWrongBody(array $currentBody): void
    {
        $response = $this->postJson(
            '/api/tour_operators/import',
            $currentBody
        );

        $response->assertStatus(422);
    }

    public function testImportToursReturnsSuccessfulResponse(): void
    {
        $response = $this->postJson(
            '/api/tour_operators/import',
            ["tours_data" => [["a" => "b", "first_operator_id" => 4]], "operator_id" => 'first'],
        );

        $response->assertSuccessful();
        $body = $response->json();
        $this->assertArrayHasKey('event_id', $body);
        $this->assertIsString($body['event_id']);
    }

    /**
     * @return array<string,array>
     */
    public function importToursProvider(): array
    {
        return [
            'empty_body' => [[]],
            'without_operator' => [['tours_data' => ["a" => "b"]]],
            'without_tours_data' => [['operator_id' => 'aaaa']],
            'not_valid_operator_id' => [['tours_data' => ["a" => "b"], 'operator_id' => ['aaaa']]],
            'not_valid_tours_data' => [['operator_id' => 'aaaa', 'tours_data' => 'aaaa']],
            'both_not_valid' => [['tours_data' => 'aaaa', "operator_id" => ["jajaja"]]],
            'empty_tours_data' => [['operator_id' => 'aaaa', 'tours_data' => []]],
            'empty_operator_id' => [['operator_id' => '', 'tours_data' => ["a" => "g"]]],
        ];
    }
}
