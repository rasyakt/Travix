# Deep Testing Report - Flight Booking System
**Tanggal:** 27 April 2026  
**Status:** Critical Issues Found

---

## 🔴 CRITICAL BUGS

### 1. **Inventory Leak pada Booking Expiry**
**Lokasi:** `app/Models/Booking.php` - `expirePendingReservation()`  
**Severity:** CRITICAL  
**Deskripsi:**
- Ketika booking expired, `SeatAssignment` dihapus tetapi `Flight.available_seats` TIDAK dikembalikan
- Ini menyebabkan inventory leak - kursi hilang dari sistem
- Setelah banyak booking expired, pesawat akan terlihat penuh padahal tidak

**Impact:**
- Revenue loss (kursi tidak bisa dijual lagi)
- Data inconsistency antara SeatAssignment dan Flight.available_seats

**Proof:**
```php
// Line 267-270 di Booking.php
$this->seatAssignments()->delete(); // ❌ Seats deleted
// ❌ MISSING: Flight.available_seats tidak dikembalikan!
```

---

### 2. **Race Condition pada Payment Processing**
**Lokasi:** `app/Http/Controllers/BookingController.php` - `processPayment()`  
**Severity:** CRITICAL  
**Deskripsi:**
- Inventory reservation terjadi SETELAH payment dibuat
- Window antara payment creation dan inventory reservation bisa menyebabkan overbooking
- Tidak ada validasi ulang available_seats sebelum reserve

**Impact:**
- Overbooking possible
- Dua user bisa book kursi yang sama

**Proof:**
```php
// Sequence yang berbahaya:
1. User A: Check available_seats = 1 ✓
2. User B: Check available_seats = 1 ✓
3. User A: Create payment, reserve seat (available_seats = 0)
4. User B: Create payment, reserve seat (available_seats = -1) ❌
```

---

### 3. **Missing Validation: Passenger Count Mismatch**
**Lokasi:** `app/Http/Controllers/BookingController.php` - `store()`  
**Severity:** HIGH  
**Deskripsi:**
- Tidak ada validasi bahwa jumlah `BookingPassenger` sama dengan `BookingFlight.passenger_count`
- User bisa submit form dengan data passenger yang tidak lengkap

**Impact:**
- Data inconsistency
- Seat selection akan error (jumlah kursi tidak match)

---

### 4. **API Booking: No Seat Assignment Validation**
**Lokasi:** `app/Http/Controllers/BookingController.php` - `processPayment()`  
**Severity:** MEDIUM  
**Deskripsi:**
- Untuk database flights, ada validasi seat assignment sebelum payment
- Untuk API flights, validasi ini di-skip tetapi tidak ada pengecekan `$isApiBooking`
- Bisa menyebabkan error jika logic berubah

**Current Code:**
```php
// Line 234-237
if ($booking->passengers->contains(fn($passenger) => !$passenger->seatAssignment)) {
    return redirect()->route('booking.seats', $booking->id)
        ->with('error', 'Pilih kursi untuk semua penumpang sebelum melanjutkan pembayaran.');
}
```
❌ Tidak ada check `if (!$isApiBooking)` sebelum validasi ini

---

### 5. **Seat Extra Fee Not Calculated**
**Lokasi:** `app/Services/SeatAssignmentService.php`  
**Severity:** MEDIUM  
**Deskripsi:**
- `SeatMap.extra_price` untuk premium seats (exit row, extra legroom) tidak dihitung
- User bisa pilih premium seat tanpa bayar extra

**Impact:**
- Revenue loss
- Unfair pricing

**Proof:**
```php
// SeatMap has extra_price field
// But SeatAssignmentService.assignSeats() doesn't calculate it
// Only base price from FlightSeatPrice is used
```

---

### 6. **Baggage Fee Not Integrated**
**Lokasi:** `app/Models/Booking.php` - `calculateTotalAmount()`  
**Severity:** MEDIUM  
**Deskripsi:**
- `Booking.baggage_fee` field exists
- `calculateTotalAmount()` tries to sum baggage fees
- Tetapi tidak ada UI/flow untuk add baggage

**Impact:**
- Dead code
- Incomplete feature

---

### 7. **Guest Booking Session Loss**
**Lokasi:** Session-based `guest_booking_ids`  
**Severity:** HIGH  
**Deskripsi:**
- Guest booking IDs disimpan di session (not persistent)
- Jika session expired/cleared, guest kehilangan akses ke booking mereka
- Tidak ada recovery mechanism

**Impact:**
- Poor UX
- Guest tidak bisa akses booking setelah session expired

---

### 8. **Duplicate Passenger Names Allowed**
**Lokasi:** `app/Http/Requests/StoreBookingRequest.php`  
**Severity:** LOW  
**Deskripsi:**
- Tidak ada validasi untuk mencegah duplicate passenger names dalam satu booking
- User bisa input nama yang sama 2x

**Impact:**
- Data quality issue
- Possible fraud

---

### 9. **Price Revalidation Not Enforced**
**Lokasi:** `app/Livewire/ApiFlightBookingForm.php`  
**Severity:** MEDIUM  
**Deskripsi:**
- Price revalidation untuk API flights adalah optional
- User bisa proceed dengan harga lama meskipun harga sudah naik
- Airline partner bisa reject booking

