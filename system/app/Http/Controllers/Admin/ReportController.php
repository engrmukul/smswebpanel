<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\Outbox;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $title = 'All Message Report';
        $today = date('Y-m-d');
        if ($request->report_date == 'last_ninety_days') {
            $startDate = date('Y-m-d', strtotime('-90 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_month') {
            $startDate = date('Y-m-d', strtotime('first day of last month'));
            $today = date('Y-m-d', strtotime('last day of last month'));
        } elseif ($request->report_date == 'this_month') {
            $startDate = date('Y-m-1');
        } elseif ($request->report_date == 'last_thirteen_days') {
            $startDate = date('Y-m-d', strtotime('-30 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_seven_days') {
            $startDate = date('Y-m-d', strtotime('-7 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'yeasterday') {
            $startDate = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
            $today = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
        } else {
            $startDate = date('Y-m-d');
        }

        if (isset($request->report_date)) {
            if (Auth::user()->id_user_group == 4) {
                $messages = Outbox::with(['user'])->where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->orderBy('last_updated', 'DESC');
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    if ($request->user == '' && $request->reseller == '') {
                        $messages = Outbox::with(['user'])->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->orderBy('last_updated', 'DESC');
                    } else {
                        if ($request->user != '') {
                            $messages = Outbox::with(['user'])->where('user_id', $request->user)->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today]);
                        } else {
                            $messages = Outbox::with(['user'])->whereHas("user", function ($query) use ($request) {
                                $query->where("reseller_id", $request->reseller);
                            })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->orderBy('last_updated', 'DESC');
                        }
                    }
                } else {
                    if ($request->user == '') {
                        $messages = Outbox::with(['user'])->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->orderBy('last_updated', 'DESC');
                    } else {
                        $messages = Outbox::with(['user'])->where('user_id', $request->user)->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->orderBy('last_updated', 'DESC');
                    }
                }
            }
        } else {
            $messages = array();
        }

        $tableHeaders = [
            "id" => "",
            "username" => "User",
            "mask" => "Mask",
            "destmn" => "Number",
            "message" => "Message",
            "write_time" => "Write Time",
            "sent_time" => "Sent Time",
            "last_updated" => "Last Update",
            "status" => "Status",
            "smscount" => "Sms Count"
        ];
        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 2), ["reseller" => 'Reseller'], array_slice($tableHeaders, 2));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($messages)
                ->addIndexColumn()
                ->addColumn('mask', function ($row) {
                    $mask = ($row['mask']) ? $row['mask'] : "-";
                    return $mask;
                })
                ->addColumn('reseller', function ($row) {
                    $reseller = ($row['reseller_id']) ? $row['reseller']->reseller_name : "-";
                    return $reseller;
                })
                ->addColumn('username', function ($row) {
                    $username = $row['user']->username;
                    return $username;
                })
                ->addColumn('sent_time', function ($row) {
                    $sent_time = ($row['sent_time']) ? $row['sent_time'] : "-";
                    return $sent_time;
                })
                ->make(true);
        }

        $ajaxUrl = route('report.list');

        return view('backend.pages.report.outboxList', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function reportFailed(Request $request)
    {
        $title = 'Failed Message Report';
        $today = date('Y-m-d');
        if ($request->report_date == 'last_ninety_days') {
            $startDate = date('Y-m-d', strtotime('-90 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_month') {
            $startDate = date('Y-m-d', strtotime('first day of last month'));
            $today = date('Y-m-d', strtotime('last day of last month'));
        } elseif ($request->report_date == 'this_month') {
            $startDate = date('Y-m-1');
        } elseif ($request->report_date == 'last_thirteen_days') {
            $startDate = date('Y-m-d', strtotime('-30 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_seven_days') {
            $startDate = date('Y-m-d', strtotime('-7 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'yeasterday') {
            $startDate = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
            $today = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
        } else {
            $startDate = date('Y-m-d');
        }

        if (isset($request->report_date)) {
            if (Auth::user()->id_user_group == 4) {
                $messages = Outbox::with(['user'])->where('status', 'Failed')->where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    if ($request->user == '' && $request->reseller == '') {
                        $messages = Outbox::with(['user'])->where('status', 'Failed')->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
                    } else {
                        if ($request->user != '') {
                            $messages = Outbox::with(['user'])->where('user_id', $request->user)->where('status', 'Failed')->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
                        } else {
                            $messages = Outbox::with(['user'])->where('status', 'Failed')->whereHas("user", function ($query) use ($request) {
                                $query->where("reseller_id", $request->reseller);
                            })->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
                        }
                    }
                } else {
                    if ($request->user == '') {
                        $messages = Outbox::with(['user'])->where('status', 'Failed')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
                    } else {
                        $messages = Outbox::with(['user'])->where('user_id', $request->user)->where('status', 'Failed')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(write_time)'), [$startDate, $today])->get();
                    }
                }
            }
        } else {
            $messages = array();
        }

        $tableHeaders = [
            "id" => "",
            "username" => "User",
            "mask" => "Mask",
            "destmn" => "Number",
            "message" => "Message",
            "write_time" => "Write Time",
            "sent_time" => "Sent Time",
            "last_updated" => "Last Update",
            "status" => "Status",
            "smscount" => "Sms Count",
            "remarks" => "Reason"
        ];
        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 2), ["reseller" => 'Reseller'], array_slice($tableHeaders, 2));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($messages)
                ->addIndexColumn()
                ->addColumn('mask', function ($row) {
                    $mask = ($row['mask']) ? $row['mask'] : "-";
                    return $mask;
                })
                ->addColumn('reseller', function ($row) {
                    $reseller = ($row['reseller_id']) ? $row['reseller']->reseller_name : "-";
                    return $reseller;
                })
                ->addColumn('username', function ($row) {
                    $username = $row['user']->username;
                    return $username;
                })
                ->addColumn('sent_time', function ($row) {
                    $sent_time = ($row['sent_time']) ? $row['sent_time'] : "-";
                    return $sent_time;
                })
                ->make(true);
        }
        $ajaxUrl = route('report.failed.list');

        return view('backend.pages.report.outboxList', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function reportCount(Request $request)
    {
        $title = 'Message Summary Report';
        $today = date('Y-m-d');
        if ($request->report_date == 'last_ninety_days') {
            $startDate = date('Y-m-d', strtotime('-120 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_month') {
            $startDate = date('Y-m-d', strtotime('first day of last month'));
            $today = date('Y-m-d', strtotime('last day of last month'));
        } elseif ($request->report_date == 'this_month') {
            $startDate = date('Y-m-1');
        } elseif ($request->report_date == 'last_thirteen_days') {
            $startDate = date('Y-m-d', strtotime('-30 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_seven_days') {
            $startDate = date('Y-m-d', strtotime('-7 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'yeasterday') {
            $startDate = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
            $today = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
        } else {
            $startDate = date('Y-m-d');
        }

        if (isset($request->report_date)) {
            if (Auth::user()->id_user_group == 4) {
                $non_mask_count = Outbox::whereNull('mask')->where('status', 'Sent')->where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                $mask_count = Outbox::whereNotNull('mask')->where('status', 'Sent')->where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    if ($request->user == '' && $request->reseller == '') {
                        $non_mask_count = Outbox::whereNull('mask')->where('status', 'Sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                        $mask_count = Outbox::whereNotNull('mask')->where('status', 'Sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                    } else {
                        if ($request->user != '') {
                            $non_mask_count = Outbox::where('user_id', $request->user)->whereNull('mask')->where('status', 'Sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                            $mask_count = Outbox::where('user_id', $request->user)->whereNotNull('mask')->where('status', 'Sent')->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                        } else {
                            $non_mask_count = Outbox::whereNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) use ($request) {
                                $query->where("reseller_id", $request->reseller);
                            })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                            $mask_count = Outbox::whereNotNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) use ($request) {
                                $query->where("reseller_id", $request->reseller);
                            })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                        }
                    }
                } else {
                    if ($request->user == '') {
                        $non_mask_count = Outbox::whereNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                        $mask_count = Outbox::whereNotNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                    } else {
                        $non_mask_count = Outbox::where('user_id', $request->user)->whereNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                        $mask_count = Outbox::where('user_id', $request->user)->whereNotNull('mask')->where('status', 'Sent')->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(last_updated)'), [$startDate, $today])->sum('smscount');
                    }
                }
            }
        } else {
            $non_mask_count = '';
            $mask_count = '';
        }

        $tableHeaders = ["t_m_sent" => "Total Masking SMS Sent", "t_n_m_sent" => "Total Non Masking SMS Sent", "total"=>"Total SMS Sent"];

        if ($non_mask_count != '') {
            $data = array(
                't_m_sent'=>$mask_count,
                't_n_m_sent'=>$non_mask_count,
                'total'=>$mask_count + $non_mask_count
            );
            $datas[] = $data;
        } else {
            $datas = array();
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)->make(true);
        }

        $ajaxUrl = route('report.sms.count');
        return view('backend.pages.report.outboxCount', compact('title', 'tableHeaders', 'ajaxUrl'));
    }


    public function emailLogs(Request $request)
    {
        $title = 'All Email Logs';
        $today = date('Y-m-d');
        if ($request->report_date == 'last_ninety_days') {
            $startDate = date('Y-m-d', strtotime('-90 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_month') {
            $startDate = date('Y-m-d', strtotime('first day of last month'));
            $today = date('Y-m-d', strtotime('last day of last month'));
        } elseif ($request->report_date == 'this_month') {
            $startDate = date('Y-m-1');
        } elseif ($request->report_date == 'last_thirteen_days') {
            $startDate = date('Y-m-d', strtotime('-30 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'last_seven_days') {
            $startDate = date('Y-m-d', strtotime('-7 day' . date('Y-m-d')));
        } elseif ($request->report_date == 'yeasterday') {
            $startDate = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
            $today = date('Y-m-d', strtotime('-1 day' . date('Y-m-d')));
        } else {
            $startDate = date('Y-m-d');
        }

        if (isset($request->report_date)) {
            if (Auth::user()->id_user_group == 4) {
                $logs = EmailLog::with(['user'])->where('user_id', Auth::user()->id)->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today])->orderBy('updated_at', 'DESC');
            } else {
                if (empty(Auth::user()->reseller_id)) {
                    if ($request->user == '' && $request->reseller == '') {
                        $logs = EmailLog::with(['user'])->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today])->orderBy('updated_at', 'DESC');
                    } else {
                        if ($request->user != '') {
                            $logs = EmailLog::with(['user'])->where('user_id', $request->user)->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today]);
                        } else {
                            $logs = EmailLog::with(['user'])->whereHas("user", function ($query) use ($request) {
                                $query->where("reseller_id", $request->reseller);
                            })->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today])->orderBy('updated_at', 'DESC');
                        }
                    }
                } else {
                    if ($request->user == '') {
                        $logs = EmailLog::with(['user'])->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today])->orderBy('updated_at', 'DESC');
                    } else {
                        $logs = EmailLog::with(['user'])->where('user_id', $request->user)->whereHas("user", function ($query) {
                            $query->where("reseller_id", Auth::user()->reseller_id);
                        })->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today])->orderBy('updated_at', 'DESC');
                    }
                }
            }
        } else {
            $logs = array();
        }

        $tableHeaders = [
            "id" => "",
            "username" => "User",
            "domain" => "Domain",
            "from_email" => "From",
            "to_email" => "To",
            "batch" => "Batch",
            "write_time" => "Write Time",
            "updated_at" => "Last Update",
            "status" => "Status",
            "response" => "Response",
            "delivery_reports" => "Delivery Status",
            "opened" => "Opened",
        ];
        if (empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4) {
            $tableHeaders = array_merge(array_slice($tableHeaders, 0, 2), ["reseller" => 'Reseller'], array_slice($tableHeaders, 2));
        }

        if ($this->ajaxDatatable()) {
            return DataTables::of($logs)
                ->addIndexColumn()
                ->addColumn('reseller', function ($row) {
                    $reseller = ($row['reseller_id']) ? $row['reseller']->reseller_name : "-";
                    return $reseller;
                })
                ->addColumn('username', function ($row) {
                    $username = $row['user']->username;
                    return $username;
                })
                ->addColumn('sent_time', function ($row) {
                    $sent_time = ($row['sent_time']) ? $row['sent_time'] : "-";
                    return $sent_time;
                })
                ->make(true);
        }

        $ajaxUrl = route('report.email.list');

        return view('backend.pages.report.emailLogList', compact('title', 'tableHeaders', 'ajaxUrl'));
    }
}
