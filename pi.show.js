

	var
		pi = pi || {};


	pi.show = pi.show || {

		render : function (template, data, partials) {

		},

		getNextScreen : function (url) {
			var
				data = pi.xhr.get(url);
			if (data) {
				return data;
			}
			else {
				return false;
			}
		}

	}
 f