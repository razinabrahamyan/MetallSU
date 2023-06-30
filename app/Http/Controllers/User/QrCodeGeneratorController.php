<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

class QrCodeGeneratorController extends Controller {
    public function index($tableId, $rowId) {
        return view('includes.qrCodes.url_qr_code')->with([
            "url" => '://'.$_SERVER['HTTP_HOST']."/table/$tableId/$rowId",
        ]);
    }
}
