Nirvana.environment({
  data: {
    version: '1.0.0',
    baseurl: window.location.origin,
    background: [
      'https://wallpaperbat.com/img/8941578-anime-girl-pink-eyes-city-desktop.jpg',
      'https://embed.pixiv.net/artwork.php?illust_id=107033961',
      'https://images.wallpaperscraft.ru/image/single/devushka_anime_vzgliad_971054_1280x720.jpg',
    ],
    headers: {
      'Rune-file': null,
      'Rune-repo': null,
    }
  },
  configure: {
    development: true,
  }
});

function cleanURL(url) {
  return url.replace(/([^:])\/{2,}/g, '$1/');
}
function baseurl( url='' ) {
  return cleanURL(Nirvana.data('baseurl') + '/' + url);
}
function element(htmlString, find = "body") {
  const doc = new DOMParser().parseFromString(htmlString, "text/html");
  const found = doc.querySelector(find);

  if (!found) return null;

  // Buat DocumentFragment untuk menampung semua child nodes
  const fragment = document.createDocumentFragment();

  // Menambahkan semua child nodes dari elemen yang ditemukan ke fragment
  while (found.firstChild) {
    fragment.appendChild(found.firstChild);
  }

  // Mengembalikan DocumentFragment
  return fragment;
}
async function http(url, opt) {
  try {
    let response = await fetch(url, opt);

    // Memeriksa apakah respons berhasil
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    // Mendapatkan header 'Content-Type' untuk menentukan jenis konten
    const contentType = response.headers.get("Content-Type");

    // Mengambil data tergantung pada jenis konten
    if (contentType && contentType.includes("application/json")) {
      // Jika konten adalah JSON
      const jsonResponse = await response.json();
      return {
        type: "json",
        data: jsonResponse
      };
    } else if (contentType && contentType.includes("text/html")) {
      // Jika konten adalah HTML
      const textResponse = await response.text();
      return {
        type: "html",
        data: textResponse
      };
    } else if (contentType && contentType.includes("text/csv")) {
      // Jika konten adalah CSV
      const csvText = await response.text();
      const jsonData = csvText;
      return {
        type: "csv",
        data: jsonData
      };
    } else {
      throw new Error("Unsupported Content-Type: " + contentType);
    }
  } catch (error) {
    console.error("Fetch error:", error);
    return {
      error: error.message
    };
  }
}
function coloring(rawText) {
  const ansiToSpan = {
    '\u001b[01;34m': '<span style="color:#268bd2">',
    '\u001b[01;30m': '<span style="color:#666666">',
    '\u001b[01;31m': '<span style="color:#ff4b4b">',
    '\u001b[01;32m': '<span style="color:#00d700">',
    '\u001b[38;5;214m': '<span style="color:#ffaf00">',
    '\u001b[01;36m': '<span style="color:#00ffff">',
    '\u001b[0;37m': '</span>',
  };
  let coloredText = rawText;
  for (const [ansi, span] of Object.entries(ansiToSpan)) {
    // replace semua ANSI codes dengan tag span
    const regex = new RegExp(ansi.replace(/[\[\];]/g, '\\$&'), 'g');
    coloredText = coloredText.replace(regex, span);
  }
  return coloredText;
}





class NirvanaCSS {
  
  static restart() {
    this.picker();
    this.dialog();
  }
  
  static instance() {
    this.picker();
    this.dialog();
  }

  static picker() {
    // Picker component
    document.querySelectorAll("[picker]").forEach(function(picker) {
      const content = picker.querySelector("[picker-content]");
      picker.addEventListener("mouseenter", function() {
        const rect = content.getBoundingClientRect();
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        if (rect.bottom > viewportHeight) {
          content.style.top = "auto";
          content.style.bottom = "100%";
        } else {
          content.style.top = "100%";
          content.style.bottom = "auto";
        }
      });
    });
  }

