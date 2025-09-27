<?php


function dumpSave($data,$name){

    file_put_contents( __DIR__ . "/_out_" . $name . ".txt" ,print_r($data,true));
    file_put_contents(__DIR__ . "/_out_" . $name . ".json",json_encode($data));


}



function macToIPv6LinkLocal(string $mac): string {
    // Normalize MAC (remove separators)
    $mac = preg_replace('/[^0-9A-Fa-f]/', '', $mac);
    if (strlen($mac) !== 12) {
        throw new InvalidArgumentException("Invalid MAC address");
    }

    // Split into bytes
    $bytes = str_split($mac, 2);

    // Convert first byte and flip the U/L bit (bit 1 of first byte)
    $firstByte = hexdec($bytes[0]);
    $firstByte ^= 0x02; // flip bit 1
    $bytes[0] = sprintf('%02x', $firstByte);

    // Insert ff:fe in the middle
    array_splice($bytes, 3, 0, ['ff', 'fe']);

    // Build IPv6 interface identifier
    $parts = [];
    for ($i = 0; $i < 8; $i += 2) {
        $parts[] = $bytes[$i] . $bytes[$i + 1];
    }

    // Compress IPv6 groups automatically
    $ipv6 = 'fe80::' . implode(':', $parts);

    return $ipv6;
}


function GetFirstValid($raw){
    if(! isset($raw)) return false;
    if(is_array($raw)){
        if(isset($raw[0])) return $raw[0];
        return false;
    }
    if(strlen($raw)< 1) return false;
    return $raw;
}

function GetResponseFieldListed($raw, $glue = ","){
    if(! isset($raw)) return false;
    if(is_array($raw)){
        if(isset($raw[0])) return implode($glue, $raw);
        return false;
    }
    if(strlen($raw)< 1) return false;
    return $raw;
}