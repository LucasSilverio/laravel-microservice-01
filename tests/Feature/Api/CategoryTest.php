<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';
    public function test_list_categories(): void
    {
        Category::factory()->count(6)->create();
        
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
    }

    public function test_error_get_single_category(): void
    {
        Category::factory()->count(6)->create();
        
        $response = $this->getJson($this->endpoint.'/fake-category');
        $response->assertStatus(404);
    }

    public function test_get_single_category(): void
    {
        $category = Category::factory()->create();
        
        $response = $this->getJson($this->endpoint.'/'.$category->url);
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['title' => $category->title]);
    }

    public function test_validations_store_category(): void
    {
        $response = $this->postJson($this->endpoint, [
            'title'         => '',
            'description'   => ''
        ]);

        $response->assertStatus(422);
    }

    public function test_store_category(): void
    {
        $response = $this->postJson($this->endpoint, [
            'title'         => 'Category 1',
            'description'   => 'Description of category'
        ]);

        $response->assertStatus(201);
    }

    public function test_update_category(): void
    {
        $category = Category::factory()->create();

        $data = [
            'title'         => 'Title updated',
            'description'   => 'Description Updated'
        ];
        
        $response = $this->putJson("$this->endpoint/fake-category", $data);
        $response->assertStatus(404);

        $response = $this->putJson("$this->endpoint/$category->url", [
            'title'         => '',
            'description'   => ''
        ]);
        $response->assertStatus(422);

        $response = $this->putJson("$this->endpoint/$category->url", $data);
        $response->assertStatus(200);

    }

    public function test_delete_category(): void
    {
        $category = Category::factory()->create();
        
        $response = $this->deleteJson("$this->endpoint/fake-category");
        $response->assertStatus(404);

        $response = $this->deleteJson("$this->endpoint/$category->url");
        $response->assertStatus(204);
    }


}
