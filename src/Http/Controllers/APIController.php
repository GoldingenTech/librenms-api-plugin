<?php

namespace blizko\LibrenmsAPIPlugin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    function get_device_port_by_mac(Request $request) {
        $mac_address = $request->route('mac_address');
        $q = 'select distinct fdb.mac_address, d.sysName, p.ifName from '.
             'ports_fdb as fdb,'.
             'devices as d,'.
             'ports as p '.
             'where fdb.port_id not in (select local_port_id from links) and '.
             'fdb.port_id not in (select remote_port_id from links where remote_port_id is not null) and '.
             'fdb.device_id=d.device_id and '.
             'fdb.port_id = p.port_id and '.
             'p.ifType = "ethernetCsmacd" and '.
             'fdb.mac_address = ? '.
             'order by fdb.updated_at DESC limit 1;';
        $data = DB::select($q, [$mac_address]);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    function get_device_port_by_device_id(Request $request) {
        $q = 'select distinct fdb.mac_address, d.sysName, p.ifName from '.
             'ports_fdb as fdb, '.
             'devices as d, '.
             'ports as p '.
             'where fdb.port_id not in (select local_port_id from links) '.
             'and fdb.port_id not in (select remote_port_id from links where remote_port_id is not null) '.
             'and fdb.port_id not in (select port_id from ports_fdb group by port_id having count(*) > 4) '.
             'and fdb.device_id = d.device_id '.
             'and fdb.port_id = p.port_id '.
             'and fdb.updated_at > DATE_SUB(NOW(), INTERVAL 24 HOUR) '.
             'and p.ifType = "ethernetCsmacd" ';
        $data = DB::select($q, []);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    function get_device_by_physaddress(Request $request) {
        $physaddress = $request->route('physaddress');
        $q = 'select distinct d.device_id, d.hostname from devices as d '.
             'left join ports as p on p.device_id = d.device_id '.
             'where p.ifPhysAddress = ? ;';
        $data = DB::select($q, [$physaddress]);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    function get_device_by_physaddress_raw(Request $request) {
        $physaddress = $request->route('physaddress');
        $q = 'select distinct d.hostname from devices as d '.
             'left join ports as p on p.device_id = d.device_id '.
             'where p.ifPhysAddress = ? ;';
        $data = DB::select($q, [$physaddress]);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
