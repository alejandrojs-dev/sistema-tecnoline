<?php
namespace App\Services\Dashboards;

use App\Interfaces\IBaseService;
use App\Http\Resources\Dashboards\DashboardCollection;
use App\Http\Resources\Dashboards\DashboardResource;
use App\Enums\HttpStatusCode;
use App\Models\Dashboard;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;

class DashboardService implements IBaseService
{
  public function all()
  {
    try {
      $dashboards = Dashboard::orderBy('name', 'ASC')->get();
      $dashboardCollection = new DashboardCollection($dashboards);

      return response()->json(
        [
          'ok' => true,
          'dashboards' => $dashboardCollection,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener los dashboards',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function save($request)
  {
    try {
      $objDashboard = new Dashboard();
      $objDashboard->name = $request->name;
      $objDashboard->description = $request->description;
      $objDashboard->is_slide = $request->isSlide;
      $objDashboard->is_row_data = $request->isRowData;
      $objDashboard->is_in_carousel = $request->isInCarousel;
      $objDashboard->active = $request->active;
      $objDashboard->save();

      if ($request->isSlide) {
        $objDashboard->component_name = 'Dashboard_' . $objDashboard->dashboard_id;
        $objDashboard->save();
      }

      $objDashboard->departments()->attach($request->allowedDepartments);

      $dashboard = new DashboardResource($objDashboard);

      return response()->json(
        [
          'ok' => true,
          'dashboard' => $dashboard,
          'message' => 'Dashboard guardado con éxito',
          'statusCode' => HttpStatusCode::CREATED,
        ],
        HttpStatusCode::CREATED
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al guardar el dashboard',
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function get($dashboard)
  {
    try {
      $dashboard = new DashboardResource($dashboard);
      return response()->json(
        [
          'ok' => true,
          'dashboard' => $dashboard,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al obtener el departamento',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function update($request, $dashboard)
  {
    try {
      $dashboard->update([
        'name' => $request->name,
        'description' => $request->description,
        'is_slide' => $request->isSlide,
        'is_row_data' => $request->isRowData,
        'is_in_carousel' => $request->isInCarousel,
        'active' => $request->active,
      ]);

      $dashboard->departments()->sync($request->allowedDepartments);

      $dashboard = new DashboardResource($dashboard);

      return response()->json([
        'ok' => true,
        'dashboard' => $dashboard,
        'message' => 'Dashboard actualizado con éxito',
        HttpStatusCode::OK,
      ]);
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al actualizar el dashboard',
          'error' => $exception->getMessage(),
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function delete($dashboard)
  {
    try {
      $dashboard->delete();
      return response()->json(
        [
          'ok' => true,
          'message' => 'Dashboard eliminado con éxito',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al eliminar el dashboard',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getDashboardsByDep($department_id)
  {
    try {
      $dashboards = Dashboard::select(
        'd.dashboard_id AS id',
        'd.name',
        'd.description',
        'd.component_name AS componentName',
        'd.is_slide AS isSlide',
        'd.is_row_data AS isRowData',
        'd.is_in_carousel AS isInCarousel'
      )
        ->from('e_dashboards AS d')
        ->join('d_dashboard_department AS dd', function ($join) use ($department_id) {
          $join->on('dd.dashboard_id', '=', 'd.dashboard_id')->where('dd.department_id', '=', $department_id);
        })
        // ->where('d.is_in_carousel', '=', 1)
        // ->where('d.is_slide', '=', 1)
        // ->where('d.active', '=', 1)
        // ->where('d.is_row_data', '=', 1)
        //->orderBy('d.name', 'ASC')
        ->get();

      return response()->json(
        [
          'ok' => true,
          'dashboards' => $dashboards,
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'No se pudo obtener la información de los dashboards',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function getBIData($dashboard_id, $data_type)
  {
    try {
      $statement = DB::getPdo()->prepare('CALL spr_generate_row_data(' . $dashboard_id . ')');

      $statement->execute();
      $results = [];
      $response = [];

      if ($data_type === 'rowData') {
        do {
          $results[] = $statement->fetchAll(\PDO::FETCH_OBJ);
        } while ($statement->nextRowSet());

        $headers = preg_replace('/\s+/', '', $results[0][0]->headers);
        $headers = explode(',', $headers);

        $queryColumns = preg_replace('/\s+/', '', $results[1][0]->columns);
        $queryColumns = explode(',', $queryColumns);

        $response = [];

        for ($x = 0; $x < count($headers); $x++) {
          $response['headers'][] = [
            'text' => $headers[$x],
            'value' => $queryColumns[$x],
            'align' => 'center',
            'sortable' => 'true',
            'filterable' => 'true',
          ];
        }

        $row_data = $results[2];
        $response['rowData'] = $row_data;
      }

      if ($data_type === 'vista') {
        do {
          $results = $statement->fetchAll(\PDO::FETCH_OBJ);
        } while ($statement->nextRowSet());

        $response['data'] = $results[0];
      }

      return response()->json(
        [
          'ok' => true,
          'response' => $response,
          'message' => 'Data generada con éxito',
          'statusCode' => HttpStatusCode::OK,
        ],
        HttpStatusCode::OK
      );
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al generar el row data',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }

  public function createVueFileComponent($dashboard_id)
  {
    try {
      $created = false;
      $message = '';
      $path = dirname(getcwd()) . '/resources/js/components/Dashboards';
      $file_name = 'Dashboard_' . $dashboard_id . '.vue';
      $component_name = 'Dashboard_' . $dashboard_id;

      $file_system = new Filesystem();

      if (!$file_system->isDirectory($path)) {
        $file_system->makeDirectory($path);
      }

      $file_path = $path . '/' . $file_name;

      $file_template = "<template>
  <div>
  </div>
</template>

<script>
export default {
  name: '{$component_name}',
  props: [],
  data: () => ({

  }),
  computed: {

  },
  methods: {

  },
  created() {

  },
}
</script>

<style scoped>
</style>
      ";

      $res = $file_system->put($file_path, $file_template);

      if ($res > 0) {
        $created = true;
        $message = 'Componente vue creado con éxito';
      } else {
        $message = 'No se creó el componente vue. Verifique que la programación esté correcta';
      }

      return response()->json([
        'ok' => true,
        'message' => $message,
        'created' => $created,
      ]);
    } catch (Exception $exception) {
      return response()->json(
        [
          'ok' => false,
          'message' => 'Error al generar el componente vue',
          'error' => $exception->getMessage(),
          'statusCode' => HttpStatusCode::INTERNAL_SERVER_ERROR,
        ],
        HttpStatusCode::INTERNAL_SERVER_ERROR
      );
    }
  }
}
