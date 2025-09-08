# Laravel 12 Azure Boilerplate

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

A comprehensive Laravel 12 boilerplate project with Azure integrations, featuring Microsoft SSO authentication, real-time notifications with Azure SignalR Service, and Azure Blob Storage for file management.

## 🚀 Features

This boilerplate includes the following Azure integrations:

- **Microsoft Azure SSO** - Single Sign-On authentication using Azure Active Directory
- **Azure SignalR Service** - Real-time notifications and messaging
- **Azure Blob Storage** - Cloud file storage and management
- **Laravel 12** - Latest Laravel framework
- **Docker Support** - Containerized development environment

## 📋 Prerequisites

### For Local Development:
- PHP 8.3 or higher
- Composer
- Azure Account with active subscription
- Azure Active Directory tenant for SSO

### For Containerized setup:
- Docker & Docker Compose
- Azure Account with active subscription
- Azure Active Directory tenant for SSO

> **Note:** If you're using Docker, you don't need PHP or Composer installed locally as they're included in the container.

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/pooja-sonagara/laravel12-azure-boilerplate.git
   cd laravel12-azure-boilerplate
   ```

2. **Configure Azure credentials**
   ```bash
   # Edit .env file with your Azure credentials (file is automatically created)
   nano .env
   ```

3. **Build and start containers**
   ```bash
   docker-compose up -d
   ```

> **Note:** The Docker container automatically handles:
> - Installing PHP dependencies via Composer
> - Generating application key
> - Setting up proper permissions
> - Configuring the environment

The application will be available at `http://localhost:8080`

**Services included:**
- **Laravel App** (PHP 8.3-FPM)
- **Nginx** (Web server on port 8080)
- **MySQL** (Database on port 3307)
- **Redis** (Caching)

## ⚙️ Azure Configuration

### Microsoft Azure SSO Configuration

Add the following credentials to your `.env` file:

```env
# Azure SSO Configuration
AZURE_CLIENT_ID=your_application_id
AZURE_CLIENT_SECRET=your_client_secret
AZURE_TENANT_ID=your_tenant_id
AZURE_REDIRECT_URI=http://localhost:8080/auth/azure/callback
```

**How to get Azure SSO credentials:**

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **Azure Active Directory** → **App registrations**
3. Click **New registration** or select existing app
4. Set **Redirect URI** to `http://localhost:8080/auth/azure/callback`
5. Go to **Certificates & secrets** → **Client secrets** → **New client secret**
6. Copy the following values:
   - **Application (client) ID** → `AZURE_CLIENT_ID`
   - **Client secret value** → `AZURE_CLIENT_SECRET`
   - **Directory (tenant) ID** → `AZURE_TENANT_ID`

### Azure Storage Configuration

### Azure Storage Configuration

Add the following credentials to your `.env` file:

```env
# Azure Storage Configuration
AZURE_STORAGE_ACCOUNT_NAME=your_storage_account_name
AZURE_STORAGE_ACCOUNT_KEY=your_storage_account_key
AZURE_STORAGE_CONTAINER=your_container_name
```

**How to get Azure Storage credentials:**

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **Storage accounts**
3. Create or select your storage account
4. Go to **Access keys** in the left menu
5. Copy the **Storage account name** and **Key1** or **Key2**
6. Create a container in **Containers** section (e.g., "uploads")

### Azure SignalR Service Configuration

Add the following to your `.env` file:

```env
# Azure SignalR Service Configuration
SIGNALR_CONNECTION_STRING=Endpoint=https://your-signalr.service.signalr.net;AccessKey=your_access_key;Version=1.0;
SIGNALR_HUB_NAME=NotificationHub
```

**How to get Azure SignalR credentials:**

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **SignalR Service**
3. Create a new SignalR service or select existing one
4. Go to **Keys** in the left menu
5. Copy the **Connection string** (Primary or Secondary)
6. Set your hub name (default: "NotificationHub")

## 🔐 Testing Microsoft SSO

### 1. Access the Login Page

Visit: `http://localhost:8080/auth/azure`

This will redirect you to Microsoft's login page where you can sign in with your Azure AD credentials.

### 2. Test the Authentication Flow

1. **Initiate Login:**
   ```
   GET /auth/azure
   ```
   This redirects to Microsoft's OAuth endpoint

2. **Handle Callback:**
   ```
   GET /auth/azure/callback
   ```
   Microsoft redirects back with authorization code

3. **View User Data:**
   Currently displays user information using `dd($user)` for testing

## 🧪 Testing Azure SignalR

### 1. Access the Notifications Page

Visit: `http://localhost:8080/notifications`

This page displays live notifications in real-time using the Blade template.

### 2. Test Real-time Notifications Using API

#### Method 1: Using cURL (Recommended)
```bash
# Send a test notification via API
curl -X POST http://localhost:8080/api/notify \
  -H "Content-Type: application/json" \
  -d '{"message": "Custom notification message from API."}'
```

### 3. Expected Behavior

When you send a notification via API:
1. **API Endpoint**: `/api/notify` receives the POST request
2. **Backend**: Laravel sends notification to User ID 1 through SignalRChannel
3. **Azure SignalR**: Service receives and broadcasts the message
4. **Live Updates**: The notifications page automatically displays new notifications
5. **UI Elements**: 
   - New notification appears at the top of the list
   - Notification count updates automatically
   - Timestamp shows current time
   - Message icon (📨) appears

### 4. Current Implementation

**SignalR Real-time Notifications:**
- Shows **live notifications only** that disappear on page refresh
- Real-time delivery to connected clients while page is open
- Notifications are also saved to database but not displayed on page load
- For permanent messages, you can fetch from database separately

## 🗂️ Testing Azure Storage

### Upload Test Files

1. **Navigate to storage interface:**
   - Visit: `http://localhost:8080/storage/azure`
   - Use the Azure storage functionality in your application

2. **Verify uploads in Azure:**
   - Go to Azure Portal → Storage Account → Containers
   - Check your configured container for uploaded files

### Storage Configuration Files

- **Filesystem config:** `config/filesystems.php`
- **Storage Service Provider:** `app/Providers/AppServiceProvider.php`