**Impact:**
- Payment failure
- Poor UX

---

### 10. **Refund Doesn't Restore FlightSeatPrice.available_seats**
**Lokasi:** `app/Http/Controllers/BookingController.php` - `refund()`  
**Severity:** MEDIUM  
**Deskripsi:**
- Refund mengembalikan `Flight.available_seats`
- Tetapi tidak mengembalikan `FlightSeatPrice.available_seats` per travel class
- Inconsistency antara total seats dan per-class seats

---

## 🟡 LOGIC ANOMALIES

### 11. **Check-in Window Calculation**
**Lokasi:** `app/Models/Booking.php` - `getCheckInBlockedReasonAttribute()`  
**Deskripsi:**
- Check-in window: 24 jam - 3 jam sebelum departure
- Menggunakan `diffInHours()` yang bisa negative jika flight sudah lewat
- Tidak ada explicit check untuk past flights

**Recommendation:** Add explicit check for past flights

---

### 12. **Booking Expiry Check Scattered**
**Deskripsi:**
- `expirePendingReservation()` dipanggil di multiple places:
  - `BookingController.show()`
  - `BookingController.payment()`
  - `BookingController.selectSeats()`
  - `BookingController.checkIn()`
- Tidak ada centralized expiry checker (middleware/job)

**Recommendation:** Create scheduled job untuk auto-expire bookings

---

### 13. **Payment Status Transition Not Validated**
**Lokasi:** `app/Http/Controllers/PaymentWebhookController.php`  
**Deskripsi:**
- `canTransitionStatus()` method exists tetapi logic-nya tidak terlihat
- Possible invalid state transitions (e.g., success → pending)

---

### 14. **API Flight Session Expiry**
**Lokasi:** `app/Livewire/ApiFlightBookingForm.php`  
**Deskripsi:**
- API flight data di session expires setelah 30 menit
- User bisa kehilangan data di tengah booking process
- No warning/notification

---

### 15. **Seat Assignment Travel Class Lock**
**Lokasi:** `app/Livewire/SeatSelection.php`  
**Deskripsi:**
- Travel class di-lock setelah booking created
- User tidak bisa upgrade/downgrade class
- Tetapi tidak ada UI indication bahwa class locked

---

## 🟢 MINOR ISSUES

### 16. **Hardcoded Values**
- Tax rate: 10% (hardcoded di `calculateTotalAmount()`)
- Refund percentage: 90% (hardcoded di view)
- Booking expiry: 60 minutes (hardcoded di `boot()`)
- Check-in window: 24h-3h (hardcoded di `getCheckInBlockedReasonAttribute()`)

**Recommendation:** Move to config file

---

### 17. **Missing Indexes**
- `booking_passengers.booking_id` - no index (frequent joins)
- `seat_assignments.flight_id` - no index (availability checks)
- `payments.status` - no index (status queries)

---

### 18. **No Soft Deletes**
- Bookings, Payments, SeatAssignments use hard delete
- No audit trail for cancelled bookings
- Cannot restore accidentally cancelled bookings

---

### 19. **Email Confirmation Job**
**Lokasi:** `app/Jobs/SendBookingConfirmationEmail.php`  
**Deskripsi:**
- Job exists tetapi tidak terlihat di-dispatch di mana pun
- Booking confirmation email tidak terkirim

---

### 20. **No Logging for Critical Operations**
- Payment processing
- Inventory reservation/release
- Refund processing
- Webhook handling (minimal logging)

---

## 📊 TESTING RECOMMENDATIONS

### Unit Tests Needed:
1. `Booking::expirePendingReservation()` - verify inventory restored
2. `SeatAssignmentService::assignSeats()` - race condition tests
3. `PaymentWebhookController::handleMidtrans()` - idempotency tests
4. `Booking::calculateTotalAmount()` - pricing accuracy

### Integration Tests Needed:
1. Complete booking flow (search → book → pay → seat → checkin)
2. Concurrent booking same seat
3. Payment expiry flow
4. Refund flow
5. Guest booking flow

### Load Tests Needed:
1. Concurrent seat selection (100+ users)
2. Webhook handling (burst traffic)
3. Search performance (API + DB)

---

## 🔧 PRIORITY FIXES

### P0 (Critical - Fix Immediately):
1. ✅ Fix inventory leak on booking expiry
2. ✅ Add race condition protection for payment
3. ✅ Validate passenger count matches

### P1 (High - Fix This Week):
4. ✅ Add API booking validation
5. ✅ Fix guest booking session persistence
6. ✅ Calculate seat extra fees

### P2 (Medium - Fix This Month):
7. Integrate baggage fee calculation
8. Enforce price revalidation for API flights
9. Fix FlightSeatPrice inventory on refund
10. Add soft deletes for audit trail

### P3 (Low - Backlog):
11. Move hardcoded values to config
12. Add missing database indexes
13. Implement email confirmation
14. Add comprehensive logging

---

## 📝 NOTES

- System is functional but has critical data integrity issues
- Main risk: Inventory management (seats can be lost/duplicated)
- Guest booking flow needs improvement
- API integration needs more validation
- Missing monitoring/alerting for critical operations

**Overall Assessment:** 🟡 YELLOW (Functional with Critical Issues)
