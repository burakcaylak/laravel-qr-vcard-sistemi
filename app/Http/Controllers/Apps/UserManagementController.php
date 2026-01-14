<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.user-management.users.list');
    }

    public function create()
    {
        // Editör rolüne sahip kullanıcılar user ekleyemez
        if (auth()->user()->hasRole('editor')) {
            abort(403, __('common.unauthorized_action'));
        }
        
        $roles = Role::all();
        return view('pages.apps.user-management.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        // Editör rolüne sahip kullanıcılar user ekleyemez
        if (auth()->user()->hasRole('editor')) {
            abort(403, __('common.unauthorized_action'));
        }
        
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now(); // E-posta doğrulaması gerekmiyor, otomatik onaylı
        $data['last_login_at'] = null; // Yeni kullanıcı henüz giriş yapmadı
        $data['last_login_ip'] = null;
        
        $user = User::create($data);
        $user->assignRole($request->role);

        return redirect()->route('user-management.users.index')->with('success', __('common.user_created'));
    }

    public function show(User $user)
    {
        // Son giriş logları - activity_logs tablosundan
        $loginLogs = collect();
        if (Schema::hasTable('activity_logs')) {
            $loginLogs = DB::table('activity_logs')
                ->where(function($query) use ($user) {
                    $query->where('subject_type', User::class)
                          ->where('subject_id', $user->id);
                })
                ->where(function($query) {
                    $query->where('description', 'like', '%giriş%')
                          ->orWhere('description', 'like', '%login%')
                          ->orWhere('event', 'login');
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Events & Logs - Kullanıcının yaptığı tüm işlemler
        $activityLogs = collect();
        if (Schema::hasTable('activity_logs')) {
            $activityLogs = DB::table('activity_logs')
                ->where('causer_id', $user->id)
                ->where('causer_type', User::class)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        }

        return view('pages.apps.user-management.users.show', compact('user', 'loginLogs', 'activityLogs'));
    }

    public function edit(User $user)
    {
        // Editör rolüne sahip kullanıcılar sadece kendi profillerini düzenleyebilir
        if (auth()->user()->hasRole('editor') && auth()->id() !== $user->id) {
            abort(403, __('common.unauthorized_action'));
        }
        
        $roles = Role::all();
        return view('pages.apps.user-management.users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        // Editör rolüne sahip kullanıcılar sadece kendi profillerini güncelleyebilir
        if (auth()->user()->hasRole('editor') && auth()->id() !== $user->id) {
            abort(403, __('common.unauthorized_action'));
        }
        
        $data = $request->validated();
        
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        
        // Editör rolüne sahip kullanıcılar rol değiştiremez
        if (!auth()->user()->hasRole('editor')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('user-management.users.show', $user)->with('success', __('common.user_updated'));
    }

    public function destroy(User $user)
    {
        // Editör rolüne sahip kullanıcılar user silemez
        if (auth()->user()->hasRole('editor')) {
            abort(403, __('common.unauthorized_action'));
        }
        
        if ($user->id === auth()->id()) {
            return redirect()->route('user-management.users.index')->with('error', __('common.cannot_delete_self'));
        }

        $user->delete();
        return redirect()->route('user-management.users.index')->with('success', __('common.user_deleted'));
    }
}
