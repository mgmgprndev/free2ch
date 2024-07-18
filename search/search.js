function addResult(link, bn, tt, cu, cn, ct,i){
    a = document.createElement("a");
    a.target = "_blank";
    a.href = link;
    table = document.createElement("table");
    table.classList.add("w-full");
    tbody = document.createElement("tbody");

    function ntr(d1,d2){
        x = document.createElement("tr");
        th1 = document.createElement("th");
        th1.innerHTML = d1;
        th1.style.width="30%";
        th2 = document.createElement("th");
        th2.innerHTML = d2;
        x.appendChild(th1);
        x.appendChild(th2);
        return x;
    }

    tbody.appendChild(ntr("書き込み先の板",bn));
    tbody.appendChild(ntr("書き込み先のスレッド",tt));
    tbody.appendChild(ntr("書き込んだユーザーのID",cu + (i ? "<span  style='color:red;' class='text-xs'>?</span>" : "" ) ));
    tbody.appendChild(ntr("書き込んだユーザーの名前",cn));
    tbody.appendChild(ntr("書き込まれた内容",ct));


    table.appendChild(tbody);
    a.appendChild(table);

    document.getElementById("results").appendChild(a);
}


function getResults(i,page){
    fetch("get.php?id=" + i + "&page=" + page)
    .then((response) => response.json())
    .then((json) => {

        document.getElementById("first").href += 1;

        if(page - 1 > 0){
            document.getElementById("prev").href += page - 1;
        }else {
            document.getElementById("prev").removeAttribute("href");
        }

        if(page + 1 <= json.pages){
            document.getElementById("next").href += page + 1;
        }else {
            document.getElementById("next").removeAttribute("href");
        }

        document.getElementById("last").href += json.pages;

        document.getElementById("custom-pg").setAttribute("max", json.pages);
        document.getElementById("custom-pg").value = page;

        json.results.forEach(i => {
            addResult("go.php?u=" + i.threaduuid + "&c=" + i.commentuuid ,i.boardname, i.threadname, i.userid, i.nickname, i.context, i.isadmin==1);
        });
    });
}