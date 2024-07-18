window.onload = function (){
    if(!localStorage.getItem('nickname')){
        localStorage.setItem('nickname','名無し@free2ch');
    }
    var nick = localStorage.getItem('nickname');
    nicknames = document.getElementsByName("nickname");
    nicknames.forEach(i => {
        i.value = nick;
        i.oninput = function () {
            localStorage.setItem('nickname',this.value == "" ? "名無し@free2ch" : this.value );
        }
    });
}