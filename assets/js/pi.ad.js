  var 
    π  = π  || {};



  π.ad = π.ad || {

    _loaded : false,


    template : {

      render : function (template, data) {
        var
          template = template || null,
          defaults = {
            adwidth   : 640, 
            adheight  : 180
          },
          data = data || {};

        // check inputs
        if ( data == {} || typeof data != "object" ){
          pi.log("NOT OBJECT, OR EMPTY OBJECT");
          return template + "<div>data==[] or !isArray(data)</div>";
        } 
        if (template === null) {
          pi.log("NO TEMPLATE");
          return JSON.stringify({error : "no template"});
        }

        // add defaults to data
        for (var i in defaults) {
          if(typeof data[i] === "undefined") {
            pi.log("setting " + i + " to " + defaults[i]);
            data[i] = defaults[i];
          }
        }

        // render
        for (var key in data) {
          template = template.replace(new RegExp("\{(" + key + "[^}]?)\}"), data[key]);
          template = template.replace(new RegExp("\{(" + key + "[^}]+)\}"), data[key]);
        }

        return template;
      }
    }
  }; // pi.ad


  pi.ad._loaded = true;

  pi.events.trigger('pi.ad', +new Date().getTime());

