    var
      π = π || {};


    π.xhr = π.xhr || {

      _loaded : false,

      json : function ( url, obj, callback, onerror ) {
        var
          xhr = new XMLHttpRequest(),

          obj       = obj       || null,
          callback  = callback  || null,
          onerror   = onerror   || π.log,
          isIe      = π.browser.isIe;

        if (!callback && typeof obj == "function") {
          callback = obj;
        }

        xhr.callback  = callback;
        xhr.onerror   = onerror;
        

        xhr.onreadystatechange = function () {
          //  IE shit
          if (this.readyState != 4) { return }

          if (!isIe(8) && !isIe(7))  { return }

          if (this.status != 200) {
            this.onerror.call(this);
          }
          else {
            this.onload.call(this);
          }
        };



        xhr.onload = function() { 
          var
            json = this.responseText || '{ error : "xhr: no data." }';

          try {
            json = JSON.parse(json);
          }
          catch(e) {
            json = {
              error : "Error : exception when parsing JSON string",
              jsonSource : this.responseText
            };
          }

          if (typeof this.callback === "function") {
            this.callback.call(this, json);
          }
        };


        xhr.open("post", url, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        return xhr.send(JSON.stringify(obj));
      },


      post : function ( url, obj, callback, onerror ) {
        var
          xhr   = new XMLHttpRequest(),
          isIe  = π.browser.isIe,

          obj       = obj       || null,
          callback  = callback  || null,
          onerror   = onerror   || π.log;

        xhr.callback  = callback;
        xhr.onerror   = onerror;
        
        xhr.onreadystatechange = function () {
          //  IE shit
          if (this.readyState != 4) { return }

          if (!isIe(8) && !isIe(7))  { return }

          if (this.status != 200) {
            this.onerror.call(this);
          }
          else {
            this.onload.call(this);
          }
        };


        xhr.onload = function() { 
          if (this.responseText.indexOf(".php</b> on line <b>") !== -1) {
            pi.log("php error detected : ");
            pi.log(this.responseText);
            if (typeof this.onerror == "function") {
              this.onerror.call(this, this.responseText);
            }
          }
          else {
            // pi.log("no php error detected : ");
            // pi.log(this.responseText);
          }

          if (typeof this.callback == "function") {
            this.callback.call(this, this.responseText || '{ error : "xhr: no data." }');
          }
        };

        xhr.open("post", url, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        return xhr.send(JSON.stringify(obj));
      },



      get : function ( url, callback, onerror ) {
        var
          xhr   = new XMLHttpRequest(),
          isIe  = π.browser.isIe || false,

          callback  = callback  || null,
          onerror   = onerror   || π.log;

        xhr.callback  = callback;
        xhr.onerror   = onerror;

        
        xhr.onreadystatechange = function () {
          //  IE shit
          if (this.readyState != 4) { return }

          if (!isIe(8) && !isIe(7))  { return }

          if (this.status != 200) {
            this.onerror.call(this);
          }
          else {
            this.onload.call(this);
          }
        };

        xhr.onload = function() { 
          var
            response = this.responseText || '{ error : "no data." }';

          if (this.responseText.indexOf(".php</b> on line <b>") !== -1) {
            π.log("php error detected : ");
            π.log(this.responseText);
            if (typeof this.onerror === "function") {
              this.onerror.call(this, this.responseText);
            }
          }

          if (typeof this.callback === "function") {
            this.callback.call(this, response);
          }
        };


        xhr.open("get", url, true);
        return xhr.send();
      }


      
    };

    π.xhr._loaded = true;

