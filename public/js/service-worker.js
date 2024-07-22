// self.addEventListener("install", (event) => {
//     event.waitUntil(
//         caches.open("my-cache").then((cache) => {
//             return cache.addAll([
//                 // Add the URLs of your static assets here
//                 // "/",
//                 "/app-210723.js",
//                 // Add more URLs as needed
//             ]);
//         })
//     );
// });

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});

self.addEventListener("activate", (event) => {
    // Clean up old caches if needed
});

self.addEventListener("message", (event) => {
    if (event.data && event.data.action === "showAlertAfterDelay") {
        const delay = event.data.delay || 3000; // Default delay is 3 seconds
        setTimeout(() => {
            self.registration.showNotification("Delayed Alert", {
                body: "This is a delayed alert!",
            });
        }, delay);
    }
});