  // Alert Component
  static alert(type = 'default', title, message) {
    return new Promise((resolve) => {
      // Durasi tampil alert (acak antara 1s - 11s, contoh aja)
      let time = Math.floor(Math.random() * 10000) + 1000;
  
      // Bikin elemen alert
      let element = document.createElement("div");
      element.setAttribute("data-aos", "slide-left");
      element.setAttribute("alert", type);
      element.innerHTML = `
        <h6>${title}</h6>
        <p>${message}</p>
      `;
  
      // Tambahkan ke container
      document.querySelector("[alert-container]").appendChild(element);
  
      // Auto hapus dan resolve setelah 3 detik
      const timing = setTimeout(() => {
        element.remove();
        clearTimeout(timing);
        resolve(); // Selesaikan promise
      }, 3000); // Ubah sesuai kebutuhan ya kak
    });
  }
  

  static dialog() {
     // opening
     document.querySelectorAll("[dialog-open]").forEach(button => {
      button.addEventListener("click", e => {
        let id = e.currentTarget.getAttribute("dialog-open");
        document.querySelector(`[dialog][id=${id}]`).setAttribute("show", 1);
      });
    });
    // closing
    document.querySelectorAll("[dialog-close]").forEach(button => {
      button.addEventListener("click", e => {
        let id = e.currentTarget.getAttribute("dialog-close");
        document.querySelector(`[dialog][id=${id}]`).removeAttribute("show");
      });
    });
  }
  static dialogOpen(id) {
    document.querySelector(`[dialog][id=${id}]`).setAttribute("show", 1);
  }
  static dialogClose(id) {
    document.querySelector(`[dialog][id=${id}]`).removeAttribute("show");
  }

}







Nirvana.component(
  class Panel extends Nirvana {
    async start( subPanel ) {
      this.service().then(() => {
        subPanel.forEach(panel => {
          panel.start();
        });
      });
      await this.listen(subPanel);
    }
    
    async listen( subApp ) {
      let interval = sessionStorage.getItem("setting--interval") || 4000;

      let intervalProccess = setInterval( async () => {
        fetch(baseurl('api/synchronize'), {
          method: 'GET',
          headers: { 'Rune-interval': interval }
        }).then( async prom=> {
          prom.text().then(async resp=> {
            if (resp === 'true') {              
              this.service().then(() => {
                subApp.forEach(app => {
                  app.start();
                });

                select("#synchronize").item(0).classList.add("play");
                setTimeout(() => {
                  select("#synchronize").item(0).classList.remove("play");
                }, 1000);
              });
            }
          });
        });
      }, interval);
    }

    async service() {
      await this.getInformation();
      await this.getLogs();
      return true;
    }
    
    async getInformation() {
      const resp = await http(baseurl('api/information'), {
        method: 'GET',
        headers: Nirvana.data('headers')
      });
      let data = resp.data.data;
      
      Nirvana.store('Altar').set('information', data);
      Nirvana.data('headers', {
        'Rune-file': data.FILE,
        'Rune-repo': data.REPO,
      });

      return true;
    }

    async getLogs() {
      const logsResponse = await fetch(baseurl('api/logs'));
      const logsJson = await logsResponse.json();
      let source = logsJson.data.source;
      Nirvana.store('Altar').set('logs', source);
      return true;
    }


    

  }
);

