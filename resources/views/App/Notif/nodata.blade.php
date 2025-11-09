
    <div class="flex flex-col items-center justify-center text-center animate-fade-in">
        <!-- Animated Empty Box -->
        <div class="relative mb-6">
            <div class="absolute inset-0 bg-purple-100 rounded-full blur-xl opacity-50 animate-pulse-slow"></div>
            <div class="relative">
                <svg class="w-32 h-32 animate-float" viewBox="0 0 200 200" fill="none">
                    <!-- Box -->
                    <rect x="40" y="60" width="120" height="100" rx="8" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2"/>
                    <rect x="40" y="60" width="120" height="30" rx="8" fill="#e5e7eb"/>
                    
                    <!-- Floating items -->
                    <circle cx="70" cy="110" r="8" fill="#a855f7" opacity="0.6">
                        <animate attributeName="cy" values="110;100;110" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="100" cy="120" r="10" fill="#ec4899" opacity="0.6">
                        <animate attributeName="cy" values="120;110;120" dur="2.5s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="130" cy="105" r="6" fill="#3b82f6" opacity="0.6">
                        <animate attributeName="cy" values="105;95;105" dur="1.8s" repeatCount="indefinite"/>
                    </circle>
                    
                    <!-- Lines in box -->
                    <line x1="60" y1="140" x2="110" y2="140" stroke="#d1d5db" stroke-width="3" stroke-linecap="round"/>
                    <line x1="60" y1="150" x2="140" y2="150" stroke="#d1d5db" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Data Available</h3>
        <p class="text-gray-600 mb-6 max-w-md">
            There's no data to display yet. Start by adding your first entry to see it appear here.
        </p>
    </div>
