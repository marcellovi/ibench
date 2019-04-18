<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show a list of all info about deleted users.
     *
     * @return Response
     */
    public function showreport() {
        $hreport = DB::table('history_deleted_users')
                ->orderBy('h_id', 'desc')
                ->get();

        $hreport_cnt = DB::table('users')
                ->where('admin', '=', 0)
                ->orderBy('h_id', 'desc')
                ->count();

        $setid = 1;
        $setts = DB::table('settings')
                ->where('id', '=', $setid)
                ->get();

        return view('admin.reports', ['hreport' => $hreport, 'hreport_cnt' => $hreport_cnt, 'setts' => $setts]);
    }
}
