<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <!-- Use CDN instead of Vite -->
    <script src="https://cdn.jsdelivr.net/npm/@microsoft/signalr@7.0.0/dist/browser/signalr.min.js"></script>
</head>
<body>
    <h2>Notifications</h2>
    <ul id="notifications"></ul>

    <script>
        async function startConnection() {
            const res = await fetch('/api/negotiate');
            const data = await res.json();

            const connection = new signalR.HubConnectionBuilder()
                .withUrl(data.url, {
                    accessTokenFactory: () => data.accessToken
                })
                .build();

            connection.on("newNotification", (message) => {
                let ul = document.getElementById("notifications");
                let li = document.createElement("li");
                li.innerText = message;
                ul.appendChild(li);
            });

            await connection.start();
            console.log("SignalR Connected ✅");
        }

        startConnection().catch(err => console.error(err));
    </script>
</body>
</html>
