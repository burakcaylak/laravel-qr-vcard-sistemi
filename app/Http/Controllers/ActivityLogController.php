<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    /**
     * Get activity logs for the drawer
     */
    public function index(Request $request)
    {
        try {
            if (!Schema::hasTable('activity_logs')) {
                return response()->json([]);
            }

            $logs = DB::table('activity_logs')
                ->leftJoin('users', function($join) {
                    $join->on('activity_logs.causer_id', '=', 'users.id')
                         ->where('activity_logs.causer_type', '=', 'App\Models\User');
                })
                ->select(
                    'activity_logs.id',
                    'activity_logs.log_name',
                    'activity_logs.description',
                    'activity_logs.subject_type',
                    'activity_logs.subject_id',
                    'activity_logs.causer_type',
                    'activity_logs.causer_id',
                    'activity_logs.properties',
                    'activity_logs.event',
                    'activity_logs.batch_uuid',
                    'activity_logs.created_at',
                    'activity_logs.updated_at',
                    'users.name as user_name',
                    'users.email as user_email',
                    'users.profile_photo_path'
                )
                ->orderBy('activity_logs.created_at', 'desc')
                ->limit(50)
                ->get();

            // Convert properties JSON string to array if it exists and map profile_photo_path to profile_photo_url
            $logs = $logs->map(function($log) {
                if (isset($log->properties) && is_string($log->properties)) {
                    $log->properties = json_decode($log->properties, true);
                }
                // Map profile_photo_path to profile_photo_url for consistency
                if (isset($log->profile_photo_path)) {
                    $log->profile_photo_url = $log->profile_photo_path ? asset('storage/' . $log->profile_photo_path) : null;
                } else {
                    $log->profile_photo_url = null;
                }
                return $log;
            });

            return response()->json($logs);
        } catch (\Exception $e) {
            \Log::error('Activity logs yüklenirken hata: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

