# Social Authentication Setup Guide

This document explains how to set up social authentication for production environment.

## Overview

The application currently uses a **mock/test authentication system** for development. For production, you need to configure real OAuth providers.

## 🔐 Required OAuth Providers

### 1. Google OAuth 2.0

**Setup Steps:**
1. Go to: https://console.cloud.google.com/
2. Create a new project or select existing one
3. Go to **APIs & Services** → **Credentials**
4. Click **+ CREATE CREDENTIALS** → **OAuth 2.0 Client IDs**
5. Select **Web application**
6. Add authorized redirect URIs:
   - Development: `http://localhost:8080/auth/google/callback`
   - Production: `https://yourdomain.com/auth/google/callback`
7. Save the **Client ID** and **Client Secret**

**Environment Variables:**
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8080/auth/google/callback
```

### 2. Facebook Login

**Setup Steps:**
1. Go to: https://developers.facebook.com/
2. Click **My Apps** → **Add New App**
3. Choose **Business** → **Business Integration**
4. Add **Facebook Login** product
5. In Facebook Login settings, add redirect URIs:
   - Development: `http://localhost:8080/auth/facebook/callback`
   - Production: `https://yourdomain.com/auth/facebook/callback`
6. Save the **App ID** and **App Secret**

**Environment Variables:**
```env
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8080/auth/facebook/callback
```

### 3. Microsoft Azure AD

**Setup Steps:**
1. Go to: https://portal.azure.com/
2. Navigate to **Azure Active Directory**
3. Go to **App registrations** → **New registration**
4. Enter application name
5. Set **Supported account types** (usually "Accounts in any organizational directory")
6. Add redirect URI:
   - Development: `http://localhost:8080/auth/microsoft/callback`
   - Production: `https://yourdomain.com/auth/microsoft/callback`
7. Go to **Certificates & secrets** → **New client secret**
8. Save the **Application (client) ID** and **Client Secret**

**Environment Variables:**
```env
MICROSOFT_CLIENT_ID=your_microsoft_app_id
MICROSOFT_CLIENT_SECRET=your_microsoft_app_secret
MICROSOFT_REDIRECT_URI=http://localhost:8080/auth/microsoft/callback
```

## ⚙️ Environment Configuration

Update your `.env` file with the OAuth credentials:

```env
# Social Authentication
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"

MICROSOFT_CLIENT_ID=your_microsoft_app_id
MICROSOFT_CLIENT_SECRET=your_microsoft_app_secret
MICROSOFT_REDIRECT_URI="${APP_URL}/auth/microsoft/callback"
```

## 🚀 Production Deployment Checklist

### Before Deployment:
- [ ] Create OAuth apps for all three providers
- [ ] Add production redirect URIs to each OAuth app
- [ ] Test OAuth flow in development environment
- [ ] Set environment variables in production

### After Deployment:
- [ ] Update OAuth app redirect URIs to production domain
- [ ] Test authentication flow in production
- [ ] Verify user data is correctly saved
- [ ] Test tenant isolation with real users

## 🔧 Current Implementation Status

### ✅ Working:
- OAuth controllers and routes are implemented
- User model supports social providers
- Tenant isolation works with social authentication
- Session management and logout functionality

### 🔄 Needs Production Setup:
- Real OAuth credentials
- Production redirect URIs
- Environment variable configuration

## 🧪 Development Testing

For development, the application includes a test login system:
- URL: `http://localhost:8080/test-login`
- Creates a test user with tenant isolation
- Only works in `local` environment
- **Do not use in production**

## 🐛 Troubleshooting

### Common Issues:
1. **"Invalid redirect_uri"** - Check OAuth app redirect URIs
2. **"Client access denied"** - Verify OAuth app is properly configured
3. **Authentication fails** - Check environment variables are set correctly
4. **Tenant isolation not working** - Ensure TenantMiddleware is applied to routes

### Debug Tips:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify environment variables: `php artisan config:cache`
- Test OAuth URLs manually in browser
- Use browser developer tools to check redirect flows

## 🔒 Security Considerations

- Never commit OAuth secrets to version control
- Use environment variables for all credentials
- Enable HTTPS in production (required for OAuth)
- Regularly rotate OAuth secrets
- Monitor OAuth app permissions and usage

## 📚 Additional Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [Microsoft OAuth Documentation](https://docs.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-auth-code-flow)
