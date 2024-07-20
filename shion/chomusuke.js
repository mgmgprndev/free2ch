var style = document.createElement("style");
style.innerText = `.xp-button {
    background-color: #c0c0c0; /* Light gray background */
    border: 2px solid #000080; /* Dark blue border */
    border-radius: 4px; /* Rounded corners */
    color: #000080; /* Dark blue text */
    font-family: "Tahoma", sans-serif; /* Windows XP font */
    font-size: 10px; /* Text size */
    padding: 4px 8px; /* Padding around text */
    text-align: center; /* Center text */
    text-decoration: none; /* Remove underline */
    display: inline-block; /* Align with other elements */
    cursor: pointer; /* Pointer cursor on hover */
    box-shadow: inset 2px 2px 4px #ffffff, inset -2px -2px 4px #8b8b8b; /* Inner shadow effect */
    transition: background-color 0.2s, box-shadow 0.2s; /* Smooth transition */
}

.xp-button:hover {
    background-color: #e0e0e0; /* Lighter gray background on hover */
    box-shadow: inset 1px 1px 3px #ffffff, inset -1px -1px 3px #7e7e7e; /* Lighter shadow effect on hover */
}
`;
document.head.appendChild(style);




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
    window.open('/open.php?to=' + btoa(encodeURIComponent(url)) ,'_blank');
}

function renderImage(url, el){
    urlEncoded = btoa(encodeURIComponent(url));

    var btn = document.createElement("button");
    btn.classList.add("xp-button");
    btn.innerHTML = "画像を読み込む";
    btn.setAttribute("url", urlEncoded);

    btn.onclick = function() {
        var img = document.createElement("img");
        img.src = 'https://img.free2ch.net?img=' + this.getAttribute("url");
        img.style.maxWidth="500px";
        img.style.maxHeight="500px";
        el.appendChild(img);
        this.remove();
    };

    el.appendChild(btn);
}