// Based on https://gist.github.com/Tarendai/3690149
(function() {
	tinymce.create('tinymce.plugins.glyphsTypekit', {
		init: function(ed, url) {
			ed.onPreInit.add(function(ed) {
				// Get the DOM document object for the IFRAME
				var doc = ed.getDoc();

				// Create the script we will add to the header asynchronously
				var jscript = "(function(d) {\n\
					var config = {\n\
						kitId: '" + GlyphsFontKit + "',\n\
						scriptTimeout: 3000\n\
					},\n\
					h=d.documentElement,\n\
					t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,'')+' wf-inactive';},\n\
					config.scriptTimeout),\n\
					tk=d.createElement('script'),\n\
					f=false,\n\
					s=d.getElementsByTagName('script')[0],\n\
					a;\n\
					h.className+=' wf-loading';\n\
					tk.src='//use.typekit.net/'+config.kitId+'.js';\n\
					tk.async=true;\n\
					tk.onload=tk.onreadystatechange=function(){a=this.readyState;\n\
					if(f||a&&a!='complete'&&a!='loaded')return;\n\
					f=true;\n\
					clearTimeout(t);\n\
					try{Typekit.load(config)}catch(e){}};\n\
					s.parentNode.insertBefore(tk,s);\n\
					console.log('test');\n\
				})(document);";

				// Create a script element and insert the TypeKit code into it
				var script = doc.createElement("script");
				script.type = "text/javascript";
				script.appendChild(doc.createTextNode(jscript));

				// Add the script to the header
				doc.getElementsByTagName('head')[0].appendChild(script);
			});
		},
		getInfo: function() {
			return {
				longname: 'TypeKit For TinyMCE',
				author: 'Tom J Nowell',
				authorurl: 'http://tomjn.com/',
				infourl: 'http://twitter.com/tarendai',
				version: "1.1"
			};
		}
	});
	tinymce.PluginManager.add('glyphsTypekit', tinymce.plugins.glyphsTypekit);
})();