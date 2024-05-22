<?php

namespace Tests\Feature;

use App\Models\Pwconnect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PwconnectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('pwconnects.index'));
        $response->assertStatus(200);
    }

    /**
     * Test create method.
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->get(route('pwconnects.create'));
        $response->assertStatus(200);
    }

    /**
     * Test store method.
     *
     * @return void
     */
    public function testStore()
    {
        $pwconnectData = [
            'PWC_NAME' => 'Test Pwconnect',
            'PWC_USER' => 'testuser',
            'PWC_PW' => 'password',
            'PWC_WRITE' => 'Y',
            'UPDATE_DATE' => '2023-06-08',
            'PWC_CAT' => 'CAT',
            'PWC_TYP' => 'TYP',
            'PWC_GROUP' => 'GROUP',
            'PWC_ACTIVE_IND' => 'Y',
            'PWC_CHANGE_TYP' => 'Y',
            'PWC_CHANGE_COND' => 'COND',
        ];

        $response = $this->post(route('pwconnects.store'), $pwconnectData);
        $response->assertRedirect(route('pwconnects.index'));
        $this->assertDatabaseHas('pwconnects', $pwconnectData);
    }

    /**
     * Test show method.
     *
     * @return void
     */
    public function testShow()
    {
        $pwconnect = Pwconnect::factory()->create();
        $response = $this->get(route('pwconnects.show', $pwconnect));
        $response->assertStatus(200);
    }

    /**
     * Test edit method.
     *
     * @return void
     */
    public function testEdit()
    {
        $pwconnect = Pwconnect::factory()->create();
        $response = $this->get(route('pwconnects.edit', $pwconnect));
        $response->assertStatus(200);
    }

    /**
     * Test update method.
     *
     * @return void
     */
    public function testUpdate()
    {
        $pwconnect = Pwconnect::factory()->create();
        $updatedData = [
            'PWC_NAME' => 'Updated Pwconnect',
            'PWC_USER' => 'updateduser',
            'PWC_PW' => 'newpassword',
            'PWC_WRITE' => 'N',
            'UPDATE_DATE' => '2023-06-09',
            'PWC_CAT' => 'NEW',
            'PWC_TYP' => 'NEW',
            'PWC_GROUP' => 'NEWGROUP',
            'PWC_ACTIVE_IND' => 'N',
            'PWC_CHANGE_TYP' => 'N',
            'PWC_CHANGE_COND' => 'NEWCOND',
        ];

        $response = $this->put(route('pwconnects.update', $pwconnect), $updatedData);
        $response->assertRedirect(route('pwconnects.index'));
        $this->assertDatabaseHas('pwconnects', $updatedData);
    }

    /**
     * Test destroy method.
     *
     * @return void
     */
    public function testDestroy()
    {
        $pwconnect = Pwconnect::factory()->create();
        $response = $this->delete(route('pwconnects.destroy', $pwconnect));
        $response->assertRedirect(route('pwconnects.index'));
        $this->assertDatabaseMissing('pwconnects', ['id' => $pwconnect->id]);
    }
}