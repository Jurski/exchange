<?php

echo "Currency converter..." . PHP_EOL;

try {
    $amountAndCurrency = trim(strtolower(readline("Enter the amount followed by the currency e.g '200 usd': ")));
    $splitString = explode(" ", $amountAndCurrency);

    if (count($splitString) !== 2 || !is_numeric($splitString[0])) {
        throw new Exception("Please enter a valid amount followed by currency, e.g '200 usd'");
    }

    $amountToConvert = (float)$splitString[0];
    $baseCurrency = $splitString[1];
    $currencyForConversion = trim(strtolower(readline("Enter the currency to convert to: ")));
    $currencyUrl = urlencode($baseCurrency);

    $url = "https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/{$currencyUrl}.json";
    $currencyJSON = file_get_contents($url);

    if ($currencyJSON === false) {
        throw new Exception("Network error or invalid input");
    }

    $currencyData = json_decode($currencyJSON);

    if ($currencyData === null) {
        throw new Exception("Error parsing currency data");
    }

    if (!isset($currencyData->$baseCurrency->$currencyForConversion)) {
        throw new Exception("Couldnt find conversion rate. Make sure you enter a valid currency to convert to");
    }

    $conversionRate = $currencyData->$baseCurrency->$currencyForConversion;
    $convertedAmount = $amountToConvert * $conversionRate;
    $convertedAmountFormatted = number_format($convertedAmount, 2);

    echo "$amountToConvert $baseCurrency is $convertedAmountFormatted $currencyForConversion" . PHP_EOL;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}