window.onload = function (){
    fetch('https://free2ch.net/uname.php', {credentials: 'include'}).then((response) => response.text() ).then( (nick) => {
        res = decodeURIComponent(nick);
        nickName = "";
        if(res == ""){
            nickName = "名無し@free2ch";
            fetch('https://free2ch.net/uname.php?set=' + encodeURIComponent(nickName));
        }else {
            nickName = res;
        }
    
        document.getElementsByName("nickname").forEach(i => {
            i.value = nickName;
            i.oninput = function () {
                fetch('https://free2ch.net/uname.php?set=' + encodeURIComponent(this.value == "" ? '名無し@free2ch' : this.value ), {credentials: 'include'});
            }

            i.onchange = function (){
                if(this.value==""){
                    this.value = "名無し@free2ch";
                }
            }
        });
    });
}