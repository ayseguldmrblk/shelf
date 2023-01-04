<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Report;

class ReportController extends Controller
{
    public function reports($id)
    {
        $reports = Review::where('user_id', $id)->get();

        return response()->json($reports, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function delete($id)
    {
        $book = Report::where('id', $id)->delete();
    }

    public function add(Request $request)
    {
        $report = new Report;
        $report->user_id = $request->user_id;
        $report->message = $request->message;
        $report->save();
        return response()->json($review, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }
}
