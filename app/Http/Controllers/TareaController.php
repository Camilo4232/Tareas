<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|string|max:120',
            'descripcion' => 'required|string',
            'fechaEstimadaFinalizacion' => 'required|date',
            'idEmpleado' => 'required|integer|exists:empleados,id',
            'idPrioridad' => 'required|integer|exists:prioridades,id',
        ]);

        $tarea = new Tarea();
        $tarea->titulo = $request->input('titulo');
        $tarea->descripcion = $request->input('descripcion');
        $tarea->fechaEstimadaFinalizacion = $request->input('fechaEstimadaFinalizacion');
        //$tarea->creadorTarea = $request->user()->nombre; 
        $tarea->creadorTarea = 1; 
        $tarea->idEmpleado = $request->input('idEmpleado');
        $tarea->idEstado = 1; 
        $tarea->idPrioridad = $request->input('idPrioridad');
        $tarea->save();

        return response()->json($tarea, 201);
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $this->validate($request, [
            'titulo' => 'sometimes|required|string|max:120',
            'descripcion' => 'sometimes|required|string',
            'fechaEstimadaFinalizacion' => 'sometimes|required|date',
            'idEmpleado' => 'sometimes|required|integer|exists:empleados,id',
            'idPrioridad' => 'sometimes|required|integer|exists:prioridades,id',
        ]);

        $tarea->update($request->all());
        return response()->json($tarea);
    }

    public function delete($id)
    {
        Tarea::destroy($id);
        return response()->json(null, 204);
    }

    public function list(Request $request)
    {
        $query = Tarea::query();

        if ($request->has('idPrioridad')) {
            $query->where('idPrioridad', $request->input('idPrioridad'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('fechaEstimadaFinalizacion', [$request->input('start_date'), $request->input('end_date')]);
        }

        if ($request->has('idEmpleado')) {
            $query->where('idEmpleado', $request->input('idEmpleado'));
        }

        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->input('titulo') . '%');
        }

        if ($request->has('descripcion')) {
            $query->where('descripcion', 'like', '%' . $request->input('descripcion') . '%');
        }

        $tasks = $query->orderBy('idPrioridad', 'desc')->orderBy('fechaEstimadaFinalizacion')->get();
        return response()->json($tasks);
    }

    public function changeStatus(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $this->validate($request, [
            'idEstado' => 'required|integer|exists:estados,id',
        ]);

        $tarea->idEstado = $request->input('idEstado');
        $tarea->save();

        return response()->json($tarea);
    }

    public function reassign(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $this->validate($request, [
            'idEmpleado' => 'required|integer|exists:empleados,id',
        ]);

        $tarea->idEmpleado = $request->input('idEmpleado');
        $tarea->save();

        return response()->json($tarea);
    }
}

?>