// ==ClosureCompiler==
// @compilation_level SIMPLE_OPTIMIZATIONS
// @language ECMASCRIPT5
// @output_file_name pi.core.min.js
// ==/ClosureCompiler==


  /**
   *
   * π v0.6.2.1
   *
   * @module Pi
   * @description Pi is an html5-based distributed client-server application platform.
   * 
   * This is the client part.
   *
   * @author Johan Telstad, jt@viewshq.no
   * 
   * @license MIT
   * 
   * @uses     PubSub.js  -  https://github.com/Groxx/PubSub 
   *           @copyright 2011 by Steven Littiebrant
   *
   */


  var 
      π  = π  || {};


  /*  ----  Our top level namespaces  ----  */


    // The core modules
    π.core        = π.core        || { _loaded: false, _ns: 'core'      };
    π.callback    = π.callback    || { _loaded: false, _ns: 'callback'  };
    π.session     = π.session     || { _loaded: false, _ns: 'session'   };
    π.events      = π.events      || { _loaded: false, _ns: 'events'    };
    π.tasks       = π.tasks       || { _loaded: false, _ns: 'tasks'     };
    π.timer       = π.timer       || { _loaded: false, _ns: 'timer'     };


    // The built-in libraries
    π.srv         = π.srv         || { _loaded: false, _ns: 'srv'       };
    π.app         = π.app         || { _loaded: false, _ns: 'app'       };
    π.pcl         = π.pcl         || { _loaded: false, _ns: 'pcl'       };
    π.system      = π.system      || { _loaded: false, _ns: 'system'    };
    π.debug       = π.debug       || { _loaded: false, _ns: 'debug'     };
    π.io          = π.io          || { _loaded: false, _ns: 'io'        };
    π.file        = π.file        || { _loaded: false, _ns: 'file'      };



    // For extending the platform
    π.ext         = π.ext         || { _loaded: false, _ns: 'ext'       };
    π.fn          = π.fn          || { _loaded: false, _ns: 'fn'        };
    π.lib         = π.lib         || { _loaded: false, _ns: 'lib'       };
    π.util        = π.util        || { _loaded: false, _ns: 'util'      };
    π.plugins     = π.plugins     || { _loaded: false, _ns: 'plugins'   };
    π.maverick    = π.maverick    || { _loaded: false, _ns: 'maverick'  };



    π._const = π._const || {

      // paths
      PI_ROOT     : "assets/js/",

      /* !!!  CUSTOM for this project ONLY  !!! */
      LIB_ROOT    : "assets/js/",
      // LIB_ROOT    : "../../assets/js/",

      API_ROOT    : "/api/",

      LOG_URL     : "/api/log/",

      // platform constants
      TWEEN_TIME      : 0.2,
      DEFAULT_TIMEOUT : 30
    };



    //will keep an updated list over which modules are loaded
    π.loaded = π.loaded || {};


    // create pi as an alias for π
    var 
      pi = π;


    /*    begin core modules     */



       /**
        * 
        * @module core.callback
        * @description  Store references to local callback functions
        *               Call remote procedure and create a listener for the result
        *               Invoke local callback when result arrives
        * 
        */

          π.core.callback = π.core.callback || {

            /**
             * Manages callback handlers
             *
             * Issues reply addresses, and invokes related
             * callback when response is received from server
             * 
             */

            __id      : 0,
            __prefix  : "___callback",
            __items   : {},

            

            //public

            // insert callback and return name of newly inserted item
            add : function (callback) {

              // check input
              if (typeof callback != "function") {
                pi.log("Error: Tried to add non-function as callback:", callback);
                return false;
              }

              var
                self  = π.core.callback;
              var
                id    = self.__prefix + (self.__id++).toString(16);


              self.__items[id] = { callback: callback, timestamp: (new Date().getTime()) };

              return id;
            },


            call : function (id, data) {
              var 
                item    = π.core.callback.__items[id],
                result  = false;

              if (item && (typeof item.callback == "function")) {        

                // pi.log("invoking callback " + id + " after " + ( (new Date().getTime()) - item.timestamp ) + "ms");
                result = item.callback.call(this, data);
                // clear callback item
                item = null;

                return result;
              }
              else {
                pi.log("Error invoking callback: " + id, item);
              }
            }

          };


        π.callback = π.core.callback;
        π.callback._loaded = true;





        /**
         * The client-side hub of the pi messaging system.
         * It handles data routing, message passing and pubsub events.
         * 
         * @author Johan Telstad, jt@viewshq.no, 2011-2014
         * 
         * @uses     PubSub.js  -  https://github.com/Groxx/PubSub 
         *           @copyright 2011 by Steven Littiebrant
        **/


          π.events = π.events || {};


        /** 
          PubSub.js
         
          Copyright 2011 by Steven Littiebrant - changes and communication about use 
          appreciated but not required: https://github.com/Groxx/PubSub

          Permission is hereby granted, free of charge, to any person obtaining a copy
          of this software and associated documentation files (the "Software"), to deal
          in the Software without restriction, including without limitation the rights
          to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
          copies of the Software, and to permit persons to whom the Software is
          furnished to do so, subject to the following conditions:

          The above copyright notice and this permission notice shall be included in
          all copies or substantial portions of the Software.  Minified files may 
          reduce this license information to the first two lines at the top of the 
          LICENSE file.

          THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
          IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
          FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
          AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
          LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
          OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
          THE SOFTWARE.
           **/


          /**
           *  PubSub 
           *
           * Creates a new subscription object.  If passed, attaches to the passed object; otherwise, assumes it was called with "new" and binds to `this`.
           * If passed, unique defines the internal unique subscription list's storage name.  By default it is "_sub".
           * This should be relatively safe to call even without "new" - it just makes the global object an event system.
           * If true, alsoPassPath will result in the publishing-path being pushed to the end of the arguments passed to subscribers.
           * 
           */

            var PubSub = function PubSub(obj_or_alsoPassPath_or_unique, alsoPassPath_or_unique, uniqueName) {
              var 
                unique        = "_sub",
                subscriptions = {},
                passPath      = false,
                bindTo        = this,


                /**
                 * Removes all instances of handler from the passed subscription chunk.
                 * @param  {[type]} cache   [description]
                 * @param  {[type]} handler [description]
                 * @return {[type]}         [description]
                 */
                _unsubscribe = function (cache, handler) {
                  for (var i = 0; i < cache[unique].length; i++) {
                    if (handler === undefined || handler === null || cache[unique][i] === handler) {
                      cache[unique].splice(i, 1);
                      i--;
                    }
                  }
                },

                /**
                 * Recursively removes all instances of handler from the passed subscription chunk.
                 * @param  {[type]} cache   [description]
                 * @param  {[type]} handler [description]
                 * @return {[type]}         [description]
                 */
                _deepUnsubscribe = function (cache, handler) {
                  for (sub in cache) {
                    if (typeof cache[sub] != "object" || sub === unique || !cache.hasOwnProperty(sub)) continue;
                    _deepUnsubscribe(cache[sub], handler);
                  }
                  _unsubscribe(cache, handler);
                };
              


              // setup / config
              
              if (typeof uniqueName == "string") {
                unique = uniqueName;
              } 
              else if (typeof alsoPassPath_or_unique == "string") {
                unique = alsoPassPath_or_unique;
              } 
              else if (typeof obj_or_alsoPassPath_or_unique == "string") {
                unique = obj_or_alsoPassPath_or_unique;
              }

              if (typeof alsoPassPath_or_unique == "boolean") {
                passPath = alsoPassPath_or_unique;
              } else if (typeof obj_or_alsoPassPath_or_unique == "boolean") {
                passPath = obj_or_alsoPassPath_or_unique;
              }
              
              if (typeof obj_or_alsoPassPath_or_unique == "object" || typeof obj_or_alsoPassPath_or_unique == "function") {
                bindTo = obj_or_alsoPassPath_or_unique;
              }
              
              // all subscriptions, nested.
              subscriptions[unique] = [];
              

              /**
               * Calls all handlers on the path to the passed subscription.
               * Ie, "a.b.c" would call "c", then "b", then "a".
               * If any handler returns false, the event does not bubble up (all handlers at that level are still called)
               * 
               * @param  {str}            sub
               * @param  {Array|Object}   callback_args [description]
               * @return {void}
               */
              bindTo.publish = function (sub, callback_args) {
                var 
                  c, s,
                  sub   = sub || "",
                  args  = null,
                  cache = subscriptions,
                  stack = [],
                  exit  = false;

                if (arguments.length > 2) {
                  // If passing args as a set of args instead of an array, grab all but the first.
                  args = Array.prototype.slice.apply(arguments, [1]);
                }
                else {
                  args = [callback_args];
                } 

                s = sub.split(".");

                if (passPath) {
                  args.push(s);
                }

                stack.push(cache);

                for (var i = 0; i < s.length && s[i] !== ""; i++) {
                  if (cache[s[i]] === undefined) {
                    break;
                  }
                  cache = cache[s[i]];
                  stack.push(cache);
                }
                while ((c = stack.pop())) {
                  for (var j = 0; j < c[unique].length; j++) {

                    // if any handler returns boolean FALSE
                    if (c[unique][j].apply(this,args) === false) {
                      exit = true;
                    }
                  }
                  if (exit) {
                    break;
                  }
                }
                return bindTo;
              };

              
              bindTo.subscribe = function (sub, handler) {
                var 
                  s,
                  cache = subscriptions;
                  sub = sub || "";

                s = sub.split(".");
                
                for (var i = 0; i < s.length && s[i] !== ""; i++) {
                  if (!cache[s[i]]) {
                    cache[s[i]] = {};
                    cache[s[i]][unique] = [];
                  }
                  cache = cache[s[i]];
                }

                cache[unique].push(handler);

                return bindTo;
              };
              
              
              // Removes _all_ identical handlers from the subscription.  
              // If no handler is passed, all are removed.
              // If deep, recursively removes handlers beyond the passed sub.
              bindTo.unsubscribe = function (sub, handler, deep) {
                var 
                  s,
                  cache = subscriptions,
                  sub   = sub || "";

                if (sub) {
                  s = sub.split(".");
                  
                  for (var i = 0; i < s.length && s[i] !== ""; i++) {
                    if (cache[s[i]] === undefined) {
                      return;
                    }
                    cache = cache[s[i]];
                  }
                }

                if (typeof handler == "boolean") {
                  deep = handler;
                  handler = null;
                }
                
                if (deep) {
                  _deepUnsubscribe(cache, handler);
                } else {
                  _unsubscribe(cache, handler);
                }
                return bindTo;
              }; //unsubscribe

            }; // function PubSub()


          /*
          End of PubSub.js code

          PubSub.js 
          Copyright 2011 by Steven Littiebrant - changes and communication about use 
          appreciated but not required: https://github.com/Groxx/PubSub
        */

          // attach it to pi.events
          PubSub(π.events, false);



          // public functions


          // /**
          //  * Wrapper for custom event triggering utilizing browser's internal event system
          //  *
          //  * @param  {String}       eventName   The event name
          //  * @param  {Object}       eventData   Optional data object
          //  * @param  {HTMLElement}  eventElem   Optional DOM element to use as event dispatcher
          //  * 
          //  * @return {bool}                     Boolean FALSE on failure, or result from dispatchEvent()
          //  */
          
          // π.events.trigger = function (eventName, eventData, eventElem) {
          //   var
          //     eventName   = eventName || false,
          //     eventData   = eventData || null,
          //     dispatcher  = eventElem || window,
          //     customEvt   = null;

          //   // early escape
          //   if (!eventName) {
          //     return false;
          //   }


          //   // are we handicapped ?
          //   if (!window.CustomEvent) {
          //     try {
          //       customEvt = document.createEvent("CustomEvent");
          //       if (eventData) {
          //         customEvt.initCustomEvent(eventName, false, false, eventData);
          //       }
          //       else {
          //         customEvt.initCustomEvent(eventName, false, false, {});
          //       }
          //       dispatcher.dispatchEvent(customEvt);
          //     }
          //     catch(e) {
          //       pi.log('Exception : ', e);
          //     }

          //   }
          //   else {
          //     // we are not handicapped

          //     if (eventData) {
          //       // event has a payload
          //       dispatcher.dispatchEvent(new CustomEvent( eventName, { detail : eventData } ));
          //     } else {
          //       // event is only named
          //       dispatcher.dispatchEvent(new CustomEvent(eventName));
          //     }
          //   }

          // };


          /**
           * Wrapper for custom event triggering
           *
           * @param {string} eventName Event name
           * @param {object} eventData Object containing event data
           * @param {element} eventElem The element used to dispatch the event
           *
           * @return {boolean} Boolean FALSE on failure, TRUE on success
           * @version 2.0 Uses internal messaging system for IE
           */

          π.events.trigger = function (eventName, eventData, eventElem) {
            var
              eventName   = eventName || false,
              eventData   = eventData || null,
              dispatcher  = eventElem || window,
              customEvt   = null;

            // early escape
            if (!eventName) {
              return false;
            }

            // are we handicapped ?
            if (pi.browser.isIe() === true) {
              pi.events.publish(eventName, eventData, eventElem);
            }
            else {
              // nope!
              if (eventData) {
                dispatcher.dispatchEvent(new CustomEvent( eventName, { detail : eventData } ));
              } else {
                dispatcher.dispatchEvent(new CustomEvent(eventName));
              }
            }

          };



          // aliases for the trigger function
          π.events.emit     = π.events.trigger;
          π.events.dispatch = π.events.trigger;



          π.events._loaded = true;


    /*    end of core modules     */



    π.browser = π.browser || {};

    /**
     * Regex test for Internet Explorer
     * 
     * @param  {int}  v     Version to check for
     * 
     * @return {Boolean}    Returns true if browser is IE
     */
    π.browser.isIe = function (v) {
      return RegExp('msie' + (!isNaN(v) ? ('\\s' + v) : ''), 'i').test(navigator.userAgent);
    };

    /**
     * Regex test for handheld devices (smartphones + tablets)
     * 
     * @return {Boolean} True if device is a handheld, false otherwise
     */
    π.browser.isMobile = function () {
      return /ip(hone|od|ad)|android|blackberry.*applewebkit|bb1\d.*mobile/i.test(navigator.userAgent);
    }



    /**
     * UUID generator
     * 
     * @return  {str[36]}   RFC-4122 v4 compliant UUID
     *
     * @author  broofa, stackoverflow user
     * @see     http://stackoverflow.com/a/2117523/5027184
     */
    π.uuid = function () {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var 
          r = Math.random() * 16|0, 
          v = c == 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
      });
    }


    π.isObject = function (obj) {
      return obj && obj instanceof Object && obj.constructor.toString().indexOf("Array") == -1;
    }


    /*    JS versions of PHP utility functions. (PHP under_score => JS camelCase)    */

    /**
     * JS version of PHP's is_array()
     * 
     * @param  {Object}  obj Object reference
     * 
     * @return {Boolean}     Boolean TRUE if obj is a native JS array
     */
    π.isArray = function (obj) {
      return (Object.prototype.toString.call(obj) == "[object Array]");
    }


    /**
     * (from jQuery)
     * @param  {Object}  obj The reference to check
     * @return {Boolean}     True if numeric, false if not
     */
    π.isNumeric = function(obj) {
      return !π.isArray(obj) && (obj - parseFloat(obj) + 1) >= 0;
    }


    /**
     * JS version of PHP's str_pad()
     * 
     * @param  {string} str       The string to pad
     * @param  {int}    padto     Desired length
     * @param  {string} padstr    Pad with this string
     * @param  {bool}   padleft   Flag to pad string from the left (default i pad from right)
     * 
     * @return {string}     The padded string
     */
    π.strPad = function (str, padto, padstr, padleft) {
      var
        padstr  = padstr  || "&nbsp;",
        padto   = padto   || false,
        padleft = padleft || false, // default is to pad on the right
        count   = 0,
        str     = str.toString(),
        result  = str;

      count = padto - str.length;

      if (count <= 0 || padto === false) {
        return str;
      }

      for (;count--;) {
        if (padleft) {
          result = padstr + result;
        }
        else {
          result += padstr;
        }
      }

      return result;
    };


    /**
     * JS version of PHP's basename()
     * 
     * @param  {string} filename The full path and filename
     * @param  {string} ext      The extension to trim
     * 
     * @return {string}          The full path without the filename part
     */
    π.basename = function (filename, ext) {
      var
        filename  = filename || null,
        ext       = ext || "",
        token     = "",
        slashpos  = -1;


      if (filename.lastIndexOf("/") == filename.length-1) {
        filename.length--;
      }

      slashpos = filename.lastIndexOf("/");
      if (slashpos > -1) {
        token = filename.substring(slashpos+1);
      }
      else {
        token = filename;
      }

      if (ext && typeof ext === "string") {
        var strlen = token.length;
        if (token.lastIndexOf(ext) == (strlen - ext.length)) {
          token = token.substring(0, token.lastIndexOf(ext));
        }
      }

      return token;
    };



    // π utility functions


    /**
     * Get text value of span element
     * The purpose of these functions is to avoid re-flowing wherever possible,
     * and to preclude any tag injection chicanery
     * 
     * @param  {string}   id      Id of the span element
     * 
     * @return {bool|string}      Text content of span element, or boolean False on error.         
     */
    π.gettext = function(id) {
      var
        span  = document.getElementById(id) || null;

      if (span === null) {
        return false;
      }
      if (span.textContent) {
        return span.textContent;
      }
      else if (span.innerText) {
        return span.innerText;
      }
      else {
        return false;
      }
    };




    /**
     * Set text value of span element
     * The purpose of these functions is to avoid re-flowing wherever possible,
     * and to preclude any tag injection chicanery
     * 
     * @param  {string}   id      Id of the span element
     * @param  {string}   value   New text value to display
     * 
     * @return {bool}         
     */
    π.settext = function(id, value) {
      var
        span  = document.getElementById(id) || null,
        value = value || "";

      if (span === null) {
        return false;
      }
      if (span.textContent) {
        span.textContent = value;
      }
      else if (span.innerText) {
        span.innerText = value;
      }
      return true;
    };



    /**
     * Add text value to a span element
     * The purpose of these function is to avoid re-flowing wherever possible,
     * and to prevent any tag injection chicanery
     * 
     * @param  {string}   id      Id of the span element
     * @param  {string}   value   Text value to add
     * @param  {bool}     head    Whether to add text to the left of current value or not
     * 
     * @return {bool}
     */
    π.addtext = function(id, value, head) {
      var
        value = value || "",
        head  = head  || false,
        span  = document.getElementById(id) || null;

      if (!(span || value || id)) {
        return false;
      }
      if (head) {
        if (span.textContent) {
          span.textContent = value + span.textContent;
        }
        else if (span.innerText) {
          span.innerText = value + span.innerText;
        }
      }
      else {
        if (span.textContent) {
          span.textContent += value;
        }
        else if (span.innerText) {
          span.innerText += value;
        }
      }
    };




    /**
     * π's internal string "heap" (not a real heap)
     * @type {Object}
     */
    π.heap = π.heap || {
      // private
      __vars : [],
      
      add : function (id, value) {
        var
          id    = id    || null,
          value = value || null,
          self  = π.heap;

        if (id !== null && value !== null) {
          var piaddr = id;
          id = id.replace(/\./g,'-');

          var 
            item = self.__vars[id] = {
              // the object we throw on the heap, stored at __vars[id]
              __e : document.getElementById(id) || null,
              address : piaddr,

              // getters and setters, supported in IE9+ (EcmaScript 5)
              get element() {
                return this.__e;
              },
              set element(id) {
                this.__e = (π.isElement(id) ? id : document.getElementById(id)) || null;
              },
              get value() {
                // if element not set, value defaults to null
                if(!this.__e) return null;

                if (this.__e.textContent) {
                  // for modern browsers
                  return this.__e.textContent;
                }
                else if (this.__e.innerText) {
                  // for IE
                  return this.__e.innerText;
                }
                // fallback
                else return this.__e.innerHTML || "";
              },
              set value(s) {
                // this.__e = this.__e || document.getElementById(s) || null;
                if(!this.__e) return false;

                if (this.__e.textContent) {
                  // for modern browsers
                  this.__e.textContent = s;
                }
                else if (this.__e.innerText) {
                  // for IE
                  this.__e.innerText = s;
                }
                // fallback
                else this.__e.innerHTML = s;
              },

            }; // END var item = { ..

        } // END if (id && value)
        else {
          // if NOT (id && value)
          /** @todo rewrite this to an early escape, it's kind of upside-down as-is */
          return false;
        }
      },
      
      /**
       * Getter for item at given index
       * 
       * @param  {int|string} id  The numeric or hash index to get
       * 
       * @return {mixed}          Returns the value stored at given index
       */
      item : function(id) {
        var
          self  = π.heap,
          id    = id || false;

        if (id === false) {
          return false;
        }
        return self.__vars[id] || self.__vars[id.replace(/\./g, '-')] || null;
      },

      /**
       * Remove item at given index
       * 
       * @param  {int|string} id  The numeric or hash index to get
       * 
       * @return {bool}           True on success, False on failure.
       */
      remove : function(id) {
        if (π.heap.__vars[id]) {
          π.heap.__vars[id] = null;
        } 
        else if (π.heap.__vars[id.replace(/\./g, '-')]) {
          π.heap.__vars[id.replace(/\./g, '-')] = null;
        }
        else {
          return false;
        }
        return true;
      },
      
      /**
       * Clear all items from array
       * Call array items's own 'clear' method, if it exists
       * @return {void} 
       */
      clear : function() {
        var
          item = null;

        while (π.heap.__vars.length > 0) {
          item = π.heap.__vars.pop();
          if (item && typeof item.clear == "function") {
            // call item's own clear(), in item's own context
            item.clear.call(item);
          }
        }
      }

    }; // pi.heap




    /**
     * Dump contents of Array object
     * 
     * @param  {Array} array The Array to dump
     * 
     * @return {void}
     */
    π.logArray = function (array) {
      var
        i = array.length;

      while (i--) {
        // π.strPad = function (str, padto, padstr) {
        pi.log(pi.strPad(i, 4, " ") + " : " + array[i]);
      }
    };


    π.logObject = function (obj) {
        pi.log("Object : ", obj);
    };


    /**
     * Universal logging function
     * 
     * @param  {string} msg The message to log
     * @param  {Object} obj An object to accompany the log line
     * 
     * @return {void}
     */
    π.log = function (msg, obj) {

      if (!!obj) {
        console.log(msg, obj);
      }
      else {
        if (π.isArray(msg)) {
          pi.logArray(msg);
        }
        else if (typeof msg === "object") {
          π.logObject(msg);
        }
        else {
          console.log(msg);
        }
      }

      if (pi.app.console && typeof pi.app.console.log == "function") {
        pi.app.console.log(msg, obj);
      }

    };



    /**
     * Search an object and its children
     * 
     * @param  {string} token     Search string
     * @param  {Object} obj       The object to search
     * @param  {int}    where     Flag: 0 => search both keys and values, 1 => keys, 2 => values
     * @param  {int}    exact     Flag: 1 => match exactly, 0 => match any occurrence
     * @param  {bool}   multiple  Flag: Return multiple matches
     * 
     * @return {Object|bool}      Mathing Object(s), or boolean FALSE if not found.
     */
    π.search = function (token, obj, where, exact, multiple) {
      var
        result    = null,
        multiple  = multiple  || false,
        token     = token     || null,
        obj       = obj       || null,
        exact     = exact     || 1, // 1 => match exactly, 0 => match any occurrence
        where     = where     || 0; // 0 => search both keys and values, 1 => keys, 2 => values

      if (!obj || !token) {
        pi.log("no obj");
        return false;
      }

      for (var item in obj) {

        if (where === 0 || where === 1) {
          if (exact) {
            if (item == token) {
              return obj[item];
            }
          }
          else {
            if (item.indexOf(token)>-1) {
              return obj[item];
            }
          }
        }

        // recurse into object properties
        if (typeof obj[item] == "object") {

          result = pi.search(token, obj[item], where, exact, multiple);
          if (!result) {
            continue;
          }
          else {
            return result;
          }
        }

        if (!obj.hasOwnProperty(item)) {
          continue;
        }

        if (where === 2 || where === 0) {

          if (exact == 1 && obj[item].toString() == token) {
            result = obj;
            return obj;
          }
          else if (exact == 0 && obj[item].toString().indexOf(token) != -1) {
            result = obj;
            return obj;
          }
          else {
            result = false;
          }
        }

      } // for var item in obj

      return result;

    };




    /**
     * Search an object and its children, 2nd version
     * 
     * @param {string}  token     Search term
     * @param {object}  obj       The object to search through
     * @param {boolean} exact     Only exact matches
     * @param {integer} multiple  Not implemented
     * 
     * @return {boolean|object} Boolean false for error, or an object matching search criteria
     *                          Returns null if not found.
     */


    π.search2 = function (token, obj, where, exact, multiple) {
      var
        result    = null,
        multiple  = multiple  || false,
        token     = token     || null,
        obj       = obj       || null,
        exact     = exact     || 1, // 1 => match exactly, 0 => match any occurrence
        where     = where     || 0; // 0 => search both keys and values, 1 => keys, 2 => values

      if (!obj || !token) {
        return false;
      }

      for (var item in obj) {

        if (where === 0 || where === 1) {
          if (exact) {
            if (item == token) {
              return obj[item];
            }
          }
          else {
            if (item.indexOf(token)>-1) {
              return obj[item];
            }
          }
        }

        // recursion part
        if (typeof obj[item] == "object") {

          result = pi.search2(token, obj[item], where, exact, multiple);
          if (!result) {
            continue;
          }
          else {
            return result;
          }
        }

        if (!obj.hasOwnProperty(item)) {
          continue;
        }

        if (where === 2 || where === 0) {

          if (exact == 1 && obj[item].toString() == token) {
            result = obj;
            return obj;
          }
          else if (exact == 0 && obj[item].toString().indexOf(token) != -1) {
            result = obj;
            return obj;
          }
          else {
            result = null;
          }
        }
      } // for var item in obj

      return result;
    };




    /* DOM-related functions  */


    /**
     * Returns TRUE if obj is a DOM Node
     * 
     * @param  {DOMElement}  obj    The DOM reference to check
     * 
     * @return {bool}               Boolean TRUE if param is a DOM Node, FALSE otherwise.
     */
    π.isNode = function (obj) {
      return (
        typeof Node == "object" ? obj instanceof Node : 
        obj && typeof obj == "object" && typeof obj.nodeType == "number" && typeof obj.nodeName == "string"
      );
    };



    /**
     * Returns true if obj is a DOM element
     * 
     * @param  {HTMLElement}  obj The element to check
     * 
     * @return {bool}     Returns true if it is a DOM element
     */
    π.isElement = function (obj) {
      return (
        typeof HTMLElement == "object" ? obj instanceof HTMLElement : //DOM2
        obj && typeof obj == "object" && obj !== null && obj.nodeType == 1 && typeof obj.nodeName == "string" 
      );
    };



    /**
     *  Dynamically polyfill missing features
     *  
     * @param {string}      feature   The feature to polyfill
     * @param {DomElement}  elem      An optional DomElement to use for attaching. If this
     *                                variable is not present, 'window' will be used instead.
     *
     * @return {Boolean|DomElement}   Boolean FALSE on failure, or new DomElement on success
     */
    π.polyfill = function (feature, elem) {
      var 
        feature = feature || null,
        elem    = elem    || window;

      if (feature in elem) {
        return;
      }

      pi.require('polyfill.' + feature.toLowerCase());

      return ;
    };




    /**
     *  Inject html source into the DOM
     *
     * @param {string} src The source to inject
     * 
     * Optional
     * @param {DomElement} elem An optional DomElement to use for injection. If
     * this variable is not present, document.body will be used instead.
     *
     * @return {Boolean|DomElement} False on failure, or new DomElement reference on success
     *  
     */

    π.inject = function (src, elem) {
      var 
        element   = elem || document.body,
        fragment  = document.createDocumentFragment(),
        container = document.createElement("div");

      // we do it this way to render the markup before we add it to the DOM
      container.innerHTML = src;
      fragment.appendChild(container);

      if (elem && (elem != document.body)) {
        π.clear(elem);
      }

      return element.appendChild(fragment);
    };



    /**
     * Remove any children from element
     * 
     * @param   {Object}  elem    The element to clear
     * 
     * @return  {bool|integer}    Number of children removed, or boolean FALSE on error
     */

    π.clear = function (elem) {
      var 
        element   = elem || null,
        removed   = 0;

      if (!π.isElement(elem)) {
        return false;
      }
       
      // clear element
      while (elem.firstChild) {
        elem.removeChild(elem.firstChild);
        removed++;
      }

      return removed;
    };



    /**
     * Bog-standard JS clone() function
     * 
     * @param  {Object}   obj   The object to clone
     * 
     * @return {Object}         Returns cloned object
     * @requires HTML5 [Array.forEach]
     */
    
    π.clone = function (obj) {
      var
        clone = Object.create(Object.getPrototypeOf(obj)),
        props = Object.getOwnPropertyNames(obj);

      props.forEach(function (name) {
        Object.defineProperty(clone, name, Object.getOwnPropertyDescriptor(obj, name));
      });

      return clone;
    };


    /**
     * Bog-standard JS create() function
     * 
     * @param  {Object}   obj   The object to create instance if
     * 
     * @return {Object}         Returns new instance of Object
     */
    
    π.create = function (obj) {
      var 
        f = function () {};
      f.prototype = obj;
      return new f();
    };



    /**
     * Copy js object
     * 
     * @param  {Object}   obj         The object to copy
     * @param  {Array}    exceptions  Array of member properties to skip
     * 
     * @return {Object}               The copied object
     */
    π.copy = function (obj, exceptions) {
      var
        obj         = obj         || false,
        exceptions  = exceptions  || false,
        newobj      = null;


      if (typeof obj == "string") {
        try {
          obj = JSON.parse(obj);
        }
        catch(e) {
          pi.log('Error in JSON.parse("' + obj + '")', e);
          return null;
        }
      }

      newobj = {};
      for (var i in obj) {
        if ((i % 1 === 0)) {
          // skip numerical indices
          // continue;
        }
        if (exceptions !== false) {
          if (exceptions.indexOf(i) >- 1) {
            continue;
          }
        }
        newobj[i] = obj[i];
      }
      return newobj;

    };






    /** 
     * Listen to an address in the global namespace via EventSource/SSE
     * 
     * @param  {string}     address   Address in the pi namespace to listen to
     * @param  {Function}   onerror   Callback on error
     * @param  {Function}   callback  Callback for each message
     * 
     * @return {Null|EventSource} New EventSource object on success, null on failure.
     */

    π.listen = function (address, callback, onerror) {

        // early escape
        if (!!address) {
          if (typeof callback != "function") {
            return false;
          }
        } 
        else {
          return false;
        }

      var
        source  = new EventSource(π._const.API_ROOT + 'pi.io.sse.monitor.php?address=' + encodeURI(address));

      source.addEventListener('message',  callback, false);

      if (typeof onerror == "function") {
        source.addEventListener('error',  onerror,  false);
      }

      return source;
    };




    /** 
     * Listen to a data stream in the global namespace
     * 
     * @param  {string}     address   Address in the pi namespace to listen to
     * @param  {function}   onerror   Callback on error
     * @param  {function}   listener  Callback for stream data
     * 
     * @return {boolean}    Result of operation
     */

    π.readstream = function (address, listener, onerror) {
      if (!π.session._connected) {
        if (typeof onerror == "function") {
          onerror.call(this, "Error: No session in readstream().");
          return false;
        }
      }

      if (typeof listener == "function") {
        return π.session.addStreamListener(address, listener, onerror);
      }
      else {
        if (typeof onerror == "function") {
          onerror.call(this, "Error: Argument #2 is not a function in readstream().");
        }
        return false;
      }

    };




    /** 
     * Receive a data queue from the global namespace
     * 
     * @param  {string}     address   Address in the pi namespace to receive from
     * @param  {Function}   onerror   Callback on error
     * @param  {Function}   listener  Callback for data chunks
     * 
     * @return {Boolean}              Result of operation
     */

    π.readqueue = function (address, listener, onerror) {
      if (!π.session._connected) {
        if (typeof onerror == "function") {
          onerror.call(this, "Error: No session in readqueue().");
          return false;
        }
      }

      if (typeof listener == "function") {
        return π.session.addStreamListener(address, listener, onerror);
      }
      else {
        if (typeof onerror == "function") {
          onerror.call(this, "Error: No listener in readstream().");
        }
        return false;
      }

    };




  /**
   * Shorthand function, wraps window.addEventListener
   * 
   * @param  {string}   eventaddress The Pi Event Address to attach to
   * @param  {Function} callback     The event handler
   * @param  {bool}     capture      Capture events, or pass along to next handler in the event chain
   * 
   * @return {bool}     Result from addEventListener() 
   */
  
    π.on = function (eventaddress, callback, capture) {

      // if object, attach all functions by name
      if (typeof eventaddress == "object") {
        var count = 0;
        for (var func in eventaddress) {
          if (eventaddress.hasOwnProperty(func) && (typeof eventaddress[func] == "function")) {
            count++;
            π.on(func, eventaddress[func], callback, capture || false);
          }
        }
        return count;
      }

      if (eventaddress.indexOf('pi.') !== 0) {
        eventaddress = "pi." + eventaddress;
      }

      return window.addEventListener(eventaddress, callback, capture);
    };


    // ALIAS
    π.bind = π.on;


    /** 
     * Wait for single named event
     * 
     * @param  {string}     eventaddress  Address in the pi namespace to wait for
     * @param  {Function}   callback      Callback when return value available
     * 
     * @return {boolean}                  Should always return true
     */

    π.await = function (eventaddress, callback, timeout) {
      var
        eventaddress  = eventaddress  || false,
        timeout       = timeout       || π._const.DEFAULT_TIMEOUT;
      
      if (typeof eventaddress != "string") {
        return false;
      }

      if (eventaddress.substring(0,7) == 'pi.app.') {
        // await named event locally
        return π.events.subscribe(eventaddress, callback);
      }
      else {
        // request a named event from the server
        return π._send("await", eventaddress, timeout, callback);
      }
    };



    /** 
     * Read a remote value
     *
     * @param  {string}     address   Address in the pi namespace to read from
     * @param  {Function}   onerror   Callback on error
     * @param  {Function}   callback  Callback when return value available
     * 
     * @return {boolean}              Result if success, false if failure
     */

    π.read = function (address, callback, onerror) {
    
      return π._send("read", address, null, callback || false);
    };



    /** 
     * Write a value to a remote variable location
     * 
     * @param  {string}     address   Address in the pi namespace to write to
     * @param  {Function}   onerror   Callback on error
     * @param  {Function}   callback  Callback when return value available
     * 
     * @return {boolean}              Old value on success, false on failure (in callback)
     */

    π.write = function (address, value, callback) {

      return π._send("write", address, value, callback);
    };



    /**
     * Handle app request for sending a message to an address in the pi namespace
     * Conform to pi packet specification
     * 
     * @param  {string}     command   pi command to issue
     * @param  {string}     address   Address in the pi namespace to read from
     * @param  {object}     data      The data to send
     * @param  {Function}   callback  Callback when return value available
     * 
     * @return {boolean}              Result if success, false if failure
     */

    π._send = function (command, address, data, callback, onerror) {
      var
        packet = {
          command   : command, 
          address   : address, 
          data      : data
        };

        if (typeof callback == "function") {
          packet.callback = π.core.callback.add(callback);
        }

        if (π.session.connected) {
          // will return true or false
          return π.session.send(packet);
        }
        else {
          pi.log("pi.session not connected! Packet:", packet);
          return false;
        }
    };



    /** 
     *  List contents of remote address
     *
     * @param  {string}     address       [channel[:id]|]address
     * @param  {string}     filetype      The file extension
     * @param  {Function}   callback      Callback for each return value available
     * 
     * @return {string|boolean}           Data set on success, false on failure
     */

    π.readlist = function (address, callback, onerror) {

      var
        parameters = { address: address };

      if (typeof callback != "function") {
        pi.log("Error : callback is not a function in readlist().");
        if (typeof onerror == "function") {
          onerror.call(this, "callback is not a function in readlist().");
        }
        return false;
      }
    
      // TBC
      return π._send("list", address, parameters, callback, onerror);
    };




    /** 
     *  Read a remote data set (mysql|file|whatever)
     *
     * @param  {string}     address       Data address in the pi namespace
     * @param  {string}     filetype      The file extension
     * @param  {Function}   callback      Callback for each return value available
     * 
     * @return {string|boolean}           Data set on success, false on failure
     */

    π.readdata = function (address, callback, onerror) {

      var
        parameters = { address: address };

      if (typeof callback != "function") {
        pi.log("Error : callback is not a function in readdata().");
        if (typeof onerror == "function") {
          onerror.call(this, "callback is not a function in readdata().");
        }
        return false;
      }
    
      // TBC
      return π._send("data.list", address, parameters, callback, onerror);
    };



    /** 
     *  Read a remote (text) file
     * 
     * @param  {string}     fileaddress   File address in the pi namespace
     * @param  {string}     filetype      The file extension
     * @param  {Function}   callback      Callback for each return value available
     * 
     * @return {string|boolean}           File contents on success, false on failure
     */

    π.readfile = function (fileaddress, filetype, callback) {

      var
        parameters = { fileaddress: fileaddress, filetype: filetype };
    
      // TBC
      return π._send("file.read", address, parameters, callback);
    };



    /** 
     *  Basic dependency management 
     * 
     * @param  {string}     module    Name of the pi module to be loaded. If you give a path, 
     *                                you also have to give a complete filename, with extension. 
     *                                Otherwise, we assume a js module from root /assets
     *                                
     * @param  {boolean}    async     Load script asynchronously
     * @param  {boolean}    defer     Use deferred script loading
     * @param  {Function}   callback  Callback on loaded
     * 
     * @return {boolean}              True for success, false for failure
     *
     */

    π.require = function (module, async, defer, callback, onerror) {

      /* early escape  */
        var
          result = true;

            // handle multiple modules given on the form "module1 module2 ..."
            if (module.indexOf(" ") >=1) {
              var
                modules = module.split(" ");
              for (var i = 0; i < modules.length; i++) { 
                // this may seem redundant, but is needed to facilitate the use case where
                // every required module is already loaded. In that case, we want to return immediately.
                // (i.e., we don't catch errors)
                result &= π.require(modules[i], async, defer, null, onerror); 
              }
              if (result && typeof callback == "function") { 
                callback.call(this); 
              }
              return result;
            }

            // already loaded => early escape  |  NB: the regex is hardcoded, may occur otherwhere 
            if (π.loaded[module.replace(/\./g,'_')]) {
              if (typeof callback == "function") { 
                callback.call(this); 
              } 
              return true;
            }


      var 
        cursor  = document.getElementsByTagName("head")[0] || document.documentElement,

        /**
         * CUSTOM for this project ONLY 
         * 
         */
        path    = 'assets/js/pi.',
        // path    = '../../assets/js/pi.',

        script  = document.createElement('script');


      // defaults to FALSE
      script.async = async || false;
      script.defer = defer || false;

      // if you give a path, you also have to give a complete filename, with extension
      if (module.indexOf("/") >= 0) {
        script.src = module;
      }
      // otherwise, we assume a js module from root /assets 
      else {
        script.src = path + module + '.js';
      }

      script.modname  = module.replace(/\./g,'_');
      script.callback = callback  || false;
      script.onerror  = onerror   || π.log;


      π.timer.start(module);

      script.onload = function () {
        π.loaded[this.modname] = { time: (new Date()).getTime(), loadtime: π.timer.stop(this.modname) };
        if (typeof this.callback == "function") {
          this.callback.call(this, this.modname);
        }
      };

      return !!cursor.insertBefore(script, cursor.firstChild); 
    };




    /*
      core support modules
    */

    π.timer = {
      
      timers : {},


      start : function (timerid, ontick, interval) {

        var
          id          = timerid.replace(/\./g,'_'),
          timers      = π.timer.timers,
          self        = π.timer.timers[id]  || false,
          events      = π.events            || false,
          ontick      = ontick              || false,
          interval    = interval            || 1000,
          tickid      = false;

        if (self) {
          pi.log("Warning: starting timer " + timerid + " for a second time. Results unpredictable.");
        }

        if (typeof ontick == "function") {
          tickid = setInterval(ontick, interval);
        }


        timers[id] = { id : timerid, start : (new Date()).getTime(), tickid : tickid };

        if (typeof events.publish == "function") {
          events.publish("pi.timer." + timerid + ".start", {event: "start", data: timers[id]});
        }
        if (typeof console.time == "function") {
          /// console.time(id);
        }
      },


      check : function (timerid) {

        var
          id          = timerid.replace(/\./g,'_'),
          timers      = π.timer.__items,
          self        = π.timer.__items[id] || false,
          events      = π.events            || false,
          ontick      = ontick              || false,
          interval    = interval            || 1000,
          tickid      = false;


        if (self) {
          pi.log("Warning: starting timer " + timerid + " for a second time. Results unpredictable.");
        }

        if (typeof ontick == "function") {
          tickid = setInterval(ontick, interval);
        }


        timers[id] = { id : timerid, start : (new Date()).getTime(), tickid : tickid };

        if (typeof events.publish == "function") {
          events.publish("pi.timer." + timerid + ".tick", {event: "tick", data: timers[id]});
        }
      },


      stop : function (timerid) {

        var
          timers  = π.timer.timers,
          history = π.timer.history,
          self    = π.timer.timers[timerid.replace(/\./g,'_')] || false;

        // if (typeof console.timeEnd == "function") {
        //   /// console.timeEnd(timerid);
        // }

        if (!self) {
          // π.events.publish("pi.timer.items." + timerid, "Warning: stopping non-existent timer \"" + timerid + "\". Results unpredictable.");
          pi.log("Warning: stopping non-existent timer " + timerid + ". Results unpredictable.");
          return false;
        }

        // is there an attached tick handler ?
        if (self.tickid) {
          // if yes, clear tick interval
          clearInterval(self.tickid);
          self.tickid = false;
          self.ontick = null;
        }
        self.stop = (new Date()).getTime();

        self.time = self.stop - self.start;

        history.add(self);

        // return timer value
        return self.time;
      },


      history : {
        
        log   : [],

        add : function (obj) {
          π.timer.history.log.push(obj);
        },


        list  : function (callback) {
          var
            log = π.timer.history.log;


          log.forEach(function (value, index) {
            if (callback) {
              callback.call(index, value);
            }
            // pi.log("timer[" + value.id + "] : " + value.time + "ms.");
          });
        },


        clear : function () {
          var
            log = π.timer.history.log;

          π.events.publish("pi.timer.history.on", ["clear"]);

          // clear log array, this is actually the fastest way
          // NB! Array MUST NOT contain any falsy values, since that 
          // would break the loop before the array is cleared
          while (log.pop()) {};
        }
      } // end of history object

    }; // end of timer module




  /**
   * BOOTSTRAPPING THE DOM
   *
   * Search for ["data-src", "data-pi"] attributes starting with "pi."
   * Set classes based on found elements' pi address. ["pi.video" => addClass("pi video")]
   *
   * @todo Move to pi.html.js or similar, since the core should be DOM-independent
   */


  π.__BOOT = {
    strapped : [],
 
    /**
     * Check if element has already been strapped
     * 
     * @param  {HTMLElement} e The element to check
     * 
     * @return {bool|int}   Boolean FALSE if already strapped, index of newly inserted cache item if not
     */
    checkIt : function (e) {
      var
        self = π.__BOOT;
      if (self.strapped.indexOf(e) > -1) {
        // elem was already strapped
        return false;
      }
      else {
        // return new length of strapped array
        return self.strapped.push(e);
      }
    },

    /**
     * Strap elements with classes from the data-src attribute, if set
     * 
     * @param  {HTMLElement} e The element to strap
     * 
     * @return {void}
     */
    strapIt : function (e) {
      var
        ref = e.getAttribute("data-src");

      if (typeof ref == "string" && ref.indexOf("pi.") === 0) {
        pi.log("adding classes : " + ref.replace(".", " "));
        e.className += ( e.className ? " " + ref.replace(".", " ") : ref.replace(".", " ") );
      }
    }
  };


  /**
   * Bootstrapper for DOM elements
   *
   * @return {void}
   * 
   * @todo Move this to a DOM-related module, the core should be DOM-independent.
   */
  π._bootstrap = function () {
    π.timer.start("_bootstrap");
    var
      elements,
      body = document.documentElement;

    elements = document.getElementsByClassName("pi");
    if (!(elements && elements.length)) {
      // pi.log("No pi elements found.");
    }
    else {
      pi.log("Bootstrapping : " + elements.length + " " + (elements.length === 1 ? "element" : "elements"));
      for (var i = 0; i < elements.length; i++) {
        // pi.log("STRAP IT: " + e, e);
        if (π.__BOOT.checkIt(elements.item(i))) {
          π.__BOOT.strapIt(elements.item(i));
        }
      }
    }

    π.timer.stop("_bootstrap");
    pi.events.trigger("pi.ready");
  };

  document.addEventListener('DOMContentLoaded',π._bootstrap);

  /***   ------   INITIALIZATION    ------
     *
     */


  /**
   * HTML5-ify the Window object
   */

  window.requestAnimationFrame = 
      window.requestAnimationFrame || 
      window.webkitRequestAnimationFrame || 
      window.mozRequestAnimationFrame || 
      function (callback) {
        window.setTimeout(callback, 1000/60)
      };

  window.cancelAnimationFrame = 
      window.cancelAnimationFrame || 
      window.webkitCancelAnimationFrame || 
      window.mozCancelAnimationFrame || function (id) {
        window.clearTimeout(id)
      };


  /**
   * HTML5-ify the Array object
   */

  Array.prototype.forEach = Array.prototype.forEach || function(callback, thisArg) {
    for (var i = 0; i < this.length; i++) {
      // callback signature forEach : (p, i, arr)
      callback.call(thisArg || this, this[i], i, this);
    };
  };
   
  Array.prototype.map = Array.prototype.map || function(callback, thisArg) {
    var 
      result = [];

    for (var i = 0; i < this.length; i++) {
      // same callback signature as forEach : (p, i, arr)
      result.push(callback.call(thisArg || this, this[i], i, this));
    }
    return result;
  };
   

  /**
   * This does not strictly adhere to the spec. 
   * If initial is null, it should start at i = 1 and prevVal = this[0].
   * Instead, we start at i = 0 with prevVal = 0, which amounts to the same
   * thing, just with an extra function invocation.
   * 
   * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/Reduce
   */
  Array.prototype.reduce = Array.prototype.reduce || function(callback, initial) {
      var accumulated = initial || 0;
      for (var i = 0; i < this.length; i++) {
          // callback signature reduce : (prevVal, currVal, i, arr)
          accumulated = callback(accumulated, this[i], i, this);
      };
      return accumulated;
  };


  /**
   * from Dustin Diaz - http://dustindiaz.com/rock-solid-addevent
   */
    function addEvent( obj, type, fn) {

      if (obj.addEventListener) {
        obj.addEventListener( type, fn, false );
        EventCache.add(obj, type, fn);
      }
      else if (obj.attachEvent) {
        obj["e"+type+fn] = fn;
        obj[type+fn] = function () { obj["e"+type+fn]( window.event ); }
        obj.attachEvent( "on"+type, obj[type+fn] );
        EventCache.add(obj, type, fn);
      }
      else {
        obj["on"+type] = obj["e"+type+fn];
      }
    }

    var EventCache = function () {
      var listEvents = [];
      return {
        listEvents : listEvents,
        add : function (node, sEventName, fHandler) {
          listEvents.push(arguments);
        },

        flush : function () {
          var i, item;
          for (i = listEvents.length - 1; i >= 0; i = i - 1) {
            item = listEvents[i];
            if (item[0].removeEventListener) {
              item[0].removeEventListener(item[1], item[2], item[3]);
            };

            if (item[1].substring(0, 2) != "on") {
              item[1] = "on" + item[1];
            };

            if (item[0].detachEvent) {
              item[0].detachEvent(item[1], item[2]);
            };

            item[0][item[1]] = null;
          };
        }
      };
    }();

    addEvent(window,'unload',EventCache.flush);


    
   /**
    * Backfill function for pi.addEventListener
    * 
    * @param {HTMLElement}  elem    DOM Element to attach to, defaults to window
    * @param {string}       event   Event name to listen for
    * @param {Function}     fn      The callback function
    * 
    * @author   http://stackoverflow.com/users/816620/jfriend00
    */
    pi._addEvent = function (elem, event, fn) {
      // avoid memory overhead of new anonymous functions for every event handler that's installed
      // by using local functions
      function listenHandler(e) {
        var ret = fn.apply(this, arguments);
        if (ret === false) {
          e.stopPropagation();
          e.preventDefault();
        }
        return(ret);
      }

      function attachHandler() {
        // set the this pointer same as addEventListener when fn is called
        // and make sure the event is passed to the fn also so that works the same too
        var ret = fn.call(elem, window.event);   
        if (ret === false) {
          window.event.returnValue = false;
          window.event.cancelBubble = true;
        }
        return(ret);
      }

      if (elem.addEventListener) {
        elem.addEventListener(event, listenHandler, false);
      } else {
        elem.attachEvent("on" + event, attachHandler);
      }
    }



    /**
     * Shorthand function
     * 
     * @param {String}    eventaddress The Pi Address to listen to
     * @param {Function}  callback     Event handler
     * @param {bool}      capture      Whether or not to capture events
     * 
     */
    pi.addEventListener = function (eventaddress, callback, capture, c2) {
      var
        elem          = window,
        eventaddress  = eventaddress  || null,
        callback      = callback      || null,
        capture       = capture       || null;

      // handle alternative function signature (element, eventaddress, callback, capture)
      if (typeof eventaddress != "string" && typeof callback == "string") {
        elem          = eventaddress;
        eventaddress  = callback;
        callback      = capture;
        capture       = c2 || false;
      }

      if (!eventaddress.indexOf) {
        pi.log("ERROR: no indexOf, eventaddress is type " + typeof eventaddress);
        pi.log("eventaddress : " + eventaddress, eventaddress);
        pi.log("wrong parameters?");
        pi.log("correct: (eventaddress, callback, capture) OR (element, event, callback)");
        return;
      }

      // use internal event system for internal events
      if (eventaddress.indexOf('pi.')===0) {
        return pi.events.subscribe(eventaddress, callback);
      }
      else {
        // use cross-browser addEvent from StackOverflow
        return pi._addEvent(elem, eventaddress, callback);
      }
    };



    /**
     * CUSTOM VERSION OF pi.core.js FOR: //kreateam.io/html5/oya/
     */

  π.events.onWindowMessage = function (msg) {
    pi.log("onWindowMessage : " + msg, msg);
    pi.log("data : " + msg.data);
    pi.log("origin : " + msg.origin);
    pi.log("source : " + msg.source);
  }

  // window.addEventListener("message", π.events.onWindowMessage, false, true);




  /*    PHP aliases   */

  π.str_pad   = π.strPad;
  π.is_array  = π.isArray;


  // Heigh ho ..
  pi.events.trigger('pi.ready', new Date().getTime());