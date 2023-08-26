<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';

    public function test_list_companies(): void
    {
        Company::factory()->count(6)->create();
        
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
    }

    public function test_error_get_single_company(): void
    {
        Company::factory()->count(6)->create();
        
        $response = $this->getJson($this->endpoint.'/fake-company');
        $response->assertStatus(404);
    }

    public function test_get_single_company(): void
    {
        $company = Company::factory()->create();
        
        $response = $this->getJson($this->endpoint.'/'.$company->uuid);
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => $company->name]);
    }

    public function test_validations_store_company(): void
    {
        $response = $this->postJson($this->endpoint, [
            'name'         => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_store_company(): void
    {
        $category = Category::factory()->create();
        
        $response = $this->postJson($this->endpoint, [
            'category_id'   => $category->id,
            'name'          => 'Teste',
            'email'         => 'teste@email.com',
            'whatsapp'      => '34996960659',
            'phone'         => '34996960659'
        ]);

        $response->assertStatus(201);
    }

    public function test_update_company(): void
    {
        $company = Company::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'category_id'   => $category->id,
            'name'          => 'Teste',
            'email'         => 'teste@email.com',
            'whatsapp'      => '34996960659',
            'phone'         => '34996960659'
        ];
        
        $response = $this->putJson("$this->endpoint/fake-company", $data);
        $response->assertStatus(404);

        $response = $this->putJson("$this->endpoint/$company->uuid", [
            'name'         => '',
            'email'   => ''
        ]);
        $response->assertStatus(422);

        $response = $this->putJson("$this->endpoint/$company->uuid", $data);
        $response->assertStatus(200);

    }

    public function test_delete_company(): void
    {
        $company = Company::factory()->create();
        
        $response = $this->deleteJson("$this->endpoint/fake-company");
        $response->assertStatus(404);

        $response = $this->deleteJson("$this->endpoint/$company->uuid");
        $response->assertStatus(204);
    }


}
