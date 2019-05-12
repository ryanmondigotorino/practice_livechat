<?php

namespace App\Modules\Finder\Audit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use ClassFactory as CF;
use AuditLogs as AL;
use Illuminate\Support\Facades\Storage;

use Auth;
use View;
use DB;
use URL;
use Browser;
use Validator;
use Exporter;

class AuditController extends Controller
{
    public static $view_path = "Finder.Audit";

    public function __construct(){
        date_default_timezone_set('Asia/Manila');
    }

    public function index(Request $request){
        return view($this->render('index'));
    }

    public function getaudit(Request $request){
        $dateRange = $request->datePicker != null ? $request->datePicker : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));
        
        $start = $request->start;
        $length = $request->length;
        $columns = [
            'finder_audits.id',
            'finders.firstname',
            'finders.username',
            'finder_audits.action',
            'finder_audits.ip_address',
            'finder_audits.device',
            'finder_audits.browser',
            'finder_audits.operating_system',
        ];

        $adminAuditDetails = $this->__mainQueryFinderAudit($request,$dateFrom,$dateTo);
        $adminAuditResultCount = $adminAuditDetails->count();
        $adminAuditDetails = $adminAuditDetails->where(function($query) use ($request){
            $query
                ->orWhere('finder_audits.id','LIKE',"%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(finders.firstname,' ',finders.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(finders.firstname,'',finders.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere(DB::raw("CONCAT(finders.firstname,' ',finders.middlename,' ',finders.lastname)"), 'LIKE', "%".$request->search['value']."%")
                ->orWhere('finders.email','LIKE',"%".$request->search['value']."%")
                ->orWhere('finders.username','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.action','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.ip_address','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.device','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.browser','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.operating_system','LIKE',"%".$request->search['value']."%")
                ->orWhere('finder_audits.created_at','LIKE',"%".$request->search['value']."%");
        })
        ->offset($start)
        ->limit($length)
        ->orderBy($columns[$request->order[0]['column']],$request->order[0]['dir'])
        ->get();

        $array = $result = [];

        foreach($adminAuditDetails as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['firstname'] = $value->firstname.' '.$value->lastname;
            $array[$key]['username'] = '<strong>'.$value->username.'</strong>';
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }

        $totalCount = count($array);
        $result['audit_details'] = $array;
        $data = [];

        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['firstname'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => $totalCount,
            "recordsFiltered" => $adminAuditResultCount,
            "data"            => $data
            );
            
        return json_encode($json_data); 
    }

    public function __mainQueryFinderAudit($request,$dateFrom,$dateTo){
        $query = CF::model('Finder_audit')
            ->select(
                'finder_audits.id',
                'finders.firstname',
                'finders.lastname',
                'finders.email',
                'finders.username',
                'finder_audits.action',
                'finder_audits.ip_address',
                'finder_audits.device',
                'finder_audits.browser',
                'finder_audits.operating_system',
                'finder_audits.created_at'
            )
            ->join('finders','finders.id','finder_audits.finder_id')
            ->whereBetween(DB::raw('DATE(finder_audits.created_at)'),[$dateFrom,$dateTo]);
        return $query;
    }

    public function downloadxlsx(Request $request){
        $currentLoggedId = Auth::guard('finder')->user();
        $dateRange = $request->date != null ? $request->date : '';
        $arrDateRange = explode(' - ',$dateRange);
        $dateFrom = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[0])));
        $dateTo = date('Y-m-d',strtotime(str_replace('/','-',$arrDateRange[1])));

        $data[] = array(
            'Id',
            'Student Name',
            'User Name',
            'Action',
            'IP Address',
            'Device Use',
            'Browser Use',
            'Operating system use',
            'Date Created',
        );

        $adminAuditDetails = $this->__mainQueryFinderAudit($request,$dateFrom,$dateTo);
        $array = $result = [];
        foreach($adminAuditDetails->get() as $key => $value){
            $array[$key]['id'] = $value->id;
            $array[$key]['name'] = $value->firstname.' '.$value->lastname;
            $array[$key]['username'] = $value->username;
            $array[$key]['action'] = $value->action;
            $array[$key]['ip_address'] = $value->ip_address;
            $array[$key]['device'] = $value->device;
            $array[$key]['browser'] = $value->browser;
            $array[$key]['operating_system'] = $value->operating_system;
            $array[$key]['created_at'] = date('m-j-y h:i:A',strtotime($value->created_at));
        }
        $result['audit_details'] = $array;
        foreach($result['audit_details'] as $key => $value){
            $dataOutput = [
                $value['id'],
                $value['name'],
                $value['username'],
                $value['action'],
                $value['ip_address'],
                $value['device'],
                $value['browser'],
                $value['operating_system'],
                $value['created_at']
            ];
            $data[] = $dataOutput;
        };

        $mainData = array(
            array(
                'Total # of Audit Logs', count($data) - 1
            ),
            array(''),
            array(''),
        );
        AL::audits('finder',$currentLoggedId,$request->ip(),'Download my Audit Logs');
        return Exporter::make('Excel')->load(collect(array_merge($mainData,$data)))->stream(date('Y-m-d-HiA-').'audit-logs-lists.xlsx');
    }
}
