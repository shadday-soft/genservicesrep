<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Equipo;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\User;
use App\Repositories\SolicitudRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SolicitudSearchPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected SolicitudRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SolicitudRepository;
    }

    /** @test */
    public function it_can_search_solicitudes_without_search_term()
    {
        // Arrange
        $this->createTestData(5);

        // Act
        $result = $this->repository->getAll(15, null);

        // Assert
        $this->assertCount(5, $result->items());
        $this->assertEquals(5, $result->total());
    }

    /** @test */
    public function it_can_search_solicitudes_by_numero_orden()
    {
        // Arrange
        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create(['client_id' => $client->id]);
        $equipo = Equipo::factory()->create(['sucursal_id' => $sucursal->id]);
        $user = User::factory()->create();

        Solicitud::factory()->create([
            'numero_orden' => '0001',
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
            'user_id' => $user->id,
        ]);

        Solicitud::factory()->create([
            'numero_orden' => '0002',
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->repository->getAll(15, '0001');

        // Assert
        $this->assertCount(1, $result->items());
        $this->assertEquals('0001', $result->items()[0]->numero_orden);
    }

    /** @test */
    public function it_can_search_solicitudes_by_client_name()
    {
        // Arrange
        $client1 = Client::factory()->create(['enterprise_name' => 'Empresa ABC']);
        $client2 = Client::factory()->create(['enterprise_name' => 'Empresa XYZ']);

        $sucursal = Sucursal::factory()->create(['client_id' => $client1->id]);
        $equipo = Equipo::factory()->create(['sucursal_id' => $sucursal->id]);
        $user = User::factory()->create();

        Solicitud::factory()->create([
            'client_id' => $client1->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
            'user_id' => $user->id,
        ]);

        $sucursal2 = Sucursal::factory()->create(['client_id' => $client2->id]);
        Solicitud::factory()->create([
            'client_id' => $client2->id,
            'sucursal_id' => $sucursal2->id,
            'equipo_id' => $equipo->id,
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->repository->getAll(15, 'ABC');

        // Assert
        $this->assertCount(1, $result->items());
        $this->assertEquals('Empresa ABC', $result->items()[0]->client->enterprise_name);
    }

    /** @test */
    public function it_properly_paginates_results()
    {
        // Arrange
        $this->createTestData(25);

        // Act - Primera página
        $page1 = $this->repository->getAll(10, null);

        // Act - Segunda página
        request()->merge(['page' => 2]);
        $page2 = $this->repository->getAll(10, null);

        // Assert
        $this->assertCount(10, $page1->items());
        $this->assertCount(10, $page2->items());
        $this->assertEquals(25, $page1->total());
        $this->assertEquals(25, $page2->total());
        $this->assertEquals(3, $page1->lastPage());
    }

    /** @test */
    public function it_loads_relations_correctly()
    {
        // Arrange
        $client = Client::factory()->create(['enterprise_name' => 'Test Client']);
        $sucursal = Sucursal::factory()->create([
            'client_id' => $client->id,
            'name' => 'Test Sucursal',
        ]);
        $equipo = Equipo::factory()->create([
            'sucursal_id' => $sucursal->id,
            'nombre_equipo' => 'Test Equipo',
        ]);
        $user = User::factory()->create(['name' => 'Test User']);

        Solicitud::factory()->create([
            'client_id' => $client->id,
            'sucursal_id' => $sucursal->id,
            'equipo_id' => $equipo->id,
            'user_id' => $user->id,
        ]);

        // Act
        $result = $this->repository->getAll(15, null);

        // Assert
        $solicitud = $result->items()[0];
        $this->assertNotNull($solicitud->client);
        $this->assertEquals('Test Client', $solicitud->client->enterprise_name);
        $this->assertNotNull($solicitud->sucursal);
        $this->assertEquals('Test Sucursal', $solicitud->sucursal->name);
        $this->assertNotNull($solicitud->equipo);
        $this->assertEquals('Test Equipo', $solicitud->equipo->nombre_equipo);
        $this->assertNotNull($solicitud->user);
        $this->assertEquals('Test User', $solicitud->user->name);
    }

    /** @test */
    public function it_measures_query_performance()
    {
        // Arrange
        $this->createTestData(100);

        // Act
        DB::enableQueryLog();
        $startTime = microtime(true);

        $result = $this->repository->getAll(15, 'test');

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // En milisegundos
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Assert
        $this->assertLessThan(500, $executionTime, 'La consulta debería ejecutarse en menos de 500ms');
        $this->assertLessThanOrEqual(2, count($queries), 'Deberían ejecutarse máximo 2 queries (total + datos)');

        echo "\n\n=== Performance Report ===\n";
        echo 'Execution Time: '.round($executionTime, 2)."ms\n";
        echo 'Number of Queries: '.count($queries)."\n";
        echo 'Total Records: '.$result->total()."\n";
        echo 'Records Returned: '.count($result->items())."\n";
        echo "=========================\n\n";
    }

    protected function createTestData(int $count)
    {
        $client = Client::factory()->create();
        $sucursal = Sucursal::factory()->create(['client_id' => $client->id]);
        $equipo = Equipo::factory()->create(['sucursal_id' => $sucursal->id]);
        $user = User::factory()->create();

        for ($i = 0; $i < $count; $i++) {
            Solicitud::factory()->create([
                'client_id' => $client->id,
                'sucursal_id' => $sucursal->id,
                'equipo_id' => $equipo->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