Nirvana.component(
  class LeftPanel extends Nirvana {
    async start() {
      // nest
      Nest.sync();
      if (sessionStorage.getItem('NEST@AnwarAchilles')) {
        select("[nest-card]").item(0).removeAttribute('misc');
        select("[nest]").item(0).setAttribute('misc', 'd-none');
      }else {
        select("[nest-card]").item(0).setAttribute('misc', 'd-none');
        select("[nest]").item(0).removeAttribute('misc');
      }
      
      // check
      if (sessionStorage.getItem('left-panel--active')) {
        await this.active(sessionStorage.getItem('left-panel--active'));
        let active = sessionStorage.getItem('left-panel--active');
        if (active === 'left-panel--rune') {
          await this.getRune();
        }
        if (active === 'left-panel--shard') {
          await this.getShard();
        }
      }else {
        await this.active('left-panel--information');
      }

      // information
      let info = Nirvana.store('Altar').get('information');
      if (info) {
        this.select("#file").item(0).innerText = info.FILE;
        this.select("#repo").item(0).innerText = info.REPO;
        this.select("#familiar").item(0).innerHTML = (info.FAMILIAR) ? '<small badge="primary">ACTIVE</small>' : info.FAMILIAR;
        this.select("#logs_size").item(0).innerText = info.LOGS_SIZE;
        this.select("#disk_usage").item(0).innerText = info.DISK_USAGE;
        this.select("#main_command").item(0).innerText = 'php ' + info.FILE;
        this.select("#main_artefact").item(0).innerText = info.FILE + '.rune';
        select("#bottom-panel--runeVersion").item(0).innerText = info.VERSION.replace('v','');
        
        // this.select("#arised").item(0).innerHTML = '';
        // Object.entries(info.ARISED).forEach((ari) => {
        //   let text = ari[0];
        //   text = text.replace('Rune\\', '');
        //   text = text.replace('\\Manifest', '');
        //   this.select("#arised").item(0).innerHTML += `
        //     <li size="pb-1">
        //       ${text}
        //       <ul>
        //         <li>ether</li>
        //         <li>essence</li>
        //         <li>entity</li>
        //       </ul>
        //     </li>`;
        // });

        delete info.CHANTER_REGISTERED[0];
        delete info.CHANTER_REGISTERED[1];
        delete info.CHANTER_REGISTERED[2];
        delete info.CHANTER_REGISTERED[3];
        this.select("#registered").item(0).innerHTML = '';
        info.CHANTER_REGISTERED.forEach((arg) => {
          let note = (info.CHANTER_NOTE[arg]) ? info.CHANTER_NOTE[arg] : '';
          let command = `php ${info.FILE} ${arg}`;
          this.select("#registered").item(0).innerHTML += `
            <li size="mb-2">
              <div flex="middle between">
                <p size="m-0">${arg}</p>
                <div>
                  <button onclick="Nirvana.run('LeftPanel').copyCommand('${command}')" button="default">
                    <img width="16px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAAiZJREFUaEPtmr1KXUEUhb9VCpLKykbII9iJhRbiI4h5gYBo0sQqEtQiKRQDSWHjC+graGNSBCzyCIJdAsHGFCl3HHKL+3N+xnHmcIh74Db3zqzZa681+2zOHfHEhp4YX5zw/664K5yqsJnNAsfAKjCVijO27g9wDmxI+pEDM5vCZnYGrOUIqgLjVNKLHNjRhM1sGngJPKvZeBOYyRFUBcavgXuq4O+AE0m/Y/aOImxmB8BWRqvGxPaQOcH6R5LetS1qJWxmb4H3bUA9+X1TUqgjtaORsJk9B657QiY2jBlJt3WT2wi/Aj7H7tSTeeuSQgGtHG2Ed+9X7Y2t/AJcjn23DCwVJhy7746kD6mEA9lAenjsSxpJgplVzcvNP3bfiXnDgbQp7ISB2Ey7wo/MQGyiO7F0KFrhU3JcShopljW1ozzhkiybsJ3wv+y4wtkfS27pjjLgZ9jP8KTVsrSWHTl4Yhu3tFvaLd38V0vKGenyPKfE50VrWKGUDLrCBTOQIohb2i0d8dayoGsbod3S3ml5p+Wd1ogHUopClwUsJT5/Dvtz2J/DzS+6/QwXzIAXLe+0vNPyTmu809oGDgvWnRLQbyR9rANu67RWgIsSURXEXJL0NYlwWGRm34H5ggHmhP4mabEJMOauZbh+eFXwpmwuwjfAgqSfjyI8UHlucHX4NRCuEfdphHuVnwZXiBvJhqBbFe4TsxyxOOEcWewzhivcZ3VyxPYXJY/ZTApENKUAAAAASUVORK5CYII="/>
                  </button>
                  <button onclick="Nirvana.run('LeftPanel').runCommand('${command}')" button="default">
                    <img width="16px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAAi5JREFUaEPtmr9KxTAYxc9xFcQ/i6CDgw/g7p/B5bq5iI8guIiD4ia66iz4Ck6KiyiIg4Kj4AOIDq4i6CqfNxjh0jYS7735mrbp3Cbnl3OafklDNOxiw3iRgOvueHI4OVyzEUiRrpmhOZzkcOeQiMgKgHUAixVx/hrAEclTl16nwyJyCGCrIqBZmXsk94u0FwKLyCqAk4rC/spukbzKMriAHwDMVBz4imTLF/gLwEDFgd9IjvkCS+5GMuoZXUS8NLsi7fVwTAlIwADaM3XO0OSwialvPBofaREZJvlexkD4mtS3SIvILoA1ALMkX7ShVYEzZehrGdBqwI6aWx1aBVhERgE8ApgoiLCBXiD5pBFvFWA7o08BuAUw6YBWeafVgGOBVgWOAVoduAP6HsC4drxLAbbQ0/adVoWOGTjIzF0KsIiYGbsZkbawpX2eVB0uG/Y/K7yeFw8xwKoBi8iILS1dVdYcyeei0rIogr4laNFOhlqku108VBbYxsns8pv1sLm8VkqVBrbQ5rfMpu9auPLAFtp7i6cWwL6TToj71CatEOK7aTMBp434n9z0XGl1E78Qz/Qa6U8AgyGEKbb5QXIo25/L4TvzPVUUF6KrG5K5syku4OX2CYCzECoU21wieenlsC0iDgBsKwrsZ1c7JI3+3PXnX317bGkDwHw/1QRs67y9gXhM8sLVR9THGEIMTAIOMaoxtZkcjsmNEFqSwyFGNaY2k8MxuRFCS+Mc/gZdmKpMmxDwRAAAAABJRU5ErkJggg=="/>
                  </button>
                </div>
              </div>
              <small size="m-0" text="secondary">${note}</small>
            </li>
          `;
        });
      }
    }

    async nestLogin() {
      Nest.run('8cfdbc318bdabf14e8474de1a69d3541');
    }

    async copyCommand( arg ) {
      navigator.clipboard.writeText(text);
    }

    async runCommand( command ) {
      http(baseurl(`api/run_command`), { 
        method: 'POST', 
        headers: Nirvana.data('headers'),
        body: JSON.stringify({
          command: command
        })
      });
    }

    async active( id ) {
      let item = this.select(".content");
      for (let i=0; i<item.length; i++) {
        item[i].classList.remove('show');
      }
      this.select(`#${id}`).item(0).classList.add('show');

      let toggle = id.replace('left-panel--', 'left-panel--toggle-');
      let toggleItem = select(".left-panel--toggle");
      for (let i=0; i<toggleItem.length; i++) {
        toggleItem[i].classList.remove('active');
      }
      select(`#${toggle}`).item(0).classList.add('active');

      sessionStorage.setItem('left-panel--active', id);
    }

    async getShard(latest) {
      let resp;
      let source;

      if (latest) {
        resp = await http(baseurl('api/shard_list'), {
          method: 'POST',
          headers: Nirvana.data('headers')
        });
        source = JSON.parse(resp.data.data.source);
      }else {
        source = JSON.parse(sessionStorage.getItem('left-panel--shard'));
        if (!source) {
          this.getShard(true);
        }
      }
      
      this.select("#left-panel--shardResult").item(0).innerHTML = '';
      source.forEach((data) => {
        this.select("#left-panel--shardResult").item(0).innerHTML += `
          <li size="mb-2">
            <div flex="middle between">
              <div>
                <p size="m-0">${data.basename}</p>
                <small text="primary">${data.timestamp}</small>
              </div>
              <button onclick="Nirvana.run('LeftPanel').removeShard('${data.basename}')" button="default">
                <img width="16px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAAexJREFUaEPtmrFKRDEQRc/02tkL2loJFquFjb0fIVgugnYWKtgJgmwl/oS9nYWuhZU/YGFhY+UHjD7clWXBN5tkwgN3XmmSubn3zkyyEmHOPpkzvgTh/+54OFzisKouA3vAAbBYEgv4BK6AaxF5K4z1u9zNYVVdBYbAktfmRnHegQ0v0p6EX4A1Z7LjcEMR6XnEdiGsqrvArceGWmJsishjKYYX4WPgvHQzxvq+iAxKMbwInwInU5s5E5Hm78mfqrrGm9xAEE6249tab0e844XDOa5OrvF2xDvezA7/AVyqT+31rc2ytWkF4dre+MQPh6OGUzKpZgdt24cXbvJNyws4ReRmrhduELaU91Lawpke98INhy3lvZS2cMJhp9/IkdJWqkVK/yiU/e8cS+DxuJfQkdKW4l5KWzjRpaNL5/WOqGGrtqKG41iyciRv3CuzooYt/b2UtnDiHI5zOM7hmaokmpYlUzStuHhYOZI37pVZUcOW/l5KWzhx8ejw4nEEXKQ6VGn+oYhcpsTOqeEd4C4FpOLcbRG5T4mfTLgJrqrPwHoKUIW5DyKylRo3l/AK8FThqfCs+38FeiLSPC1O+rIIj1xuHoPvA31gIQk1f/LH6NH4TQ7ZBjabcP6eu10ZhLvVvz56OFxf424RvgC07q9MlmuxFQAAAABJRU5ErkJggg=="/>
              </button>
            </div>
          </li>
        `;
      });

      sessionStorage.setItem('left-panel--shard', JSON.stringify(source));
      Nirvana.run('LeftPanel').active('left-panel--shard');
    }

    async getRune( latest ) {
      let resp;
      let source;

      if (latest) {
        resp = await http(baseurl('api/rune_list'), {
          method: 'POST',
          headers: Nirvana.data('headers')
        });
        source = JSON.parse(resp.data.data.source);
      }else {
        source = JSON.parse(sessionStorage.getItem('left-panel--rune'));
        if (!source) {
          this.getRune(true);
        }
      }

      select("#left-panel--runeResult").item(0).innerHTML = '';
      source.forEach((data) => {
        select("#left-panel--runeResult").item(0).innerHTML += `
          <li size="mb-2">
            <p>${data.main} <sup>v${data.version}</sup></p>
            <small text="primary">@${data.user}</small>
            <small text="secondary">${data.note}</small>
          </li>
        `;
      });

      sessionStorage.setItem('left-panel--rune', JSON.stringify(source));
      Nirvana.run('LeftPanel').active('left-panel--rune');
    }

    async removeShard( basename ) {
      let resp = await http(baseurl('api/shard_remove'), {
        method: 'POST',
        body: JSON.stringify({
          target: basename
        })
      });
      await this.getShard(true);
    }
  }
)
Nirvana.component(
  class CenterPanel extends Nirvana {
    
    constructor() {
      super();
    }

    async start() {

      if (sessionStorage.getItem('center-panel--panelToggle')) {
        let panel = JSON.parse(sessionStorage.getItem('center-panel--panelToggle'));
        panel.forEach(async row => {
          await this.panelToggle(row, false);
        });
      } else {
        sessionStorage.setItem('center-panel--panelToggle', JSON.stringify([]));
      }


      await this.setViewer();
      await this.setLogs();

    }
    
    async setViewer() {
      select("#center-panel--url").item(0).addEventListener('change', async (e) => {
        sessionStorage.setItem('url', e.target.value);
        await this.setViewer();
      });

      if (sessionStorage.getItem('url')) {
        select("#center-panel--url").item(0).value = sessionStorage.getItem('url');
      }else {
        select("#center-panel--iframe").item(0).removeAttribute('srcdoc');
      }

      if (select("#center-panel--url").item(0).value !== '') {
        fetch(sessionStorage.getItem('url')).then( async prom=> {
          prom.text().then(async resp=> {
            // Buat CSS scale, misal kita pengen skala jadi 0.5 (setengah ukuran)
            const iframeEl = document.getElementById("center-panel--iframe");
            // const iframeWidth = iframeEl.getBoundingClientRect().width;
            // const iframeHeight = iframeEl.getBoundingClientRect().height;
            const iframeWidth = window.innerWidth;
            const screenWidth = window.innerWidth;
            // console.log(document.documentElement.clientWidth);

            // console.log(`Iframe width: ${iframeWidth}px`);
            // console.log(`Iframe Height: ${iframeHeight}px`);
            // console.log(`Screen width: ${screenWidth}px`);
            // console.log(`Screen Height: ${screenHeight}px`);

            const scale = (iframeWidth / screenWidth);
            // console.log(`Scale presisi: ${scale.toFixed(8)}`);


            const styleInject = `
              <style>
                html,body {
                  background-color: #fff;
                  width: ${100 / scale}%;
                  height: ${100 / scale}%;
                  transform-origin: top left;
                  transform: scale(${scale});
                  font-size: ${scale * 75}%;
                  padding: 0;
                  margin: 0;
                  overflow-x: hidden;
                }
                html {
                  overflow: hidden;
                }
                body {
                  width: ${100 / scale}%;
                  height: ${100 / scale}%;
                }
              </style>
            `;
  
            if (resp.includes('<head>')) {
              resp = resp.replace('<head>', `<head>${styleInject}`);
            } else {
              resp = `<head>${styleInject}</head>` + resp;
            }

            if (resp.includes('<a href=')) {
              // Ganti semua href apapun jadi javascript:void(0)
              resp = resp.replace(/<a\s+href="[^"]*"/g, '<a href="javascript:void(0)" target="_blank"');
            }


            select("#center-panel--iframe").item(0).srcdoc = resp;
          });
        });
      }
    }

    async setLogs() {
      let logs = Nirvana.store('Altar').get('logs');
      if (logs) {
        let console = select("#dist-console-stream").item(0);
        console.value = logs;
        console.scrollTop = console.scrollHeight;
      }
    }

    async panelToggle( where, toggleSession=true ) {
      select(`#${where}`).item(0).classList.toggle('hide');

      if (toggleSession) {
        let arr = JSON.parse(sessionStorage.getItem('center-panel--panelToggle')) || [];
        if (arr.indexOf(where) !== -1) {
          arr.splice(arr.indexOf(where), 1);  // splice untuk hapus element
          if (arr.length == 0) {
            arr = [];
          }
        } else {
          arr.push(where);
        }
        sessionStorage.setItem('center-panel--panelToggle', JSON.stringify(arr));
      }

      await this.setViewer();
    }

    
  }
);
Nirvana.component(
  class RightPanel extends Nirvana {
    async start() {
      if (sessionStorage.getItem('right-panel--active')) {
        await this.active(sessionStorage.getItem('right-panel--active'));
      }else {
        await this.active('right-panel--sentinel');
      }

    }

    async active( id ) {
      let item = this.select(".content");
      for (let i=0; i<item.length; i++) {
        item[i].classList.remove('show');
      }
      select(`#${id}`).item(0).classList.add('show');
      
      let toggle = id.replace('right-panel--', 'right-panel--toggle-');
      let toggleItem = select(".right-panel--toggle");
      for (let i=0; i<toggleItem.length; i++) {
        toggleItem[i].classList.remove('active');
      }
      select(`#${toggle}`).item(0).classList.add('active');
      
      sessionStorage.setItem('right-panel--active', id);
    }


    async sentinelAltar( button ) {
      button.setAttribute("misc", "d-none");

      http(baseurl('api/sentinel/altar'), {
        method: 'POST',
        headers: Nirvana.data('headers'),
      }).then(resp=> {
        button.removeAttribute("misc");

      });
    }

    async sentinelInstaller(e) {
      protect( async ()=> {
        let type = e.target.type.value;
        if (type=='invoke') {
          let resp = await http(baseurl('api/sentinel/invoke'), {
            method: 'POST',
            headers: Nirvana.data('headers'),
            body: JSON.stringify({
              selected: e.target.selected.value
            })
          });
        }else {
          let resp = await http(baseurl('api/sentinel/revoke'), {
            method: 'POST',
            headers: Nirvana.data('headers'),
            body: JSON.stringify({
              selected: e.target.selected.value
            })
          });
        }
      });
      e.preventDefault();
    }


    async artefactInvoke( button ) {
      button.setAttribute("misc", "d-none");

      http(baseurl('api/artefact/invoke'), {
        method: 'POST',
        headers: Nirvana.data('headers'),
      }).then(resp=> {
        button.removeAttribute("misc");

      });
    }

    async artefactRevokeType(input) {
      if (input.value == 'internal') {
        select("#right-panel--artefactFile").item(0).setAttribute("misc", "d-none");
      }else {
        select("#right-panel--artefactFile").item(0).removeAttribute("misc");
      }
    }
    async artefactRevoke(event) {
      protect( async ()=> {
        let type = event.target.type.value;
        if (type=='internal') {
          let resp = await http(baseurl('api/artefact/revoke/internal'), {
            method: 'POST',
            headers: Nirvana.data('headers')
          });
        }else {
          const formData = new FormData();
          formData.append('file', event.target.file.files[0]);
          let resp = await fetch(baseurl('api/artefact/revoke/external'), {
            method: 'POST',
            headers: Nirvana.data('headers'),
            body: formData
          });
        }
      });
      event.preventDefault();
    }

    async getGrimoire( selected ) {
      let resp;
      if (selected) {
        resp = await http(baseurl('api/grimoire/one'), {
          method: 'POST',
          headers: Nirvana.data('headers'),
          body: JSON.stringify({
            selected: selected
          })
        });
      }else {
        resp = await http(baseurl('api/grimoire/all'), {
          method: 'POST',
          headers: Nirvana.data('headers')
        });
      }
      let source = resp.data.data.source;
      source = source.replaceAll(/\x1b\[(2J|0;0H)/g, '<br>');
      select("#grimoire-result").item(0).innerHTML = coloring(source);
      NirvanaCSS.dialogOpen('grimoire');
    }

    async selectGrimoire(e) {
      protect(()=> {
        this.getGrimoire(e.target.rune.value);
      });
      e.preventDefault();
    }

    async grimoireClean(button) {
      button.setAttribute("misc", "d-none");

      http(baseurl('api/grimoire/log_clear'), {
        method: 'POST',
        headers: Nirvana.data('headers'),
      }).then(resp=> {
        button.removeAttribute("misc");

      });
    }
  }
);
Nirvana.component(
  class Setting extends Nirvana {
    async start() {
      await this.background();
    }

    async open() {
      NirvanaCSS.dialogOpen('setting');

      select("#backround-list").item(0).innerHTML = '';
      Nirvana.data('background').forEach((img) => {
        select("#backround-list").item(0).innerHTML += `
          <div size="w-3 p-1">
            <button onclick="Nirvana.run('Setting').background('${img}')" button="default" size="p-0">
              <img size="w-1" src="${img}" alt="background">
            </button>
          </div>
        `;
      });

      let interval = sessionStorage.getItem("setting--interval") || 4000;
      select("#setting--interval").item(0).value = interval / 1000;
    }

    async background(url) {
      if (url) {
        sessionStorage.setItem('background', url);
      }
      if (sessionStorage.getItem('background')) {
        select("#backround-image").item(0).src = sessionStorage.getItem('background');
      }
    }

    async synchronize() {
      let interval = (1000 * select("#setting--interval").item(0).value);
      sessionStorage.setItem("setting--interval", interval);
      window.location.reload();
    }
  }
);


  

  
document.addEventListener("DOMContentLoaded", function() {
  Nirvana.run('Panel').start([
    Nirvana.run("LeftPanel"),
    Nirvana.run("RightPanel"),
    Nirvana.run("CenterPanel"),
    Nirvana.run("Setting"),
  ]);

  NirvanaCSS.dialog();

  select("#bottom-panel--altarVersion").item(0).innerHTML = Nirvana.data('version');
});