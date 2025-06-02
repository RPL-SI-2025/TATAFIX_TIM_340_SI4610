document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const notificationButton = document.getElementById('notification-button');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');
    const markAllReadButton = document.getElementById('mark-all-read');
    
    // Only initialize if we have the notification elements (user is logged in)
    if (!notificationButton) return;
    
    // Toggle notification dropdown
    notificationButton.addEventListener('click', function() {
        notificationDropdown.classList.toggle('hidden');
        loadNotifications();
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });
    
    // Mark all notifications as read
    if (markAllReadButton) {
        markAllReadButton.addEventListener('click', function() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    updateNotificationCount();
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        });
    }
    
    // Load notifications
    function loadNotifications() {
        fetch('/notifications/list')
            .then(response => response.json())
            .then(data => {
                renderNotifications(data.notifications);
                updateBadgeCount(data.unread_count);
            })
            .catch(error => console.error('Error loading notifications:', error));
    }
    
    // Render notifications in the dropdown
    function renderNotifications(notifications) {
        if (!notificationList) return;
        
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                    Tidak ada notifikasi
                </div>
            `;
            return;
        }
        
        notificationList.innerHTML = '';
        
        notifications.forEach(notification => {
            const isRead = notification.read_at !== null;
            const notificationItem = document.createElement('div');
            notificationItem.className = `px-4 py-3 hover:bg-gray-50 ${isRead ? 'bg-white' : 'bg-blue-50'}`;
            
            // Determine icon based on notification type
            let iconSvg = '';
            switch(notification.type) {
                case 'success':
                    iconSvg = '<svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'error':
                    iconSvg = '<svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'warning':
                    iconSvg = '<svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                    break;
                default: // info
                    iconSvg = '<svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            }
            
            notificationItem.innerHTML = `
                <div class="flex relative">
                    <a href="${notification.link}" class="flex flex-grow" data-id="${notification.id}">
                        <div class="flex-shrink-0 mr-3 mt-1">
                            ${iconSvg}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="text-xs text-gray-600 mt-1">${notification.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                        </div>
                    </a>
                    <button class="delete-notification text-gray-400 hover:text-red-500 p-2" data-id="${notification.id}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;
            
            // Event listener untuk link notifikasi
            notificationItem.querySelector('a').addEventListener('click', function(e) {
                if (!isRead) {
                    e.preventDefault();
                    markAsRead(notification.id, this.getAttribute('href'));
                }
            });
            
            // Event listener untuk tombol hapus
            notificationItem.querySelector('.delete-notification').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                deleteNotification(notification.id, this.closest('div.px-4'));
            });
            
            notificationList.appendChild(notificationItem);
        });
    }
    
    // Mark a notification as read
    function markAsRead(id, redirectUrl) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
                window.location.href = redirectUrl;
            }
        })
        .catch(error => console.error('Error marking as read:', error));
    }
    
    // Update notification badge count
    function updateBadgeCount(count) {
        if (count > 0) {
            notificationBadge.textContent = count > 9 ? '9+' : count;
            notificationBadge.classList.remove('hidden');
        } else {
            notificationBadge.classList.add('hidden');
        }
    }
    
    // Update notification count
    function updateNotificationCount() {
        fetch('/notifications/count')
            .then(response => response.json())
            .then(data => {
                updateBadgeCount(data.count);
            })
            .catch(error => console.error('Error updating notification count:', error));
    }
    
    // Delete a notification
    function deleteNotification(id, element) {
        if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
            return;
        }
        
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the notification item from the DOM with animation
                element.style.opacity = '0';
                element.style.height = '0';
                element.style.overflow = 'hidden';
                element.style.transition = 'opacity 0.3s, height 0.5s';
                
                setTimeout(() => {
                    element.remove();
                    updateNotificationCount();
                    
                    // If no notifications left, show empty message
                    if (notificationList.children.length === 0) {
                        notificationList.innerHTML = `
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                Tidak ada notifikasi
                            </div>
                        `;
                    }
                }, 500);
            }
        })
        .catch(error => console.error('Error deleting notification:', error));
    }
    
    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) {
            // Today - show time
            return `Hari ini, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
        } else if (diffDays === 1) {
            // Yesterday
            return 'Kemarin';
        } else if (diffDays < 7) {
            // Within a week
            return `${diffDays} hari yang lalu`;
        } else {
            // More than a week
            return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
        }
    }
    
    // Initial load of notification count
    updateNotificationCount();
    
    // Periodically check for new notifications (every 60 seconds)
    setInterval(updateNotificationCount, 60000);
});
