@extends('App.Layout.index')

@section('title')
    profile-user
@endsection

@section('content')
    <main class="p-6">

        <div class="min-h-screen">

            <!-- Profile Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">

                <!-- Tabs & Content -->
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-1 gap-6">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Tab Navigation -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex border-b border-gray-200 overflow-x-auto">
                                <button onclick="showTab('profile')" class="tab-btn active px-6 py-4 text-sm font-semibold border-b-2 border-blue-500 text-blue-600 whitespace-nowrap">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Profile
                                </button>
                                
                                <button onclick="showTab('activity')" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                                    <i class="fas fa-clock mr-2"></i>
                                    Activity
                                </button>

                                <button onclick="showTab('settings')" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                                    <i class="fas fa-cog mr-2"></i>
                                    Settings
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div class="p-6">
                                <!-- Activity Tab -->
                                <div id="activity" class="tab-content">
                                    <div class="space-y-4">
                                        <!-- Activity Item -->
                                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-check text-blue-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 mb-1">Completed project milestone</h3>
                                                <p class="text-sm text-gray-600">Successfully delivered the dashboard redesign project</p>
                                                <p class="text-xs text-gray-500 mt-2">2 hours ago</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-trophy text-green-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 mb-1">Achievement unlocked</h3>
                                                <p class="text-sm text-gray-600">Earned "Design Master" badge for completing 100 projects</p>
                                                <p class="text-xs text-gray-500 mt-2">1 day ago</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-users text-purple-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 mb-1">Joined new team</h3>
                                                <p class="text-sm text-gray-600">Became a member of the Mobile App Design team</p>
                                                <p class="text-xs text-gray-500 mt-2">3 days ago</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-star text-orange-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 mb-1">Received 5-star review</h3>
                                                <p class="text-sm text-gray-600">Client praised your work on the e-commerce redesign</p>
                                                <p class="text-xs text-gray-500 mt-2">1 week ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Tab -->
                                <div id="timeline" class="tab-content hidden">
                                    <div class="relative">
                                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                        <div class="space-y-6">
                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2024
                                                </div>
                                                <div class="flex-1 pb-8">
                                                    <h3 class="font-bold text-gray-900 mb-2">Senior Product Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">TechCorp Inc. • Full-time</p>
                                                    <p class="text-sm text-gray-500">Leading design team and creating innovative solutions for enterprise clients.</p>
                                                </div>
                                            </div>

                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2022
                                                </div>
                                                <div class="flex-1 pb-8">
                                                    <h3 class="font-bold text-gray-900 mb-2">Product Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">StartupXYZ • Full-time</p>
                                                    <p class="text-sm text-gray-500">Designed core features for mobile and web applications.</p>
                                                </div>
                                            </div>

                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2020
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-gray-900 mb-2">UI/UX Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">DesignStudio • Freelance</p>
                                                    <p class="text-sm text-gray-500">Created beautiful interfaces for various clients and projects.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Tab -->
                                <div id="settings" class="tab-content hidden">
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="font-bold text-gray-900 mb-4">Account Settings</h3>
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-gray-900">Email Notifications</p>
                                                        <p class="text-sm text-gray-500">Receive email about your account activity</p>
                                                    </div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" checked class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    </label>
                                                </div>

                                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-gray-900">Push Notifications</p>
                                                        <p class="text-sm text-gray-500">Receive push notifications on your devices</p>
                                                    </div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    </label>
                                                </div>

                                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-gray-900">Two-Factor Authentication</p>
                                                        <p class="text-sm text-gray-500">Add an extra layer of security</p>
                                                    </div>
                                                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">
                                                        Enable
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="font-bold text-gray-900 mb-4">Privacy Settings</h3>
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-gray-900">Profile Visibility</p>
                                                        <p class="text-sm text-gray-500">Control who can see your profile</p>
                                                    </div>
                                                    <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                                        <option>Public</option>
                                                        <option>Friends Only</option>
                                                        <option>Private</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="font-bold text-gray-900 mb-4 text-red-600">Danger Zone</h3>
                                            <div class="space-y-3">
                                                <button class="w-full p-4 border-2 border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                    Deactivate Account
                                                </button>
                                                <button class="w-full p-4 border-2 border-red-300 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors font-medium">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete Account
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Spacing -->
            <div class="h-20"></div>
        </div>


        {{-- alert --}}
        <div id="alertContainer"></div>
    </main>


@endsection


@push('custom-scripts')

    
@endpush

