<?php

namespace App\Http\Controllers;

use App\Models\EndpointBookings;
use App\Models\EndpointLocks;
use App\Models\Endpoints;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EndpointsController extends Controller
{
    public function all() {
        return Endpoints::with('locks')->get()->toArray();
    }

    public function add(Request $request) {
        $endpoint = new Endpoints();
        $endpoint->id = Str::uuid();
        $endpoint->internalIp = $request->internalIp;
        $endpoint->credentialId = $request->credentialId;
        $endpoint->gatewayId = $request->gatewayId;
        $endpoint->type = $request->type;
        $endpoint->note = $request->note;
        $endpoint->save();
        return $endpoint->toArray();
    }

    public function update(string $id, Request $request) {
        $endpoint = Endpoints::find($id);
        $endpoint->internalIp = $request->internalIp;
        $endpoint->credentialId = $request->credentialId;
        $endpoint->gatewayId = $request->gatewayId;
        $endpoint->type = $request->type;
        $endpoint->note = $request->note;
        $endpoint->save();
        return $endpoint->toArray();
    }

    public function delete(string $id, Request $request) {
        Endpoints::find($id)->delete();
        return ['id' => $id];
    }

    public function getLocks(string $id) {
        $lock = Endpoints::find($id);
        if($lock == null) {
            return response(['error' => 'No endpoint found'],400);
        }
        return Endpoints::find($id)->with('locks')->first();
    }

    public function connect(string $id) {
        /*if(Endpoints::find($id)->bookings()->where('from', '=', date('d.m.Y'))->orWhere('to', '=', date('d.m.Y'))->count() > 0){
            return response([
                'error' => 'Endpoint booked for this date'
            ], 400);
        }*/
        if(Endpoints::find($id)->locks()->count() > 0){
            return response([
                'error' => 'Endpoint already locked'
            ], 400);
        }
        $lock = new EndpointLocks();
        $lock->id = Str::uuid();
        $lock->endpointId = $id;
        $lock->save();
        return ['lockId' => $lock->id];
    }

    public function extend(string $lockId) {
        $locks = EndpointLocks::find($lockId);
        if($locks == null) {
            return response(['error' => 'Wrong code'],400);
        }
        $lock = $locks->first();
        $lock->updated_at = Carbon::now();
        $lock->save();
        return ['status' => 'extended'];
    }

    public function unlock(string $id) {
        EndpointLocks::where('endpointId', $id)->delete();
        return ['status' => 'unlocked'];
    }

    public function lockInfo(string $lockId) {
        $lock = EndpointLocks::find($lockId);
        if($lock == null) {
            return response(['error' => 'Wrong code'],400);
        }
        return Endpoints::find($lock->endpointId)->with('credential')->with('gateway')->first();
    }

    /*public function addBooking(string $id, Request $request) {
        return ['status' => 'n\a'];
    }

    public function deletBooking(string $id, Request $request) {
        return ['status' => 'n\a'];
    }*/
}
