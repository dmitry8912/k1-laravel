<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Credentials;

class CredentialsController extends Controller
{
    public function all() {
        return Credentials::all()->toArray();
    }

    public function add(Request $request) {
        $credential = new Credentials();
        $credential->id = Str::uuid();
        $credential->name = $request->name;
        $credential->plainPassword = $request->plainPassword;
        $credential->note = $request->note;
        $credential->save();
        return $credential->toArray();
    }

    public function update(string $id, Request $request) {
        $credential = Credentials::find($id);
        $credential->name = $request->name;
        $credential->plainPassword = $request->plainPassword;
        $credential->note = $request->note;
        $credential->save();
        return $credential->toArray();
    }

    public function delete(string $id, Request $request) {
        Credentials::find($id)->delete();
        return ['id' => $id];
    }
}
