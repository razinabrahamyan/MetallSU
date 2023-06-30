<?php

namespace App\Http\Controllers\Mytest;

use App\Classes\Export\Inventory\InventoryExcel;
use App\Classes\FilesGenerator\Core as FilesGenCore;
use App\Classes\FilesGenerator\FileGenerator;
use App\Classes\FilesGenerator\FilesTypes\PDFGeneration;
use App\Exports\OutcomeExport;
use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\Category;
use App\Models\Cost;
use App\Models\Deduction;
use App\Models\Holiday;
use App\Models\InventoryItem;
use App\Models\InventoryOwnerCategory;
use App\Models\Item;
use App\Models\Logistics;
use App\Models\Responsible;
use App\Models\ResponsibleType;
use App\Models\Salary;
use App\Models\StoreItem;

use App\Models\Worker;

use App\Models\WorkerEvent;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class TestController extends Controller
{
    public function test()
    {


    }
    public function postRequest(Request $request)
    {

        dd($request->all());
    }




}
