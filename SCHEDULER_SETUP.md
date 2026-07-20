# Laravel Scheduler Setup - Auto Risk Score Calculation

## Overview
Risk scores are now calculated automatically every 6 hours using Laravel's Task Scheduler.

## Features
- ✅ Auto-calculate risk scores for all countries every 6 hours
- ✅ Background job with retry mechanism (3 attempts)
- ✅ Logging for monitoring and debugging
- ✅ Manual trigger command for testing
- ✅ "Last Updated" timestamp in UI

---

## Setup Instructions

### Windows (Development)

#### Option 1: Using Task Scheduler (Recommended for Production-like Testing)

1. Open **Task Scheduler** (search "Task Scheduler" in Start Menu)

2. Click **"Create Basic Task"**

3. **Name**: `Laravel Scheduler - Supply Chain Risk`
   **Description**: `Runs Laravel scheduler every minute to check for scheduled jobs`

4. **Trigger**: Select **"Daily"**, then check **"Repeat task every: 1 minute"**

5. **Action**: Select **"Start a program"**
   - **Program/script**: `php`
   - **Add arguments**: `artisan schedule:run`
   - **Start in**: `C:\supply-chain-risk` (your project path)

6. Click **Finish**

7. Edit the task, go to **Settings** tab:
   - ✅ Check: "Run task as soon as possible after a scheduled start is missed"
   - ✅ Check: "If the task fails, restart every: 1 minute"

#### Option 2: Using PowerShell Loop (Quick Testing)

Run this in PowerShell (keep window open):

```powershell
cd C:\supply-chain-risk
while ($true) {
    php artisan schedule:run
    Start-Sleep -Seconds 60
}
```

---

### Linux/Production

Add this to your crontab (`crontab -e`):

```bash
* * * * * cd /path/to/supply-chain-risk && php artisan schedule:run >> /dev/null 2>&1
```

---

## Manual Commands

### Calculate All Risk Scores Now (Sync Mode)
```bash
php artisan risk:calculate-all --sync
```

### Calculate All Risk Scores (Queue Mode)
```bash
php artisan risk:calculate-all
```

### View Schedule
```bash
php artisan schedule:list
```

### Run Scheduler Once (Testing)
```bash
php artisan schedule:run
```

---

## Queue Configuration

The job uses Laravel's queue system. Make sure your `.env` has:

```env
QUEUE_CONNECTION=database
```

Or use `sync` for immediate execution (simpler for development):

```env
QUEUE_CONNECTION=sync
```

If using `database` queue, run the queue worker:

```bash
php artisan queue:work
```

---

## Monitoring

### Check Logs
Logs are written to `storage/logs/laravel.log`. Look for:

- `🚀 Starting automatic risk score calculation...`
- `✅ Risk score calculation completed!`
- `❌ Risk score calculation job failed`

### View in Laravel Log Viewer (if installed)
```
http://localhost:8000/log-viewer
```

---

## Schedule Details

| Job | Frequency | Description |
|-----|-----------|-------------|
| `CalculateRiskScoresJob` | Every 6 hours | Calculates risk scores for all countries with ports |

**Schedule Times** (based on when scheduler starts):
- If scheduler starts at 00:00: runs at 00:00, 06:00, 12:00, 18:00
- If scheduler starts at 14:00: runs at 14:00, 20:00, 02:00, 08:00

---

## Troubleshooting

### Scheduler Not Running
1. Check if scheduler is actually running:
   ```bash
   php artisan schedule:work
   ```

2. Verify schedule is registered:
   ```bash
   php artisan schedule:list
   ```

3. Check file permissions (Linux):
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### Job Failing
1. Check logs: `storage/logs/laravel.log`

2. Run manually to see error:
   ```bash
   php artisan risk:calculate-all --sync
   ```

3. Check API keys in `.env`:
   - `GNEWS_API_KEY`
   - `EXCHANGERATE_API_KEY`

### Queue Not Processing
If using database queue:
```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Start queue worker
php artisan queue:work --tries=3
```

---

## Performance Notes

- **Duration**: ~30-60 minutes for all countries (depends on API response times)
- **API Calls**: ~600-800 external API calls per run
- **Rate Limits**: 0.5 second delay between countries to avoid rate limits
- **Memory**: ~50-100MB peak usage
- **Timeout**: 1 hour max per job

---

## Production Recommendations

1. **Use Queue Workers**: 
   ```bash
   php artisan queue:work --daemon --tries=3
   ```

2. **Monitor with Supervisor** (Linux):
   ```ini
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/path/to/worker.log
   ```

3. **Enable Failed Job Notifications**:
   - Configure email/Slack notifications in `config/logging.php`

4. **Database Optimization**:
   - Add index on `risk_scores.updated_at`
   - Regular `php artisan optimize:clear`

---

## API Rate Limits

Be aware of these API limits:

| API | Free Tier Limit | Used Per Run |
|-----|----------------|--------------|
| Open-Meteo | Unlimited | ~600 calls |
| GNews.io | 100/day | ~600 calls |
| ExchangeRate API | 1500/month | ~600 calls |
| World Bank | Unlimited | ~2400 calls |

**Solution**: Run job during off-peak hours (e.g., 2 AM, 8 AM, 2 PM, 8 PM)

---

## Support

For issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Scheduler output: `php artisan schedule:run -v`
3. Job status: `php artisan queue:failed`
