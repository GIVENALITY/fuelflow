@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Notifications</h6>
                            <p class="text-sm text-secondary mb-0">Stay updated with your latest activities</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-1"></i>
                                Mark All Read
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="refreshNotifications()">
                                <i class="fas fa-sync-alt me-1"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="card">
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action border-0 py-3 {{ $notification->read_at ? '' : 'bg-light' }}" 
                                     data-notification-id="{{ $notification->id }}"
                                     onclick="markAsRead({{ $notification->id }})">
                                    <div class="d-flex align-items-start">
                                        <!-- Notification Icon -->
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-sm {{ $notification->read_at ? 'bg-secondary' : 'bg-primary' }} rounded-circle d-flex align-items-center justify-content-center">
                                                @switch($notification->type)
                                                    @case('request_approved')
                                                        <i class="fas fa-check text-white"></i>
                                                        @break
                                                    @case('request_rejected')
                                                        <i class="fas fa-times text-white"></i>
                                                        @break
                                                    @case('request_assigned')
                                                        <i class="fas fa-user-tag text-white"></i>
                                                        @break
                                                    @case('fuel_dispensed')
                                                        <i class="fas fa-gas-pump text-white"></i>
                                                        @break
                                                    @case('request_completed')
                                                        <i class="fas fa-flag-checkered text-white"></i>
                                                        @break
                                                    @case('credit_limit_alert')
                                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                                        @break
                                                    @case('low_stock_alert')
                                                        <i class="fas fa-exclamation-circle text-white"></i>
                                                        @break
                                                    @case('receipt_verified')
                                                        <i class="fas fa-receipt text-white"></i>
                                                        @break
                                                    @case('payment_received')
                                                        <i class="fas fa-credit-card text-white"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-bell text-white"></i>
                                                @endswitch
                                            </div>
                                        </div>

                                        <!-- Notification Content -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 {{ $notification->read_at ? 'text-dark' : 'text-dark fw-bold' }}">
                                                        {{ $notification->title }}
                                                    </h6>
                                                    <p class="mb-1 text-sm text-secondary">
                                                        {{ $notification->message }}
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                
                                                <!-- Unread Indicator -->
                                                @if(!$notification->read_at)
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-primary rounded-pill">New</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Additional Data -->
                                            @if($notification->data && count($notification->data) > 0)
                                                <div class="mt-2">
                                                    @if(isset($notification->data['vehicle_plate']))
                                                        <span class="badge bg-light text-dark me-1">
                                                            <i class="fas fa-car me-1"></i>
                                                            {{ $notification->data['vehicle_plate'] }}
                                                        </span>
                                                    @endif
                                                    @if(isset($notification->data['request_id']))
                                                        <span class="badge bg-light text-dark me-1">
                                                            <i class="fas fa-hashtag me-1"></i>
                                                            #{{ $notification->data['request_id'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">You'll see your notifications here when they arrive.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time notification toast -->
<div id="notificationToast" class="toast position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
    <div class="toast-header bg-primary text-white">
        <i class="fas fa-bell me-2"></i>
        <strong class="me-auto">New Notification</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toastBody">
        <!-- Notification content will be inserted here -->
    </div>
</div>

<script>
// Real-time notifications
let notificationChannel;
let unreadCount = {{ $unreadCount }};

// Initialize Pusher for real-time notifications
document.addEventListener('DOMContentLoaded', function() {
    // Check if Pusher is available
    if (typeof Pusher !== 'undefined') {
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true
        });

        // Subscribe to user's private channel
        notificationChannel = pusher.subscribe('private-user.{{ auth()->id() }}');
        
        // Listen for new notifications
        notificationChannel.bind('notification.sent', function(data) {
            showNotificationToast(data);
            updateUnreadCount();
            refreshNotifications();
        });
    }
});

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('bg-light');
                notificationElement.querySelector('.fw-bold').classList.remove('fw-bold');
                const newBadge = notificationElement.querySelector('.badge');
                if (newBadge) {
                    newBadge.remove();
                }
            }
            updateUnreadCount();
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            document.querySelectorAll('[data-notification-id]').forEach(element => {
                element.classList.remove('bg-light');
                element.querySelector('.fw-bold')?.classList.remove('fw-bold');
                element.querySelector('.badge')?.remove();
            });
            updateUnreadCount();
        }
    });
}

function refreshNotifications() {
    location.reload();
}

function showNotificationToast(notification) {
    const toastBody = document.getElementById('toastBody');
    toastBody.innerHTML = `
        <div>
            <h6 class="mb-1">${notification.title}</h6>
            <p class="mb-0 text-sm">${notification.message}</p>
        </div>
    `;
    
    const toast = new bootstrap.Toast(document.getElementById('notificationToast'));
    toast.show();
}

function updateUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            unreadCount = data.count;
            // Update any unread count indicators in the UI
            const countElements = document.querySelectorAll('.notification-count');
            countElements.forEach(element => {
                element.textContent = unreadCount;
                element.style.display = unreadCount > 0 ? 'inline' : 'none';
            });
        });
}

// Update unread count on page load
updateUnreadCount();
</script>
@endsection
