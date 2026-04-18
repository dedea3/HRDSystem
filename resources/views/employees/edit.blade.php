<x-app-layout>
@section('title', 'Edit Karyawan')

<div class="max-w-2xl mx-auto space-y-6">
    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Karyawan</h2>
            <p class="page-subtitle">Edit data {{ $employee->name }}</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input @error('name') border-rose-500 @enderror"
                           value="{{ old('name', $employee->name) }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input @error('email') border-rose-500 @enderror"
                           value="{{ old('email', $employee->email) }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Password Baru <span class="text-slate-500 normal-case">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-input @error('password') border-rose-500 @enderror"
                           placeholder="Kosongkan jika tidak diubah">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Role</label>
                    <div class="flex flex-wrap gap-3 mt-1">
                        @foreach($roles as $role)
                        @php $checked = in_array($role->id, old('roles', $employee->roles->pluck('id')->toArray())); @endphp
                        <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-700 bg-slate-800/50 px-4 py-2.5 hover:border-indigo-500 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-900/30">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                   class="h-4 w-4 rounded border-slate-600 bg-slate-700 text-indigo-600 focus:ring-indigo-500"
                                   {{ $checked ? 'checked' : '' }}>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $role->display_name }}</p>
                                <p class="text-xs text-slate-400">{{ $role->description }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('roles')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary flex-1">Update Karyawan</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
