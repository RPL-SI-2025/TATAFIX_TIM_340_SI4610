document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const homeNotification = document.getElementById('home-notification');
    const homeNotificationTitle = document.getElementById('home-notification-title');
    const homeNotificationMessage = document.getElementById('home-notification-message');
    const closeHomeNotification = document.getElementById('close-home-notification');
    
    // Only initialize if we have the notification elements (user is logged in)
    if (!homeNotification) return;
    
    // Initially hide the notification
    homeNotification.style.opacity = '0';
    homeNotification.style.transform = 'translateX(20px)';
    homeNotification.style.display = 'block';
    
    // Close notification
    if (closeHomeNotification) {
        closeHomeNotification.addEventListener('click', function() {
            // Animate out
            homeNotification.style.opacity = '0';
            homeNotification.style.transform = 'translateX(20px)';
            
            // Hide after animation completes
            setTimeout(() => {
                homeNotification.style.display = 'none';
            }, 500);
            
            // Save to localStorage that notification was closed
            localStorage.setItem('home_notification_closed', 'true');
        });
    }
    
    // Check if notification was previously closed
    if (localStorage.getItem('home_notification_closed') === 'true') {
        return;
    }
    
    // Load latest notification
    function loadLatestNotification() {
        fetch('/notifications/list')
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    // Get the latest unread notification
                    const unreadNotifications = data.notifications.filter(n => n.read_at === null);
                    
                    if (unreadNotifications.length > 0) {
                        const latestNotification = unreadNotifications[0];
                        
                        // Update notification content
                        homeNotificationTitle.textContent = latestNotification.title;
                        homeNotificationMessage.textContent = latestNotification.message;
                        
                        // Show notification with animation
                        homeNotification.style.opacity = '1';
                        homeNotification.style.transform = 'translateX(0)';
                        
                        // Set notification link
                        const notificationLink = homeNotification.querySelector('a');
                        if (notificationLink && latestNotification.link) {
                            notificationLink.href = latestNotification.link;
                        }
                        
                        // Add appropriate color based on notification type
                        homeNotification.className = homeNotification.className.replace(/border-\w+-500/g, '');
                        
                        switch(latestNotification.type) {
                            case 'success':
                                homeNotification.classList.add('border-green-500');
                                break;
                            case 'error':
                                homeNotification.classList.add('border-red-500');
                                break;
                            case 'warning':
                                homeNotification.classList.add('border-yellow-500');
                                break;
                            default: // info
                                homeNotification.classList.add('border-blue-500');
                        }
                    }
                }
            })
            .catch(error => console.error('Error loading latest notification:', error));
    }
    
    // Load notification after a short delay (to make it noticeable)
    setTimeout(loadLatestNotification, 1500);
});
