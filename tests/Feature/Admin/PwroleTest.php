<?php

namespace Tests\Feature;

use App\Models\Pwrole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PwroleTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('pwroles.index'));
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $response = $this->get(route('pwroles.create'));
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $pwroleData = [
            'pwr_name' => 'Test Pwrole',
            'pwr_description' => 'Test Description',
            'pwc_group' => 'Test Group',
            'pwr_type' => 'Test Type',
        ];

        $response = $this->post(route('pwroles.store'), $pwroleData);
        $response->assertRedirect(route('pwroles.index'));
        $this->assertDatabaseHas('pwroles', $pwroleData);
    }

    public function testShow()
    {
        $pwrole = Pwrole::factory()->create();
        $response = $this->get(route('pwroles.show', $pwrole));
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $pwrole = Pwrole::factory()->create();
        $response = $this->get(route('pwroles.edit', $pwrole));
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $pwrole = Pwrole::factory()->create();
        $updatedData = [
            'pwr_name' => 'Updated Pwrole',
            'pwr_description' => 'Updated Description',
            'pwc_group' => 'Updated Group',
            'pwr_type' => 'Updated Type',
        ];

        $response = $this->put(route('pwroles.update', $pwrole), $updatedData);
        $response->assertRedirect(route('pwroles.index'));
        $this->assertDatabaseHas('pwroles', $updatedData);
    }

    public function testDestroy()
    {
        $pwrole = Pwrole::factory()->create();
        $response = $this->delete(route('pwroles.destroy', $pwrole));
        $response->assertRedirect(route('pwroles.index'));
        $this->assertDatabaseMissing('pwroles', ['id' => $pwrole->id]);
    }
}