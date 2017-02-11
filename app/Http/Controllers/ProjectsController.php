<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        return response()->json([
            'projects' => Project::select(['name', 'description'])->get(),
        ]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        Project::create($request->all());

        return response()->json([
            'message' => 'Project created!',
            'project' => $request->all(),
        ], Response::HTTP_OK);
    }
}
