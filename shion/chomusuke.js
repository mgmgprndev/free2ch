const uuid_data = {};

function addIDConvert(key, value) {
    uuid_data[key] = value;
}

document.querySelectorAll("comment").forEach((comment) => {
    addIDConvert(comment.getAttribute("data-id"), comment.id);
    comment.querySelectorAll("context").forEach((context) => {
        context.querySelectorAll("p").forEach((p) => {
            p.innerHTML = p.innerHTML.replace(/&gt;&gt;\d+/g, (match) => {
                return `<span style='color:blue;cursor:pointer;' onclick='jump(this.innerHTML);'>${match}</span>`;
            });

            p.innerHTML = p.innerHTML.replace(/img\((https?:\/\/[a-z0-9.]+\.[a-z]{2,63}(\/[^\s]*)?)\)/gi, (match, url) => {
                renderImage(url, context);
                return ``;
            });

            p.innerHTML = p.innerHTML.replace(/https?:\/\/[a-z0-9.]+\.[a-z]{2,63}(\/[^\s]*)?/gi, (match) => {
                return `<span style='color:blue;cursor:pointer;' onclick='openURL("${match}")'>${match}</span>`;
            });
        });
    });
});

function jump(i){
    i = i.replaceAll("&gt;&gt;","").replaceAll(" ","");
    target = uuid_data[i];
    if(target){
        document.getElementById(target).scrollIntoView({ behavior: 'smooth' });
    }
}

function openURL(url){
    window.open('/open.php?to=' + btoa(url) ,'_blank');
}

function renderImage(url, el){
    var img = document.createElement("img");
    img.src = 'https://img.free2ch.net?img=' + btoa(url);
    img.style.maxWidth="500px";
    img.style.maxHeight="500px";
    el.appendChild(img);
}