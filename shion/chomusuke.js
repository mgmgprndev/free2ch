var link = document.createElement("link");
link.setAttribute("rel", "stylesheet");
link.setAttribute("href", "/megumin.css");
document.head.appendChild(link);




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

            p.innerHTML = p.innerHTML.replace(/\!img\((https?:\/\/[a-z0-9.]+\.[a-z]{2,63}(\/[^\s]*)?)\)/gi, (match, url) => {
                renderImage(url, context, 1);
                return ``;
            });

            p.innerHTML = p.innerHTML.replace(/img\((https?:\/\/[a-z0-9.]+\.[a-z]{2,63}(\/[^\s]*)?)\)/gi, (match, url) => {
                renderImage(url, context, 0);
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
    window.open('/open.php?to=' + btoa(encodeURIComponent(url)) ,'_blank');
}

function renderImage(url, el, i){
    urlEncoded = btoa(encodeURIComponent(url));

    var btn = document.createElement("button");
    btn.classList.add("xp-button");
    btn.innerHTML = "画像を読み込む";
    btn.setAttribute("url", urlEncoded);
    btn.setAttribute("nsfw", i);
    if(i==1){
        btn.innerHTML += " (NSFW)";
    }

    btn.onclick = function() {
        var img_c = document.createElement("div");
        img_c.classList.add("image-container");
        
        var img = document.createElement("img");
        img.src = 'https://img.free2ch.net?img=' + this.getAttribute("url");
        img.style.maxWidth="500px";
        img.style.maxHeight="500px";
        img.style.width="auto";
        img.style.height="auto";

        img_c.appendChild(img);

        if(this.getAttribute('nsfw') == 1){
            var warning = document.createElement("div");
            warning.classList.add("warning");

            var warning_text = document.createElement("p");
            warning_text.classList.add("warning-text");
            warning_text.innerHTML = "Hover To Display";

            warning.appendChild(warning_text);
            img_c.appendChild(warning);
        }

        el.appendChild(img_c);
        this.remove();
    };

    el.appendChild(btn);
}