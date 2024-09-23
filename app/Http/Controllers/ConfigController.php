<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::get();
        return view('config.index', compact('configs'));
    }

    public function create()
    {
        return view('config.create');
    }

    public function store(Request $request)
    {
        Config::create($request->all());
        return redirect()->route('config.index');
    }

    public function edit($id)
    {
        $config = Config::find($id);
        return view('config.edit', compact('config'));
    }

    public function update(Request $request, $id)
    {
        $config = Config::find($id);
        $config->update($request->all());
        return redirect()->route('config.index');
    }

    public function destroy($id)
    {
        $config = Config::find($id);
        $config->delete();
        return redirect()->route('config.index');
    }
}
