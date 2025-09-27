<?php
//require_once 'meekrodb.2.3.class.php'; // adjust path as needed

class Host {
    /* ---- PUBLIC FIELDS ---- */
    public $Idx = null;          
    public $IPv4Address = null;    
    public $IPv6Address = null;    
    public $MACAddress = null;     
    public $Active = null;         
    public $HostName = null;       
    public $InterfaceType = null;  
    public $MACAddressList = null; 
    public $FriendlyName = null;   
    public $WANAccess = null;      
    public $VPN = null;            
    public $Comment = null;        
    public $RequireOnline = null;  
    public $TrackChanges = null;
    public $OwnName = null;   

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /* ----------------- GETTERS / SETTERS (optional, chainable) ----------------- */

    public function getIndex() { return $this->Idx; }
    public function setIndex($v) { $this->Idx = (int)$v; return $this; }

    public function getIPv4Address() { return $this->IPv4Address; }
    public function setIPv4Address($v) { $this->IPv4Address = $v; return $this; }

    public function getIPv6Address() { return $this->IPv6Address; }
    public function setIPv6Address($v) { $this->IPv6Address = $v; return $this; }

    public function getMACAddress() { return $this->MACAddress; }
    public function setMACAddress($v) { $this->MACAddress = $v; return $this; }

    public function getActive() { return $this->Active; }
    public function setActive($v) { $this->Active = (int)$v; return $this; }

    public function getHostName() { return $this->HostName; }
    public function setHostName($v) { $this->HostName = $v; return $this; }

    public function getInterfaceType() { return $this->InterfaceType; }
    public function setInterfaceType($v) { $this->InterfaceType = $v; return $this; }

    public function getMACAddressList() { return $this->MACAddressList; }
    public function setMACAddressList($v) { $this->MACAddressList = $v; return $this; }

    public function getFriendlyName() { return $this->FriendlyName; }
    public function setFriendlyName($v) { $this->FriendlyName = $v; return $this; }

    public function getWANAccess() { return $this->WANAccess; }
    public function setWANAccess($v) { $this->WANAccess = (int)$v; return $this; }

    public function getVPN() { return $this->VPN; }
    public function setVPN($v) { $this->VPN = (int)$v; return $this; }

    public function getComment() { return $this->Comment; }
    public function setComment($v) { $this->Comment = $v; return $this; }

    public function getRequireOnline() { return $this->RequireOnline; }
    public function setRequireOnline($v) { $this->RequireOnline = (int)$v; return $this; }

    public function getTrackChanges() { return $this->TrackChanges; }
    public function setTrackChanges($v) { $this->TrackChanges = (int)$v; return $this; }

    public function getOwnName() { return $this->OwnName; }
    public function setOwnName($v) { $this->OwnName = $v; return $this; }    

    /* ----------------- DB Methods ----------------- */

    public function save($basicscan = true) {
        $data = array();
        $data['Idx'] = $this->Idx;
        if($this->IPv4Address) $data['IPv4Address'] = $this->IPv4Address;
        if ($this->IPv6Address !== null)    $data['IPv6Address'] = $this->IPv6Address;
        if ($this->MACAddress !== null)     $data['MACAddress'] = $this->MACAddress;
        if ($this->Active !== null)         $data['Active'] = $this->Active;
        if ($this->HostName !== null)       $data['HostName'] = $this->HostName;
        if ($this->InterfaceType !== null)  $data['InterfaceType'] = $this->InterfaceType;
        if ($this->MACAddressList !== null) $data['MACAddressList'] = $this->MACAddressList;
        if ($this->FriendlyName !== null)   $data['FriendlyName'] = $this->FriendlyName;
        if ($this->WANAccess !== null)      $data['WANAccess'] = $this->WANAccess;
        if ($this->VPN !== null)            $data['VPN'] = $this->VPN;
        if ($this->OwnName !== null)        $data['OwnName'] = $this->OwnName;

        if(!$basicscan){
            if ($this->VPN !== null)     $data['Comment']        = $this->Comment;
            if ($this->RequireOnline !== null)     $data['RequireOnline']  = $this->RequireOnline;
            if ($this->TrackChanges !== null)     $data['TrackChanges']   = $this->TrackChanges;

        }

        DB::insertUpdate('hosts', $data);
    }

    /**
     * Load a single host by a field name and value.
     */
    public static function load($field, $value) {
        $allowedFields = ['Idx','IPv4Address','IPv6Address','MACAddress','HostName','FriendlyName','OwnName'];
        if (!in_array($field, $allowedFields)) {
            throw new InvalidArgumentException("Invalid field name $field");
        }

        $row = DB::queryFirstRow("SELECT * FROM hosts WHERE $field=%s", $value);
        if (!$row) return null;

        return new self($row);
    }

    /**
     * Load all hosts (optionally filtered by a field and value).
     * 
     * @param string|null $field
     * @param mixed|null $value
     * @return Host[]
     */
    public static function loadAll($field = null, $value = null) {
        if ($field === null) {
            $rows = DB::query("SELECT * FROM  hosts");
        } else {
            $allowedFields = ['Idx','IPv4Address','IPv6Address','MACAddress','HostName','FriendlyName','OwnName'];
            if (!in_array($field, $allowedFields)) {
                throw new InvalidArgumentException("Invalid field name $field");
            }
            $rows = DB::query("SELECT * FROM hosts WHERE $field=%s", $value);
        }

        $hosts = [];
        foreach ($rows as $row) {
            $hosts[] = new self($row);
        }
        return $hosts;
    }




    /* ----------------- OUTPUT ----------------- */

    public function outputJson($pretty = false) {
        $options = $pretty ? JSON_PRETTY_PRINT : 0;
        return json_encode(get_object_vars($this), $options);
    }

    public function outputHost(){
        return get_object_vars($this);
    }
}
