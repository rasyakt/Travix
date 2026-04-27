<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MonitoringService
{
    /**
     * Track booking metrics
     */
    public function trackBooking(string $type, array $data): void
    {
        $key = "metrics:bookings:{$type}:" . now()->format('Y-m-d-H');
        
        Cache::increment($key);
        Cache::put($key . ':last', now(), 3600);

        Log::channel('metrics')->info("Booking {$type}", $data);
    }

    /**
     * Track API performance
     */
    public function trackApiCall(string $endpoint, float $duration, bool $success): void
    {
        $key = "metrics:api:{$endpoint}:" . now()->format('Y-m-d-H');
        
        Cache::increment($key . ':total');
        
        if ($success) {
            Cache::increment($key . ':success');
        } else {
            Cache::increment($key . ':failed');
        }

        // Track response time
        $times = Cache::get($key . ':times', []);
        $times[] = $duration;
        Cache::put($key . ':times', array_slice($times, -100), 3600); // Keep last 100

        // Alert if too slow
        if ($duration > 5000) { // 5 seconds
            $this->sendAlert('slow_api', [
                'endpoint' => $endpoint,
                'duration' => $duration,
            ]);
        }
    }

    /**
     * Track payment metrics
     */
    public function trackPayment(string $status, float $amount): void
    {
        $key = "metrics:payments:{$status}:" . now()->format('Y-m-d');
        
        Cache::increment($key . ':count');
        
        $totalKey = $key . ':total_amount';
        $currentTotal = Cache::get($totalKey, 0);
        Cache::put($totalKey, $currentTotal + $amount, 86400);

        Log::channel('metrics')->info("Payment {$status}", [
            'amount' => $amount,
            'date' => now()->toDateString(),
        ]);
    }

    /**
     * Check system health
     */
    public function checkHealth(): array
    {
        $health = [
            'status' => 'healthy',
            'checks' => [],
            'timestamp' => now()->toDateTimeString(),
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = 'ok';
        } catch (\Exception $e) {
            $health['checks']['database'] = 'failed';
            $health['status'] = 'unhealthy';
            $this->sendAlert('database_down', ['error' => $e->getMessage()]);
        }

        // Cache check
        try {
            Cache::put('health_check', true, 10);
            $health['checks']['cache'] = Cache::get('health_check') ? 'ok' : 'failed';
        } catch (\Exception $e) {
            $health['checks']['cache'] = 'failed';
            $health['status'] = 'degraded';
        }

        // Queue check
        try {
            $queueSize = Cache::get('queue:size', 0);
            $health['checks']['queue'] = $queueSize < 1000 ? 'ok' : 'warning';
            
            if ($queueSize > 5000) {
                $this->sendAlert('queue_overload', ['size' => $queueSize]);
            }
        } catch (\Exception $e) {
            $health['checks']['queue'] = 'unknown';
        }

        // Disk space check
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        $diskUsagePercent = (($diskTotal - $diskFree) / $diskTotal) * 100;
        
        $health['checks']['disk_usage'] = round($diskUsagePercent, 2) . '%';
        
        if ($diskUsagePercent > 90) {
            $this->sendAlert('disk_space_low', ['usage' => $diskUsagePercent]);
        }

        return $health;
    }

    /**
     * Get booking statistics
     */
    public function getBookingStats(string $period = 'today'): array
    {
        $date = match($period) {
            'today' => now()->format('Y-m-d'),
            'yesterday' => now()->subDay()->format('Y-m-d'),
            default => now()->format('Y-m-d'),
        };

        $stats = [];
        
        foreach (['created', 'confirmed', 'cancelled', 'failed'] as $type) {
            $key = "metrics:bookings:{$type}:{$date}";
            $stats[$type] = 0;
            
            // Sum all hours
            for ($hour = 0; $hour < 24; $hour++) {
                $hourKey = "{$key}-" . str_pad($hour, 2, '0', STR_PAD_LEFT);
                $stats[$type] += Cache::get($hourKey, 0);
            }
        }

        $stats['conversion_rate'] = $stats['created'] > 0 
            ? round(($stats['confirmed'] / $stats['created']) * 100, 2) 
            : 0;

        return $stats;
    }

    /**
     * Send alert (implement your notification channel)
     */
    protected function sendAlert(string $type, array $data): void
    {
        Log::channel('alerts')->critical("ALERT: {$type}", $data);

        // TODO: Implement Slack/Email/SMS notification
        // Notification::route('slack', config('services.slack.webhook'))
        //     ->notify(new SystemAlert($type, $data));
    }
}
