# ✈️ Travix - Airlines Reservation System

A comprehensive flight booking and reservation system built with Laravel 12, Livewire, PostgreSQL, and Tailwind CSS. Features real flight data integration via AviationStack API and Google OAuth authentication.

## 🌟 Features

### User Features

- **Google OAuth Authentication** - Secure login with Google
- **Flight Search** - Real-time flight search with AviationStack API integration
- **Multi-Passenger Booking** - Book for multiple passengers with different travel classes
- **Seat Selection** - Interactive seat map with real-time availability
- **Baggage Management** - Add extra baggage with automatic price calculation
- **Mock Payment System** - Multiple payment methods (Credit Card, Bank Transfer, E-Wallet)
- **Online Check-in** - Check-in within 24 hours before departure
- **Digital Boarding Pass** - QR code boarding pass generation
- **Booking Management** - View, cancel unpaid bookings
- **Real-time Flight Status** - Live flight status updates

### Admin Features (Optional)

- **Booking Management** - View and manage all bookings
- **Flight Status Management** - Manual flight status updates
- **System Monitoring** - Track booking and flight operations

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL 14+
- Google OAuth Credentials Gmail
- AviationStack API Key (Free tier available)

## 🚀 Installation

### 1. Clone & Install Dependencies

```powershell
cd c:\laragon\www\Travix
composer install
npm install
```

### 2. Environment Configuration

```powershell
cp .env.example .env
```

Edit `.env` and configure:

```env
APP_NAME=Travix
APP_URL=http://localhost

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=travix
DB_USERNAME=postgres
DB_PASSWORD=your_password

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback

# AviationStack API
AVIATIONSTACK_API_KEY=your_api_key
AVIATIONSTACK_BASE_URL=http://api.aviationstack.com/v1
```

### 3. Generate Application Key

```powershell
php artisan key:generate
```

### 4. Create Database

Create PostgreSQL database:

```sql
CREATE DATABASE travix;
```

### 5. Run Migrations & Seeders

```powershell
php artisan migrate:fresh --seed
```

This will create:

- 15 Airlines (Indonesian & International)
- 15 Airports (Domestic & International)
- 5 Aircraft manufacturers & models
- 12+ Aircraft instances
- 3 Travel classes (Economy, Business, First Class)
- 15 Flight schedules
- 400+ Flights (for next 30 days)
- Seat maps for all aircraft
- Flight seat prices

### 6. Create Storage Link

```powershell
php artisan storage:link
```

### 7. Build Assets

```powershell
npm run build
```

For development:

```powershell
npm run dev
```

### 8. Start Development Server

```powershell
php artisan serve
```

Visit: http://localhost:8000

## 🔑 Getting API Credentials

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable **Google+ API**
4. Create OAuth 2.0 credentials:
    - Application type: Web application
    - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
5. Copy Client ID and Client Secret to `.env`

### AviationStack API Setup

