@php
 $user = Auth::user();
        $re_role = App\Models\Auth\Role::find($user->role_id ?? 26);
        $userPerms = json_decode($re_role->permission ?? '[]', true);
@endphp