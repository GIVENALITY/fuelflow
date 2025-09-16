const CACHE_NAME = "fuelflow-v1";
const urlsToCache = [
    "/",
    "/css/material-dashboard.css",
    "/js/material-dashboard.js",
    "/img/logo-ct.png",
    "/img/favicon.png",
];

// Install event
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log("Opened cache");
            return cache.addAll(urlsToCache);
        })
    );
});

// Fetch event
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            // Return cached version or fetch from network
            return response || fetch(event.request);
        })
    );
});

// Activate event
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log("Deleting old cache:", cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Background sync for offline actions
self.addEventListener("sync", (event) => {
    if (event.tag === "background-sync") {
        event.waitUntil(doBackgroundSync());
    }
});

function doBackgroundSync() {
    // Handle offline actions when connection is restored
    return new Promise((resolve) => {
        // Check for pending offline actions
        // This would typically involve checking IndexedDB for pending requests
        console.log("Background sync triggered");
        resolve();
    });
}

// Push notifications
self.addEventListener("push", (event) => {
    const options = {
        body: event.data ? event.data.text() : "New notification from FuelFlow",
        icon: "/img/logo-ct.png",
        badge: "/img/logo-ct.png",
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1,
        },
        actions: [
            {
                action: "explore",
                title: "View Details",
                icon: "/img/icons/checkmark.png",
            },
            {
                action: "close",
                title: "Close",
                icon: "/img/icons/xmark.png",
            },
        ],
    };

    event.waitUntil(
        self.registration.showNotification("FuelFlow Notification", options)
    );
});

// Notification click
self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    if (event.action === "explore") {
        event.waitUntil(clients.openWindow("/notifications"));
    }
});
