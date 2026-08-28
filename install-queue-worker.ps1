# ============================================================
#  WRD Queue Worker Installer (run ONCE on the Windows server)
#
#  Installs the Laravel queue worker so the daily 11:00 Wash
#  Report mail sends automatically - server boots, no login,
#  no IDE, no Task Scheduler time entry needed.
#
#  HOW TO RUN (as Administrator, in the project folder):
#    powershell -ExecutionPolicy Bypass -File install-queue-worker.ps1
#
#  TO REMOVE:
#    nssm remove WRD-QueueWorker confirm        (service mode)
#    Unregister-ScheduledTask WRD-QueueWorker   (task mode)
# ============================================================

$ErrorActionPreference = 'Stop'
$serviceName = 'WRD-QueueWorker'
$laravel     = $PSScriptRoot

# --- Must run as Administrator ---
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "[X] Please run this script as Administrator (right-click -> Run as administrator)" -ForegroundColor Red
    exit 1
}

# --- Locate php.exe ---
$php = (Get-Command php -ErrorAction SilentlyContinue).Source
if (-not $php) {
    foreach ($candidate in @("C:\php\php.exe", "C:\xampp\php\php.exe", "C:\laragon\bin\php\*\php.exe", "D:\php\php.exe", "C:\xampp\php\..\php\php.exe")) {
        $found = Get-Item $candidate -ErrorAction SilentlyContinue | Select-Object -First 1
        if ($found) { $php = $found.FullName; break }
    }
}
if (-not $php) {
    Write-Host "[X] php.exe not found. Install PHP or add it to PATH, then re-run." -ForegroundColor Red
    exit 1
}
Write-Host "[OK] PHP: $php"

# --- Sanity checks ---
if (-not (Test-Path "$laravel\artisan")) {
    Write-Host "[X] artisan not found in $laravel - run this script from the project root." -ForegroundColor Red
    exit 1
}

$envLine = Get-Content "$laravel\.env" -ErrorAction SilentlyContinue | Where-Object { $_ -match '^QUEUE_CONNECTION=' } | Select-Object -First 1
if ($envLine -ne 'QUEUE_CONNECTION=database') {
    Write-Host "[X] .env has '$envLine' - it must be QUEUE_CONNECTION=database. Fix .env and re-run." -ForegroundColor Red
    exit 1
}
Write-Host "[OK] QUEUE_CONNECTION=database"

# --- Locate nssm.exe (service mode) ---
$nssm = (Get-Command nssm -ErrorAction SilentlyContinue).Source
if (-not $nssm -and (Test-Path "$laravel\nssm.exe")) { $nssm = "$laravel\nssm.exe" }

if ($nssm) {
    # --- Preferred: real Windows service (auto-start on boot, auto-restart on crash) ---
    & $nssm stop $serviceName 2>$null | Out-Null
    & $nssm remove $serviceName confirm 2>$null | Out-Null

    & $nssm install $serviceName $php "artisan queue:work --timeout=900"
    & $nssm set $serviceName AppDirectory $laravel | Out-Null
    & $nssm set $serviceName AppStdout "$laravel\storage\logs\worker.log" | Out-Null
    & $nssm set $serviceName AppStderr "$laravel\storage\logs\worker-error.log" | Out-Null
    & $nssm set $serviceName AppRotateFiles 1 | Out-Null
    & $nssm set $serviceName AppRotateOnline 1 | Out-Null
    & $nssm set $serviceName Start SERVICE_AUTO_START | Out-Null
    & $nssm start $serviceName | Out-Null

    Write-Host "[OK] Installed as Windows service '$serviceName' (NSSM)" -ForegroundColor Green
    Write-Host "     - Starts automatically on boot"
    Write-Host "     - Restarts automatically if it crashes"
    Write-Host "     - php artisan queue:restart works (service restarts it with fresh code)"
}
else {
    # --- Fallback: SYSTEM startup task running the worker continuously ---
    $action    = New-ScheduledTaskAction -Execute $php -Argument "artisan queue:work --timeout=900" -WorkingDirectory $laravel
    $trigger   = New-ScheduledTaskTrigger -AtStartup
    $settings  = New-ScheduledTaskSettingsSet -ExecutionTimeLimit ([TimeSpan]::Zero) -RestartCount 999 -RestartInterval (New-TimeSpan -Minutes 1) -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries
    $principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -RunLevel Highest

    Unregister-ScheduledTask -TaskName $serviceName -Confirm:$false -ErrorAction SilentlyContinue
    Register-ScheduledTask -TaskName $serviceName -Action $action -Trigger $trigger -Settings $settings -Principal $principal -Force | Out-Null
    Start-ScheduledTask -TaskName $serviceName

    Write-Host "[OK] Installed as startup task '$serviceName' (no NSSM found - fallback mode)" -ForegroundColor Yellow
    Write-Host "     Tip: put nssm.exe in this folder and re-run for a true service."
}

# --- Verify: wait, then show status + next scheduled run ---
Start-Sleep -Seconds 8
Write-Host ""
Write-Host "--- Verification ---"
if ($nssm) {
    Write-Host ("Service state: " + (& $nssm status $serviceName))
} else {
    Write-Host ("Task state: " + (Get-ScheduledTask -TaskName $serviceName).State)
}

& $php artisan tinker --execute='echo "Next scheduled Wash Report run: " . date("Y-m-d H:i", DB::table("jobs")->value("available_at")) . PHP_EOL;'
Write-Host ""
Write-Host "Done. The server will now send the Wash Report mail automatically at the scheduled time." -ForegroundColor Green
