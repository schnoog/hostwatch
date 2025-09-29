{literal}


function SendRequest(idx,req,val){
        const xhr = new XMLHttpRequest();

        const params = new URLSearchParams();
        params.append("do", 1);
        params.append("req", req);
        params.append("idx", idx);
        params.append("val", val);
        xhr.open("POST", "index.php?do=1");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(params.toString());
        
        xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {

            console.log("ANSWER:" + xhr.responseText);
            location.reload();            
        } else {
            console.log(`Error: ${xhr.status}`);
        }
        };
}






{/literal}