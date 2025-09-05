# PowerShell script to update remaining services with URL parameters
$serviceFile = "service.html"

# Define service data: ServiceName, Price, Duration
$services = @(
    @("Honey Soak Pedicure", "KES 3,500", "1 hour 15 min"),
    @("Sculpting Ombre", "KES 3,500", "1 hour 30 min"),
    @("Acrylics Ombre", "KES 4,000", "1 hour 30 min"),
    @("Wig Lines", "KES 3,500", "2 hours"),
    @("Kids Lines", "KES 1,500", "1 hour"),
    @("Dreadlocks Retwist", "KES 2,000", "1 hour 30 min"),
    @("Weaving", "KES 2,500", "2 hours"),
    @("Deep Conditioning", "KES 1,500", "1 hour"),
    @("Twist Outs", "KES 1,500", "1 hour 30 min"),
    @("Trac And Saw", "KES 3,500", "2 hours"),
    @("Cornrows", "KES 1,500", "2 hours"),
    @("Finger Coils", "KES 2,000", "2 hours")
)

$content = Get-Content $serviceFile -Raw

foreach ($service in $services) {
    $serviceName = $service[0]
    $price = $service[1]
    $duration = $service[2]
    
    # URL encode the parameters
    $encodedService = [System.Web.HttpUtility]::UrlEncode($serviceName)
    $encodedPrice = [System.Web.HttpUtility]::UrlEncode($price)
    $encodedDuration = [System.Web.HttpUtility]::UrlEncode($duration)
    
    # Find and replace the Book Now link for this service
    $oldPattern = "(<h4[^>]*>$serviceName</h4>[\s\S]*?)<a href=`"appointment\.html`" class=`"btn btn-primary"
    $newUrl = "appointment.html?service=$encodedService&price=$encodedPrice&duration=$encodedDuration"
    $newPattern = "`$1<a href=`"$newUrl`" class=`"btn btn-primary"
    
    $content = $content -replace $oldPattern, $newPattern
}

$content | Set-Content $serviceFile -Encoding UTF8
Write-Host "Updated all remaining services with URL parameters!"
