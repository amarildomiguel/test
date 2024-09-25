<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {

            $query = People::query()
                ->with('user')
                ->search($request->get('search'))
                ->sort($request->get('sort'));

            $data = $query->paginate($request->get('limit', 10));

            return response()->json($data);
        }

        return inertia('People/Index');
    }

    public function create()
    {
        return inertia('People/Create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'gender' => ['required'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
        ]);

        $people = People::create($request->all());
        $message = sprintf('Successfully created %s', $people->name);

        return redirect()->back()->with('success', $message);
    }

    public function edit(People $person)
    {
        return inertia('People/Edit', [
            'person' => $person
        ]);
    }
    public function show(People $person)
    {
        $person->load('user');

        return inertia('People/Show', [
            'person' => $person
        ]);
    }

    public function update(People $person, Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['required'],
            'gender' => ['required'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
        ]);
        $person->update($data);
        $message = sprintf('Successfully updated %s', $person->name);

        return redirect()->back()->with('success', $message);
    }

    public function destroy(People $person)
    {
        $person->delete();
        $message = sprintf('Successfully deleted %s', $person->name);

        return redirect()->back()->with('success', $message);
    }
}
