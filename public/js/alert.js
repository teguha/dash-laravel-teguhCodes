
        {/* // Custom Alert Function */}
        function showAlert(options) {
            const { type, title, message, duration = 3000 } = options;

            // Icon and color configurations
            const configs = {
                success: {
                    icon: 'fa-check-circle',
                    iconBg: 'bg-green-100',
                    iconColor: 'text-green-500',
                    borderColor: 'border-green-500',
                    buttonBg: 'bg-green-500 hover:bg-green-600'
                },
                error: {
                    icon: 'fa-times-circle',
                    iconBg: 'bg-red-100',
                    iconColor: 'text-red-500',
                    borderColor: 'border-red-500',
                    buttonBg: 'bg-red-500 hover:bg-red-600'
                },
                warning: {
                    icon: 'fa-exclamation-triangle',
                    iconBg: 'bg-yellow-100',
                    iconColor: 'text-yellow-500',
                    borderColor: 'border-yellow-500',
                    buttonBg: 'bg-yellow-500 hover:bg-yellow-600'
                },
                info: {
                    icon: 'fa-info-circle',
                    iconBg: 'bg-blue-100',
                    iconColor: 'text-blue-500',
                    borderColor: 'border-blue-500',
                    buttonBg: 'bg-blue-500 hover:bg-blue-600'
                }
            };

            const config = configs[type] || configs.info;

            // Create alert HTML
            const alertHTML = `
                <div class="alert-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="alert-content bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border-t-4 ${config.borderColor}">
                        <div class="p-8">
                            <div class="flex flex-col items-center text-center">
                                <div class="${config.iconBg} rounded-full p-4 mb-4 icon-success">
                                    <i class="fas ${config.icon} ${config.iconColor} text-5xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">${title}</h3>
                                <p class="text-gray-600 mb-6 leading-relaxed">${message}</p>
                                <button onclick="closeAlert()" class="${config.buttonBg} text-white font-semibold py-3 px-8 rounded-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-check mr-2"></i>
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Inject alert into container
            const container = document.getElementById('alertContainer');
            container.innerHTML = alertHTML;

            // Auto close after duration (optional)
            if (duration > 0) {
                setTimeout(() => {
                    closeAlert();
                }, duration);
            }
        }

        {/* // Close alert function */}
        function closeAlert() {
            const container = document.getElementById('alertContainer');
            container.innerHTML = '';
        }

        {/* // Test alert functions */}
        function testSuccessAlert() {
            showAlert({
                type: 'success',
                title: 'Success!',
                message: 'Your data has been saved successfully.',
                duration: 0 // Set 0 for manual close only
            });
        }

        function testErrorAlert() {
            showAlert({
                type: 'error',
                title: 'Error!',
                message: 'Something went wrong. Please try again.',
                duration: 0
            });
        }

        function testWarningAlert() {
            showAlert({
                type: 'warning',
                title: 'Warning!',
                message: 'Please check your input before proceeding.',
                duration: 0
            });
        }

        function testInfoAlert() {
            showAlert({
                type: 'info',
                title: 'Information',
                message: 'This is an informational message for you.',
                duration: 0
            });
        }