1. Register at [AviationStack](https://aviationstack.com/)
2. Get your free API key (100 requests/month)
3. Add to `.env` as `AVIATIONSTACK_API_KEY`

## 📁 Project Structure

```
Travix/
├── app/
│   ├── Enums/          # Status enums (Booking, Payment, Flight)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── SocialiteController.php
│   │   │   ├── AdminController.php
│   │   │   ├── BookingController.php
│   │   │   ├── DashboardController.php
│   │   │   └── FlightController.php
│   │   └── Requests/    # Form validation
│   │       ├── StoreBookingRequest.php
│   │       ├── UpdateSeatAssignmentRequest.php
│   │       └── CheckInRequest.php
│   ├── Livewire/        # Livewire components
│   │   ├── FlightSearch.php
│   │   ├── BookingForm.php
│   │   ├── SeatSelection.php
│   │   ├── CheckInProcess.php
│   │   └── UserDashboard.php
│   ├── Models/          # 20 Eloquent models
│   └── Services/
│       ├── AviationStackService.php
│       └── QRCodeService.php
├── database/
│   ├── migrations/      # 17 migration files
│   └── seeders/         # 8 comprehensive seeders
├── resources/
│   └── views/
│       ├── layouts/
│       ├── livewire/
│       ├── bookings/
│       └── flights/
└── routes/
    └── web.php
```

## 🗄️ Database Schema

### Core Tables

- **users** - User accounts (Google OAuth)
- **airlines** - Airline information
- **airports** - Airport details with IATA codes
- **aircraft_manufacturers** / **aircraft** / **aircraft_instances** - Aircraft management
- **travel_classes** - Economy, Business, First Class
- **seat_maps** - Seat configurations per aircraft
- **schedules** - Recurring flight schedules
- **flights** - Individual flight instances
- **flight_seat_prices** - Dynamic pricing per class

### Booking Tables

- **bookings** - Booking master data
- **booking_passengers** - Passenger details
- **booking_flights** - Booking-flight relation (many-to-many)
- **seat_assignments** - Assigned seats
- **payments** - Payment records
- **baggage** - Extra baggage
- **check_ins** - Check-in records
- **boarding_passes** - Digital boarding passes
- **flight_status_logs** - Status change history

## 🎯 Usage Guide

### For Users

1. **Login**: Click "Login with Google" on homepage
2. **Search Flights**: Enter origin, destination, date
3. **Book Flight**:
    - Fill passenger details
    - Select seats
    - Add baggage (optional)
    - Complete payment
4. **Check-in**: Available H-24 before departure
5. **Boarding Pass**: Download/view QR code boarding pass

### For Admins

Access admin panel at `/admin/bookings` (no authentication middleware yet - add as needed)

## 🔧 Configuration

### Travel Class Price Multipliers

Edit `database/seeders/TravelClassSeeder.php`:

```php
'Economy' => 1.0x base price
'Business' => 3.0x base price
'First Class' => 5.0x base price
```

### Baggage Pricing

Edit `BookingController::addBaggage()`:

```php
$fee = $request->weight * 10; // $10 per kg
```

### Check-in Window

Edit `CheckInRequest.php`:

```php
if ($departureTime->diffInHours($now) > 24) // H-24 rule
```

## 🚨 Known Issues & TODOs

### Lint Warnings (Non-breaking)

- Controller middleware call method (works but shows warning)
- Schedule type hint in FlightSeeder (works but shows warning)

### Future Enhancements

- Admin role middleware
- Email notifications (booking confirmation, boarding pass)
- PDF boarding pass export
- Payment gateway integration (Midtrans SDK already installed)
- Multi-language support
- PWA for mobile
- Seat upgrade functionality
- Loyalty program

## 🧪 Testing

### Manual Testing Checklist

1. ✅ Google OAuth login/logout
2. ✅ Flight search (requires API key)
3. ✅ Create booking with passengers
4. ✅ Seat selection
5. ✅ Add baggage
6. ✅ Process payment
7. ✅ View booking details
8. ✅ Check-in (H-24)
9. ✅ View boarding pass with QR
10. ✅ Cancel unpaid booking

## 📦 Packages Used

- **laravel/framework** ^12.0 - Core framework
- **livewire/livewire** ^3.4 - Interactive UI components
- **laravel/socialite** ^5.12 - Google OAuth
- **simplesoftwareio/simple-qrcode** ^4.2 - QR code generation
- **barryvdh/laravel-dompdf** ^3.1 - PDF generation
- **midtrans/midtrans-php** ^2.5 - Payment gateway (optional)
- **tailwindcss** ^4.1 - CSS framework

## 🤝 Contributing

This is a demo/portfolio project. Feel free to fork and customize.

## 📝 License

Open-source under MIT License.

## 👨‍💻 Author

Built with ❤️ using Laravel 12, Livewire, and Tailwind CSS.

---

## 🆘 Troubleshooting

### Database Connection Error

```powershell
# Check PostgreSQL is running
# Verify credentials in .env
php artisan config:clear
```

### Seeder Fails

```powershell
# Clear cache and retry
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed
```

### Google OAuth Not Working

- Check redirect URI matches exactly in Google Console
- Clear browser cookies
- Verify .env credentials

### AviationStack API Empty Results

- Check API key is valid
- Free tier limited to 100 requests/month
- Verify IATA codes are correct (e.g., CGK, DPS)

### Assets Not Loading

```powershell
npm run build
php artisan storage:link
```

## 📞 Support

For issues or questions, please check:

- Laravel Documentation: https://laravel.com/docs/12.x
- Livewire Documentation: https://livewire.laravel.com/docs
- AviationStack API Docs: https://aviationstack.com/documentation
