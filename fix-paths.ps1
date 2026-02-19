# Script to replace hardcoded /TFG2DAW/ paths with PHP constants
# This makes the app work in both Docker and XAMPP

$frontendDir = Join-Path $PSScriptRoot "src\frontend"

Get-ChildItem -Path $frontendDir -Recurse -Include "*.php" | ForEach-Object {
    $content = Get-Content $_.FullName -Raw -Encoding UTF8
    $changed = $false

    # Replace /TFG2DAW/src/backend/ with PHP constant BASE_URL
    if ($content -match '/TFG2DAW/src/backend/') {
        $content = $content -replace '/TFG2DAW/src/backend/', '<?php echo BASE_URL; ?>/'
        $changed = $true
    }

    # Replace /TFG2DAW/src/frontend/ with PHP constant ASSETS_URL + /frontend/
    if ($content -match '/TFG2DAW/src/frontend/') {
        $content = $content -replace '/TFG2DAW/src/frontend/', '<?php echo ASSETS_URL; ?>/frontend/'
        $changed = $true
    }

    # Replace /TFG2DAW/src/img/ with PHP constant ASSETS_URL + /img/
    if ($content -match '/TFG2DAW/src/img/') {
        $content = $content -replace '/TFG2DAW/src/img/', '<?php echo ASSETS_URL; ?>/img/'
        $changed = $true
    }

    # Replace /TFG2DAW/src/public/ with PHP constant ASSETS_URL + /public/
    if ($content -match '/TFG2DAW/src/public/') {
        $content = $content -replace '/TFG2DAW/src/public/', '<?php echo ASSETS_URL; ?>/public/'
        $changed = $true
    }

    if ($changed) {
        Set-Content -Path $_.FullName -Value $content -NoNewline -Encoding UTF8
        Write-Host "Updated: $($_.FullName)"
    }
}

Write-Host "`nDone! All hardcoded paths replaced with PHP constants."
