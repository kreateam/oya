		/**
		 * Window error handler. Suppresses error messages, and re-routes to server.
		 * Aborts server reporting if localStorage is not available for counting number of errors.
		 * Stops reporting after counting 1000 errors.
		 * 
		 * NB! Should always be included directly after opening <head> tag
		 * 
		 * @param  {str} 	msg  	Error message
		 * @param  {str} 	url  	Url of the file
		 * @param  {int} 	line 	The line where the error occurred
		 * 
		 * @return {true} 			Always returns true, in order to suppress any visible error messages
		 */
		window.onerror = function(msg, url, line) {

			// trap any further errors
	    try {
		    var
		    	errorCount = null,

		    	/**
		    	 * Property	Description
						appCodeName	Returns the code name of the browser
						appName	Returns the name of the browser
						appVersion	Returns the version information of the browser
						cookieEnabled	Determines whether cookies are enabled in the browser
						geolocation	Returns a Geolocation object that can be used to locate the user's position
						language	Returns the language of the browser
						onLine	Determines whether the browser is online
						platform	Returns for which platform the browser is compiled
						product	Returns the engine name of the browser
						userAgent	Returns the user-agent header sent by the browser to the server
		    	 */

		    	data 	= { 
		    						navigator : { 
		    							userAgent 		: navigator.userAgent,
		    							appCodeName 	: navigator.appCodeName,
		    							appVersion 		: navigator.appVersion,
		    							cookieEnabled : navigator.cookieEnabled,
		    							language 			: navigator.language,
		    							product 			: navigator.product,
		    							platform 			: navigator.platform,
		    						}
		    					},

		    	xhr 	= new XMLHttpRequest(); // var


				if (typeof(Storage) != "undefined") {

					// returns null if non-existent
					errorCount = localStorage.getItem("errorCount");
					if (errorCount === null) {
						errorCount = 1;
					}
					else {

						// convert to integer, and increment
						errorCount = parseInt(errorCount, 10);
						errorCount++;
					}

					// update localStorage with current value
					localStorage.setItem("errorCount", errorCount);
				}

				if (errorCount === null) {

					console.error("No localStorage!");
					console.error("msg : " + msg);
					console.error("url : " + url);
					console.error("line : " + line);

					// don't send to server unless we know we are counting errors locally
					return true;
				}

				// collect relevant data
		    data.msg 	= msg;
		    data.url 	= url;
		    data.line = line;
		    data.location = window.location;

				console.error("Sending to server: " + msg, data);

		    // send it to the errorlogger
		    // we don't care if it gets there, that's not our problem
		    xhr.open('GET', "assets/php/errorlog.php?error=" + JSON.stringify(data), true);
				xhr.send();
	    }
	    catch(e) {

        // suppress any additional error
        // 
        // aaand maybe we should do a refresh...
        // that would have to be only if we know for certain that we can 
        // write to localStorage, otherwise infinite error loops would be possible
				console.error("error in window.onerror: " + e);
	    }

	    // and damn the torpedoes
	    return true;
		};
