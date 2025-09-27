<?php

use blacksenator\fritzsoap\hosts;

function QueryFritzBox(){
    global $CONFIG;
        $fritz_user = $CONFIG['fritz']['user']; 
        $fritz_url = $CONFIG['fritz']['host'];
        $fritz_pwd = $CONFIG['fritz']['pass'];
        $getnew = true;
        if($getnew){
        $fritzbox = new hosts($fritz_url, $fritz_user, $fritz_pwd);
        $fritzbox->getClient();
            $data =  $fritzbox->getHostList();
        }else{
            $data = json_decode(file_get_contents('_out_getHostList.json'),true);
        }
        $data = json_decode(json_encode($data),true);

        $tmp = $data;
        $hostarray = [];
        if(isset($tmo['Item'])){
            $nh = new Host();
            $nh->setIPv4Address('192.168.178.1');
            $nh->setIndex(0);
            $nh->setActive(1);
            $nh->setHostName("FritzBox");
            $nh->setFriendlyName("FritzBox");
            $nh->setMACAddress("74:42:7F:03:55:72");
            $nh->setIPv6Address(macToIPv6LinkLocal("74:42:7F:03:55:72"));
            $nh->save();
            unset($nh);

        }else{
            return false;
        }


        foreach($tmp['Item'] as $rh){
                $nh = new Host();
                $nh->setIndex($rh['Index']);
                $nh->setIPv4Address($rh['IPAddress']);
                $nh->setVPN($rh['X_AVM-DE_VPN']);
                $nh->setWANAccess($rh['X_AVM-DE_WANAccess']);
                $nh->setActive($rh['Active']);
                $nh->setHostName($rh['HostName']);
                if(isset($rh['X_AVM-DE_FriendlyName']))    $nh->setFriendlyName($rh['X_AVM-DE_FriendlyName']);
                $fmac = GetFirstValid($rh['MACAddress']);
                if($fmac){
                    $nh->setMACAddress($fmac);
                    $nh->setIPv6Address( macToIPv6LinkLocal( $fmac));
                }
                if(isset($rh['InterfaceType'])){ 
                    $ift = GetResponseFieldListed($rh['InterfaceType']);
                    if($ift) $nh->setInterfaceType($ift);
                }

                if(isset($rh['X_AVM-DE_MACAddressList'])){    
                    $mal = GetResponseFieldListed($rh['X_AVM-DE_MACAddressList']);
                    if($mal) $nh->setMACAddressList($mal);
                }
                $hostarray[] = $nh;
                $nh->save();
                unset($nh);
        }
        return $hostarray;
}

