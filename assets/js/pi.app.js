/**
 * Minimal app bootstrapper
 *
 * Merges existing app object with new
 *
 * We don't check for existence of the π object, since we want 
 * this code to break if Pi is not loaded
 *
 */



    π.TMP = π.app;

    π.app = {

      PI_ROOT   : π.PI_ROOT,
      LIB_ROOT  : π.LIB_ROOT,
      IMG_ROOT  : π.IMG_ROOT,
      CSS_ROOT  : π.CSS_ROOT,

      self      : this,


      __init : function() {
        for(var key in pi.TMP) {
          this[key] = pi.TMP[key]
        }
        pi.TMP = null;
      }
    };

    π.app.__init();
    π.app._loaded = true;



