<x-app-layout title="Profile">
    <div class="space-y-6">
        <div class="page-header">
            <div>
                <h2 class="page-title">Profile Anda</h2>
                <p class="page-subtitle">Update data diri dan amankan akun anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card p-6">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-white mb-4">Ubah Informasi Profile</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card p-6">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-white mb-4">Ganti Password</h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @role('admin')
            <div class="card p-6 border-rose-500/20 bg-rose-900/10">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-rose-500 mb-4">Danger Zone</h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endrole
        </div>
    </div>
</x-app-layout>
