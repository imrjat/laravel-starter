<button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none pl-1 pt-4 pr-2">
    <svg class="w-6 transition ease-in-out duration-150 text-white" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

<div class="py-4">
    <a href="{{ route('dashboard') }}" class="text-gray-100 font-bold">
        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
    </a>
</div>

@can('view_dashboard')
    <x-nav.link route="dashboard" icon="fas fa-home">{{ __('Dashboard') }}</x-nav.link>
@endcan

@if(can('view_system_settings') || can('view_roles') || can('view_audit_trails') || can('view_sent_emails'))
    <x-nav.group label="Settings" route="admin.settings" icon="fas fa-cogs">
        @can('view_audit_trails')
            <x-nav.group-item route="admin.settings.audit-trails.index" icon="far fa-circle">Audit Trails</x-nav.group-item>
        @endcan

        @can('view_roles')
            <x-nav.group-item route="admin.settings.roles.index" icon="far fa-circle">Roles</x-nav.group-item>
        @endcan

        @can('view_sent_emails')
            <x-nav.group-item route="admin.settings.sent-emails" icon="far fa-circle">Sent Emails</x-nav.group-item>
        @endcan

        @can('view_system_settings')
            <x-nav.group-item route="admin.settings" icon="far fa-circle">System Settings</x-nav.group-item>
        @endcan
    </x-nav.group>
@endif

@can('view_users')
    <x-nav.link route="admin.users.index" icon="fas fa-users">Users</x-nav.link>
@endcan