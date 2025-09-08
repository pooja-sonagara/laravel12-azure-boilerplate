<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Notifications</title>
    <!-- Use CDN instead of Vite -->
    <script src="https://cdn.jsdelivr.net/npm/@microsoft/signalr@7.0.0/dist/browser/signalr.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .header {
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            color: #495057;
            padding: 30px 40px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #f8f9fa;
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 400;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 20px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #6c757d;
        }

        .content {
            padding: 40px;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            margin-right: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        .notifications-container {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            min-height: 300px;
            position: relative;
        }

        .notifications-header {
            background: #495057;
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-count {
            background: #6c757d;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        #notifications {
            list-style: none;
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        #notifications:empty::before {
            content: "🔔 Waiting for notifications...";
            display: block;
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 60px 20px;
            font-size: 1.1rem;
        }

        .notification-item {
            background: #fff;
            margin-bottom: 12px;
            padding: 18px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            border-left: 3px solid #6c757d;
            animation: slideIn 0.3s ease-out;
            position: relative;
        }

        .notification-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: #f8f9fa;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .notification-icon-small {
            width: 36px;
            height: 36px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 16px;
            flex-shrink: 0;
        }

        .notification-text {
            flex: 1;
        }

        .notification-message {
            font-size: 0.95rem;
            color: #495057;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #6c757d;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 20px;
            border: none;
            font-size: 0.9rem;
        }

        .back-button:hover {
            background: #5a6268;
            text-decoration: none;
            color: white;
        }

        /* Scrollbar styling */
        #notifications::-webkit-scrollbar {
            width: 6px;
        }

        #notifications::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #notifications::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #notifications::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 20px;
            }
            
            .title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div class="notification-icon">🔔</div>
                <h1 class="title">Real-time Notifications</h1>
                <p class="subtitle">Stay updated with live SignalR messages</p>
            </div>
        </div>
        
        <div class="content">
            <a href="/" class="back-button">
                ← Back to Home
            </a>
            
            <div class="status-indicator">
                <div class="status-dot"></div>
                <span>Connected to SignalR Hub</span>
            </div>
            
            <div class="notifications-container">
                <div class="notifications-header">
                    <span>📢 Live Notifications</span>
                    <span class="notification-count" id="notification-count">0</span>
                </div>
                <ul id="notifications"></ul>
            </div>
        </div>
    </div>

    <script>
        let notificationCount = 0;

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
                li.className = "notification-item";
                
                const now = new Date();
                const timeString = now.toLocaleTimeString();
                
                li.innerHTML = `
                    <div class="notification-content">
                        <div class="notification-icon-small">📨</div>
                        <div class="notification-text">
                            <div class="notification-message">${message}</div>
                            <div class="notification-time">
                                🕒 ${timeString}
                            </div>
                        </div>
                    </div>
                `;
                
                ul.insertBefore(li, ul.firstChild); // Add new notifications at the top
                
                // Update notification count
                notificationCount++;
                document.getElementById('notification-count').textContent = notificationCount;
            });

            await connection.start();
            console.log("SignalR Connected ✅");
        }

        startConnection().catch(err => console.error(err));
    </script>
</body>
</html>
