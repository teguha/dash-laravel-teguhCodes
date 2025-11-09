
<div class="flex flex-col items-center justify-center text-center animate-fade-in">
    <!-- Animated Cloud Icon -->
    <div class="relative mb-6">
        <div class="absolute inset-0 bg-cyan-100 rounded-full blur-xl opacity-50 animate-pulse-slow"></div>
        <div class="relative">
            <svg class="w-32 h-32 animate-float" viewBox="0 0 200 200" fill="none">
                <!-- Rocket -->
                <path d="M100 30 Q110 40 110 60 L110 100 Q110 110 100 115 Q90 110 90 100 L90 60 Q90 40 100 30 Z" 
                    fill="#06b6d4" stroke="#0891b2" stroke-width="2"/>
                <ellipse cx="100" cy="60" rx="10" ry="15" fill="#67e8f9"/>
                <path d="M110 100 L120 120 L110 115 Z" fill="#f87171"/>
                <path d="M90 100 L80 120 L90 115 Z" fill="#f87171"/>
                
                <!-- Flames -->
                <g transform="translate(100, 115)">
                    <ellipse cx="-5" cy="15" rx="8" ry="15" fill="#fb923c" opacity="0.7">
                        <animate attributeName="ry" values="15;20;15" dur="0.5s" repeatCount="indefinite"/>
                    </ellipse>
                    <ellipse cx="5" cy="15" rx="8" ry="15" fill="#fbbf24" opacity="0.7">
                        <animate attributeName="ry" values="15;22;15" dur="0.6s" repeatCount="indefinite"/>
                    </ellipse>
                    <ellipse cx="0" cy="18" rx="6" ry="18" fill="#fef08a" opacity="0.8">
                        <animate attributeName="ry" values="18;25;18" dur="0.4s" repeatCount="indefinite"/>
                    </ellipse>
                </g>
                
                <!-- Stars -->
                <g opacity="0.6">
                    <circle cx="40" cy="50" r="2" fill="#fbbf24">
                        <animate attributeName="opacity" values="1;0.3;1" dur="1.5s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="160" cy="70" r="2" fill="#fbbf24">
                        <animate attributeName="opacity" values="0.3;1;0.3" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="50" cy="120" r="2" fill="#fbbf24">
                        <animate attributeName="opacity" values="1;0.5;1" dur="1.8s" repeatCount="indefinite"/>
                    </circle>
                </g>
            </svg>
        </div>
    </div>

    <h3 class="text-2xl font-bold text-gray-900 mb-2">Connection Error</h3>
    <p class="text-gray-600 mb-6 max-w-md">
        We're having trouble loading your data. Please check your connection and try again.
    </p>
</div>
