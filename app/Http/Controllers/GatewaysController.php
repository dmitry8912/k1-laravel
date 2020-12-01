<?php

namespace App\Http\Controllers;

use App\Models\Gateways;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GatewaysController extends Controller
{
    public function all() {
        return Gateways::all()->toArray();
    }

    public function add(Request $request) {
        $gateway = new Gateways();
        $gateway->id = Str::uuid();
        $gateway->url = $request->url;
        $gateway->credentialId = $request->credentialId;
        $gateway->note = $request->note;
        $gateway->save();
        return $gateway;
    }

    public function update(string $id, Request $request) {
        $gateway = Gateways::find($id);
        $gateway->url = $request->url;
        $gateway->credentialId = $request->credentialId;
        $gateway->note = $request->note;
        $gateway->save();
        return $gateway->toArray();
    }

    public function delete(string $id, Request $request) {
        Gateways::find($id)->delete();
        return ['id' => $id];
    }
}
