var link = document.createElement("link");
link.setAttribute("rel", "stylesheet");
link.setAttribute("href", "/megumin.css");
document.head.appendChild(link);


var isR18OK = false;

if(localStorage.getItem("r18ok") && localStorage.getItem("r18ok") == "yes"){
    isR18OK = true;
}




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

        if(!isR18OK){
            isR18OK = confirm("あなたは18歳以上ですか?\nこの画像は投稿者によってR18指定されています。");
            localStorage.setItem("r18ok", "yes");
        }

        if(!isR18OK){
            return;
        }

        url = "https://img.free2ch.net?img=" + this.getAttribute("url");

        var img_c = document.createElement("div");
        img_c.style.display = "none";
        img_c.classList.add("image-container");
        
        var img = document.createElement("div");
        img.classList.add("img");
        loadImageBlur(img_c, img, url, this.getAttribute("nsfw")==1);

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

async function loadImageBlur(pr, el, imagepath, i) {
    const response = await fetch(imagepath);
    const imageBlob = await response.blob();

    const imageUrl = URL.createObjectURL(imageBlob);

    if(i){
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const image = new Image();

        image.src = imageUrl;
        await new Promise(resolve => image.onload = resolve);

        canvas.width = image.width;
        canvas.height = image.height;

        ctx.filter = 'blur(64px)';
        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
    
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;
        for (let i = 0; i < data.length; i += 4) {
            const rand = Math.random() * 50 - 25;
            data[i] = data[i] + rand;
            data[i + 1] = data[i + 1] + rand;
            data[i + 2] = data[i + 2] + rand;
        }
        ctx.putImageData(imageData, 0, 0);

        ctx.filter = 'blur(64px)';
        ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height);

        const blurredBlob = await new Promise(resolve => canvas.toBlob(resolve));
        const blurredUrl = URL.createObjectURL(blurredBlob);

        pr.addEventListener('mouseenter', () => {
            el.style.backgroundImage = `url(${imageUrl})`;
        });
    
        pr.addEventListener('mouseleave', () => {
            el.style.backgroundImage = `url(${blurredUrl})`;
        });

        el.style.backgroundImage = `url(${blurredUrl})`;
    }else {
        el.style.backgroundImage = `url(${imageUrl})`;
    }


    pr.style.display = "";
}