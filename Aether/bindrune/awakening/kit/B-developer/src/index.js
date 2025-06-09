
/* Nirvana JS
 * full documentation here
 * https://github.com/AnwarAchilles/nirvana-native-js
 * */

// set environment
Nirvana.environment({});

// create component
Nirvana.component(
  class App extends Nirvana {

    // stored data
    store = Nirvana.store("App");

    // start function
    async start() {
      this.store.set("result", 0);
    }
    
    // click to add number
    async click( number ) {
      // get last number from store
      const lastNumber = this.store.get('result');
      // add last number with clicked number
      const resultNumber = parseInt(lastNumber) + parseInt(number);
      // save data to store
      this.store.set("result", resultNumber);
      // output data to element
      select("#result").item(0).innerHTML = resultNumber;
    }

    // http client to get product
    async getProduct() {
      // fetch http
      let http = await fetch('?api/product', {
        method: 'GET'
      });
      // response as json
      let resp = await http.json();
      // output data to element
      select('#result-product').item(0).innerHTML = JSON.stringify(resp);
    }
  }
);

// run first time load
Nirvana.run('App').start();