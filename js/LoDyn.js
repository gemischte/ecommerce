console.log('LoDyn');

const LoDyn				= {};

LoDyn.output			= {};
LoDyn.ajax_callbacks	= [];
LoDyn.requests			= {};
LoDyn.attr				= {
	route:				'route',
	event:				'event',
	delay:				'delay',
	data:				'data',
	method:				'method',
	output:				'output',
	truncate:			'truncate',
	deeplink:			'deeplink',
	confirm:			'confirm',
	filter:				'filter',
	filter_feedback:	'filterFeedback',
	on_success:			'onSuccess',
	on_failure:			'onFailure',
	on_filter_failure:	'onFilterFailure',
};
LoDyn.serialize_filter	= [
	LoDyn.attr.event,
	LoDyn.attr.on_success,
	LoDyn.attr.on_filter_failure,
	LoDyn.attr.on_failure,
	LoDyn.attr.filter,
	LoDyn.attr.deeplink
];
LoDyn.events	= {
	success:		'success',
	failure:		'failure',
	filter_failure:	'filterFailure',
};
LoDyn.filter_flags	= {
	feedback:	1 << 0,
	focus:		1 << 1,
};
LoDyn.reflow_flags	= {
	truncate:	1 << 0,
	fadeout:	1 << 1,
	flowout:	1 << 2,
	flowin:		1 << 3,
	remove:		1 << 4,
};
LoDyn.headers	= [];
LoDyn.origins		= [];
LoDyn.domain_regex	= /^(?:https?:\/\/)?(?:[^@\n]+@)?(?:(?:www|cdn|nj)\.)?([^:\/\n?]+)/;
LoDyn.options	= {
	overlay: {
		close_click: true,		//close overlay by clicking on the shading
		close_key: 27,			//close overlay by pressing escape; vk|null
		background: true,		//put a background behind the content
		fadein: true,			//fade in the overlay
		slide: 'right',			//sliding directing; right|left|top|bottom|none
	},
	data: {
		separator: ';',			//separator for data entries
	},
	reflow: {
		reflow_x: true,
		reflow_y: true,
		expand_x: true,
		expand_y: true,
		fadein: true,
		fadeout: true,
	},
	xss: {
		verify_origin: true,
	},
	filter: {
		feedback: true,			//check data regex filters onInput and add valid/invalid class; works on input and textarea
	},
	load_cursor: true,			//Set cursor to progress on body during http requests
};

class LoDynData
{
	constructor(str)
	{
		let name;
		let value;
		let regex;
		let flags;
		let len;

		[name, value]	= str.split(/:(.+)/).map(s => s.trim());

		if(value !== undefined)
			[value, regex]	= value.split(/=(.+)/).map(s => s.trim());

		if(name.length < 1)
			return;

		if(value === undefined)
		{
			value		= name;
			name		= name.substr(1);
		}

		this.name	= name;
		this.val	= value;

		if(regex !== undefined)
		{
			len				= regex.lastIndexOf('/');
			flags			= regex.substr(len + 1, regex.length - len + 1);
			regex			= regex.substr(1, len - 1);
			this.regex		= new RegExp(regex, flags);
		}
	}

	/**
	 *
	 * @returns {Element | *}
	 */
	get target()
	{
		if(this.val[0] !== '#')
			return null;

		return document.querySelector(this.val);
	}

	/**
	 *
	 * @returns {string | null}
	 */
	get value()
	{
		let value;

		value	= this.val;

		if(value[0] === '#')
		{
			let target;

			target				= document.querySelector(value);

			if(target === null)
				return null;

			if(target.type === 'checkbox' && !target.checked)
				return null;

			value	= target.value !== undefined ? target.value : target.innerHTML;
		}

		return value;
	}

	/**
	 * @param {string | null}	v
	 */
	set value(v)
	{
		this.val	= v;
	}

}

class LoDynElement extends HTMLElement
{
	constructor()
	{
		super();

		this.timeout			= null;
		this.eventListeners		= []; //handlers for events on target
		this.eventHandlers		= {}; //handlers for custom events on this
		this.feedbackHandlers	= []; //handlers for feedback events on target
		this.LoDyn				= {};

		//init events defined in html
		LoDynElement.init_event(this);

		setTimeout(e => {

			LoDynElement.init_feedback(this);

			//init callbacks defined in html
			for(let property in LoDyn.events)
			{
				let attr;

				if(!LoDyn.events.hasOwnProperty(property))
					continue;

				attr	= `on${LoDyn.events[property].charAt(0).toUpperCase() + LoDyn.events[property].slice(1)}`;

				if(this.hasAttribute(attr))
					this[attr]	= this.getAttribute(attr);
			}
		}, 0);

	}

	/**
	 * @param {LoDynElement}	_this
	 */
	static init_event(_this)
	{
		if(!_this.hasAttribute(LoDyn.attr.event))
			return;

		let events;

		events	= _this.getAttribute(LoDyn.attr.event).split(';');

		for(const v of Object.values(events))
		{
			let 	target;
			let 	event;
			let 	listener;

			[event, target]		= v.split(':').map(s => s.trim());

			if(target === undefined)
				throw `Invalid ${LoDyn.attr.event} definition`;

			switch(event)
			{
				case 'interval':
					setInterval((e) => LoDyn.handle(e, _this), target);
					break;
				case 'return':
					target	= document.querySelector(target.trim());

					if(target === null)
						throw `Element ${target} not found`;

					if(target.tagName.toLowerCase() !== 'input')
						throw `Element ${target} is not an input`;

					listener = e => { if(e.keyCode === 0x0D) LoDyn.handle(e, _this); };
					_this.pushEventListener(target, 'keyup', listener);
					target.addEventListener('keyup', listener);
					break;
				default:
					target	= document.querySelector(target);

					if(target === null)
						throw `Element ${target} not found`;

					listener = e => LoDyn.handle(e, _this);
					_this.pushEventListener(target, event, listener);
					target.addEventListener(event, listener);
			}

			console.log(`Added event listener for ${event} to #${target.id}`);
		}
	}

	static init_feedback(_this)
	{
		if(_this.feedbackHandlers.length > 0)
		{
			for(let i = 0, v; v = _this.feedbackHandlers[i]; i++)
			{
				v.target.removeEventListener('input', v.cb);

				console.log(`Removed event listener for input filter to #${v.target.id}`);
			}

			for(;_this.feedbackHandlers.length > 0;)
				_this.feedbackHandlers.pop();
		}

		if(LoDyn.options.filter.feedback && _this.filterFeedback === 'true' && _this.dataObjects)
		{
			let data;

			data	= _this.dataObjects;

			for(let i = 0, v; v = data[i]; i++)
			{
				let cb;

				if(!v.target || v.regex === undefined || v.target.onFilter !== undefined)
					continue;

				cb	= e => {
					LoDyn.check_filters(_this, LoDyn.filter_flags.feedback, e);
				};

				v.target.addEventListener('input', cb);
				v.target.onFilter	= cb;

				_this.feedbackHandlers.push({target: v.target, cb: cb});

				console.log(`Added event listener for input filter to #${v.target.id}`);
			}
		}
	}

	/**
	 * @param 	{string}	str
	 * @param	{array}		except
	 * @returns {Element|*}
	 */
	static unserialize(str, except = [])
	{
		let obj;

		obj		= JSON.parse(str);

		for(const key of Object.keys(obj))
		{
			if(except.indexOf(key) !== -1)
				delete obj[key];
		}

		return LoDyn.createElement(obj ,false);
	}

	/**
	 * @param	{array}		except		the attributes not to serialize
	 *
	 * @returns {string}
	 */
	serialize(except = [])
	{
		let obj		= {};

		for(const key of Object.keys(LoDyn.attr))
		{
			let attr;

			attr	= LoDyn.attr[key];

			if(except.indexOf(attr) !== -1)
				continue;

			if(!this.hasAttribute(attr))
				continue;

			obj[LoDyn.attr[key]]	= this.getAttribute(LoDyn.attr[key]);
		}

		return JSON.stringify(obj);
	}

	/**
	 *
	 * @param target
	 * @param type
	 * @param listener
	 */
	pushEventListener(target, type, listener){
		this.eventListeners.push({
			target,
			type,
			listener
		});
	}

	/**
	 *
	 */
	removeEventListeners(){
		for(let i = 0, v; v = this.eventListeners[i]; i++){
			v.target.removeEventListener(v.type, v.listener);
			console.log(`Removed event listener ${v.type} for #${v.target.id}`);
		}

		for(let i = 0, v; v = this.feedbackHandlers[i]; i++){
			v.target.removeEventListener('input', v.listener);
			console.log(`Removed event listener ${v.type} for #${v.target.id}`);
		}
	}

	/**
	 * @returns {undefined}
	 */
	fire()
	{
		return LoDyn._handle(this);
	}

	/**
	 * @returns {string | null}
	 */
	get event()
	{
		return this.getAttribute(LoDyn.attr.event);
	}

	/**
	 * @returns {string | null}
	 */
	get eventType()
	{
		let type;
		let target;

		if(typeof this.event !== 'string')
			return null;

		[type, target]	= this.event.split(':');

		return type;
	}

	/**
	 * @returns {Element | *}
	 */
	get eventTarget()
	{
		let type;
		let target;

		if(typeof this.event !== 'string')
			return null;

		[type, target]	= this.event.split(':');

		return document.querySelector(target);
	}

	/**
	 * @param {string | null}	v
	 */
	set event(v)
	{
		this.setAttribute(LoDyn.attr.event, v);
		LoDynElement.init_event(this);
	}

	/**
	 * @returns {string | null}
	 */
	get route()
	{
		return this.getAttribute(LoDyn.attr.route);
	}

	/**
	 * @param {string | null}	v
	 */
	set route(v)
	{
		this.setAttribute(LoDyn.attr.route, v);
	}

	/**
	 * @returns {string | null}
	 */
	get method()
	{
		return this.getAttribute(LoDyn.attr.method) || 'GET';
	}

	/**
	 * @param {string | null}	v
	 */
	set method(v)
	{
		this.setAttribute(LoDyn.attr.method, v);
	}

	/**
	 * @returns {string | null}
	 */
	get data()
	{
		return this.getAttribute(LoDyn.attr.data);
	}

	/**
	 *
	 * @returns {LoDynData[]}
	 */
	get dataObjects()
	{
		if(this.LoDyn.dataObjects === undefined && this.data !== undefined)
			this.data	= this.data;
		return this.LoDyn.dataObjects;
	}

	/**
	 * @param {string | null}	v
	 */
	set data(v)
	{
		let entries;
		let objs;

		if(v !== null)
		{
			entries		= v.split(new RegExp(`${LoDyn.options.data.separator}(?![^${LoDyn.options.data.separator}:\/]*\/)`, 'gi')).map(s => s.trim());
			objs		= [];

			for(let i = 0, v; v = entries[i]; i++)
				objs.push(new LoDynData(v));

			this.LoDyn.dataObjects	= objs;

			this.setAttribute(LoDyn.attr.data, v);
		}
		else
			this.setAttribute(LoDyn.attr.data, '');

		LoDynElement.init_feedback(this);
	}

	/**
	 * @returns {string | null}
	 */
	get output()
	{
		return this.getAttribute(LoDyn.attr.output);
	}

	/**
	 * @returns {string | undefined}
	 */
	get outputType()
	{
		if(this.LoDyn.outputType === undefined && this.output !== undefined)
			this.output	= this.output;
		return this.LoDyn.outputType;
	}

	/**
	 * @returns {Element | null | *}
	 */
	get outputTarget()
	{
		if(this.LoDyn.outputTarget === undefined && this.output !== undefined)
			this.output	= this.output;
		return this.LoDyn.outputTarget;
	}

	/**
	 * @param {string | null}	v
	 */
	set output(v)
	{
		let type;
		let target;

		this.setAttribute(LoDyn.attr.output, v);

		if(typeof v !== 'string')
			return;

		[type, target]	= v.split(':');

		this.LoDyn.outputType		= type;
		this.LoDyn.outputTarget		= document.querySelector(target);
	}

	/**
	 *
	 * @returns {string | null}
	 */
	get truncate()
	{
		return this.getAttribute(LoDyn.attr.truncate) || 'output';
	}

	/**
	 *
	 * @param {string | null}	v
	 */
	set truncate(v)
	{
		this.setAttribute(LoDyn.attr.truncate, v);
	}

	get deeplink()
	{
		return this.getAttribute(LoDyn.attr.deeplink);
	}

	set deeplink(v)
	{
		this.setAttribute(LoDyn.attr.deeplink, v);
	}

	get confirm()
	{
		return this.getAttribute(LoDyn.attr.confirm);
	}

	set confirm(v)
	{
		this.setAttribute(LoDyn.attr.confirm, v);
	}

	/**
	 * @returns {*}
	 */
	get filter()
	{
		return LoDyn.eval(this.getAttribute(LoDyn.attr.filter));
	}

	/**
	 * @param {string | null}	v
	 */
	set filter(v)
	{
		this.setAttribute(LoDyn.attr.filter, v);
	}

	/**
	 * @returns {string | null}
	 */
	get filterFeedback()
	{
		return this.getAttribute(LoDyn.attr.filterFeedback) || 'true';
	}

	/**
	 * @param {string | null}	v
	 */
	set filterFeedback(v)
	{
		this.setAttribute(LoDyn.attr.filterFeedback, v);
		LoDynElement.init_feedback(this);
	}

	/**
	 * @returns {*}
	 */
	get onSuccess()
	{
		return this.eventHandlers.onSuccess;
	}

	/**
	 * @param {function | string | *}	v
	 */
	set onSuccess(v)
	{

		if(this.eventHandlers.onSuccess !== undefined)
			this.removeEventListener(LoDyn.events.success, this.eventHandlers.onSuccess);

		v								= LoDyn.eval(v);
		this.eventHandlers.onSuccess	= v;

		this.addEventListener(LoDyn.events.success, v);
	}

	/**
	 * @returns {*}
	 */
	get onFailure()
	{
		return this.eventHandlers.onFailure;
	}

	/**
	 * @param {function | string | *}	v
	 */
	set onFailure(v)
	{
		if(this.eventHandlers.onFailure !== undefined)
			this.removeEventListener(LoDyn.events.failure, this.eventHandlers.onFailure);

		v								= LoDyn.eval(v);
		this.eventHandlers.onFailure	= v;

		this.addEventListener(LoDyn.events.failure, v);
	}

	/**
	 * @returns {*}
	 */
	get onFilterFailure()
	{
		return this.eventHandlers.onFilterFailure;
	}

	/**
	 * @param {function | string | *}	v
	 */
	set onFilterFailure(v)
	{
		if(this.eventHandlers.onFilterFailure !== undefined)
			this.removeEventListener(LoDyn.events.filter_failure, this.eventHandlers.onFilterFailure);

		v									= LoDyn.eval(v);
		this.eventHandlers.onFilterFailure	= v;

		this.addEventListener(LoDyn.events.filter_failure, v);
	}
}

/**
 * Initiator
 */
LoDyn.init	= () =>
{
	//self closing tag fix
	//disabled because it messes up autofocus
	//document.body.innerHTML	= document.body.innerHTML.replace(/(<([A-Z][A-Z0-9]*-[A-Z0-9]*)[\s]*[^>]*?(?:(?:(['"])[^'"]*?\3)[^>]*?)*)\/?>(?!<\/\2)/gi, '$1></$2>');

	window.customElements.define('lo-dyn', LoDynElement);

	//for some reason autofocus is not working correctly (again...)
	let autofocus = document.querySelector('[autofocus]');

	if(autofocus)
		autofocus.focus();

	let state_handler = e => {
		try
		{
			let h	= window.location.href.indexOf('#');

			if(h > 0)
				LoDynElement.unserialize(atob(window.location.href.substr(h + 1)), LoDyn.serialize_filter).fire();
		}
		catch(exception)
		{
			console.log(`State handler exception: ${exception.toString()}`);
		}
	};

	window.addEventListener('popstate', state_handler);

	setTimeout(state_handler, 0);
};

/**
 * Create a lo-dyn element
 *
 * @param 	{Object}	attr		object of attribute pairs
 * @param 	{boolean}	append	append element to body
 *
 * @returns {Element | *}
 */
LoDyn.createElement		= (attr, append = true) =>
{
	const e	= document.createElement('lo-dyn');

	Object.keys(attr).forEach(prop => {
		if(Object.values(LoDyn.attr).some(v => v === prop))
			e[prop] = attr[prop];
	});

	if(append)
		document.body.appendChild(e);

	return e;
};

/**
 * @param {function | string}	v
 * @returns {function | null}
 */
LoDyn.eval		= v =>
{
	if(typeof v === 'function')
		return v;

	if(typeof v === 'string')
	{
		let fn;

		fn		= Function('"use strict";return (' + v + ')')();

		if(typeof fn === 'function')
			return fn;
	}

	return null;
};

/**
 * Set LoDyn options
 *
 * @param {Object}	o		options
 */
LoDyn.setopt		= o =>
{
	let deep_ass;

	deep_ass	= (x, y) => {
		for(let property in y)
		{
			if(!y.hasOwnProperty(property) || !x.hasOwnProperty(property) || typeof y[property] !== 'object' || typeof x[property] !== 'object')
				continue;

			x[property]	= deep_ass(x[property], y[property]);

			delete y[property];
		}

		return Object.assign(x, y);
	};

	deep_ass(LoDyn.options, o);
};

/**
 * @param 	{function}		fn		callback
 */
LoDyn.ajax_callback	= fn =>
{
	LoDyn.ajax_callbacks.push(fn);
};

/**
 * @param 	{string}		key
 * @param 	{string}		val
 */
LoDyn.push_header = (key, val) =>
{
	LoDyn.headers.push({key: key, val: val});
};

LoDyn.push_origin = origin =>
{
	LoDyn.origins.push(origin.match(LoDyn.domain_regex)[1]);
};

LoDyn.push_origin(document.currentScript.src);

/**
 * @param 	{LoDynElement}		c			context
 * @param 	{integer}			flags
 * @param	{HTMLElement|null}	e
 *
 * @returns {boolean}
 */
LoDyn.check_filters	= (c, flags, e = null) =>
{
	let	ret;
	let data;
	let focus;

	if(c.filter !== null)
	{
		if(!c.filter(c))
		{
			if(flags & LoDyn.filter_flags.feedback)
				LoDyn.set_valid(c.eventTarget, false);

			return false;
		}

		if(flags & LoDyn.filter_flags.feedback)
			LoDyn.set_valid(c.eventTarget, true);
	}

	ret		= true;
	data	= c.dataObjects;
	focus	= false;

	for(let i = 0, v; v = data[i]; i++)
	{
		if(!v.target || (e !== null && e.target !== v.target))
			continue;

		if(v.regex !== undefined)
		{
			if(!v.regex.test(v.value))
			{
				if(flags & LoDyn.filter_flags.feedback)
					LoDyn.set_valid(v.target, false);

				if(!focus && (flags & LoDyn.filter_flags.focus))
				{
					focus	= true;
					v.target.focus();
				}

				ret	= false;
				continue;
			}
		}

		if(flags & LoDyn.filter_flags.feedback)
			LoDyn.set_valid(v.target, true);
	}

	return ret;
};

LoDyn.verify_origin	= (route) =>
{
	if(!LoDyn.options.xss.verify_origin)
		return route;

	let		domain;

	domain	= route.match(LoDyn.domain_regex);

	if(domain !== null && domain.length > 1)	//route contains a domain
	{
		domain	= domain[1];

		if(LoDyn.origins.indexOf(domain) === -1)
			throw `Loading resources from ${domain} is not allowed.`;
	}

	return route;
};

/**
 * @param	{LoDynElement}		c		context
 * @returns	{{}}
 */
LoDyn.parse_data	= c =>
{
	let ret;

	ret		= {};

	for(let i = 0, v; v = c.dataObjects[i]; i++)
		ret[v.name]		= v.value;

	return ret;
};

/**
 * Generate a random id
 *
 * @returns {string}
 */
LoDyn.generate_id	= () =>
{
	return 'i' + Math.random().toString(0x20).substr(2, 10);
};

/**
 * @param 	{Element}			e		target element
 * @param 	{boolean}			b		bool valid/invalid
 */
LoDyn.set_valid		= (e, b) =>
{
	if(!b)
	{
		e.classList.remove('valid');
		e.classList.add('invalid');
		return;
	}

	e.classList.remove('invalid');
	e.classList.add('valid');
};

/**
 * @param 	{string|Element}	e
 *
 * @returns {Element|null}
 */
LoDyn.el	= e =>
{
	if(typeof e === 'string' || e instanceof String)
		e	= document.querySelector(e);

	return e;
};

/**
 * @param 	{string|Element}	e
 *
 * @returns {Element|null}
 */
LoDyn.ela	= e =>
{
	if(typeof e === 'string' || e instanceof String)
		e	= document.querySelectorAll(e);

	return e;
};

/**
 * Handle triggered event after delay
 *
 * @param 	{Event}			e		event
 * @param 	{LoDynElement}	c		context
 */
LoDyn.handle		= (e, c) =>
{
	let delay;

	e.stopPropagation();
	delay	= c.getAttribute(LoDyn.attr.delay);

	if(delay === null)
	{

		if(c.confirm)
		{
			LoDyn.handle_confirm(c);
			return;
		}
		else
		{
			LoDyn._handle(c);
			return;
		}
	}

	if(c.timeout !== null)
		clearTimeout(c.timeout);

	c.timeout	= setTimeout(() => {
		LoDyn._handle(c);
		c.timeout	= null;
	}, delay);
};

/**
 * @param	{string|Element}	el
 */
LoDyn.close_confirm		= el =>
{
	let bg;

	el	= LoDyn.el(el);

	if(el === null)
		return;

	bg	= el.parentElement;

	if(bg !== null && bg.classList.contains('modal'))
	{
		el.parentElement.classList.add('fade-out');

		el.parentElement.addEventListener('animationend', e => {
			e.stopPropagation();

			bg.remove();
		});
	}
	LoDyn.remove(el);
};

/**
 * @param	{LoDynElement}		c		context
 */
LoDyn.handle_confirm	= c =>
{
	let id 			= LoDyn.generate_id();
	let frag		= document.createDocumentFragment();
	let modal		= {};

	modal.bg		= document.createElement('div');
	modal.reflow	= document.createElement('div');
	modal.dialog	= document.createElement('div');
	modal.content	= document.createElement('div');
	modal.header	= document.createElement('div');
	modal.footer	= document.createElement('div');
	modal.title		= document.createElement('h5');
	modal.confirm	= document.createElement('button');
	modal.close		= document.createElement('button');

	modal.bg.classList.add('modal', 'd-block', 'fade-new');
	modal.reflow.classList.add('reflow', 'reflow-hidden');
	modal.dialog.classList.add('modal-dialog');
	modal.content.classList.add('modal-content');
	modal.header.classList.add('modal-header');
	modal.footer.classList.add('modal-footer');
	modal.title.classList.add('modal-title');
	modal.confirm.classList.add('btn', 'btn-primary');
	modal.close.classList.add('btn', 'btn-secondary');

	modal.bg.id			= id;
	modal.confirm.id	= 'confirm';
	modal.close.id		= 'close';
	modal.confirm.type	= 'button';
	modal.close.type	= 'button';

	modal.title.innerText	= c.confirm;
	modal.confirm.innerText	= 'Confirm';
	modal.close.innerText	= 'Close';

	frag.appendChild(modal.bg);
	modal.bg.appendChild(modal.reflow);
	modal.reflow.appendChild(modal.dialog);
	modal.dialog.appendChild(modal.content);
	modal.content.appendChild(modal.header);
	modal.content.appendChild(modal.footer);
	modal.header.appendChild(modal.title);
	modal.footer.appendChild(modal.confirm);
	modal.footer.appendChild(modal.close);
	document.body.appendChild(frag);

	LoDyn.el(`#${id} button#confirm`).addEventListener('click', e => { LoDyn._handle(c); LoDyn.close_confirm(`#${id} > div`); });
	LoDyn.el(`#${id} button#close`).addEventListener('click', e => { LoDyn.close_confirm(`#${id} > div`); });

	requestAnimationFrame(() => {
		LoDyn.show(`#${id} > div`);
	});


};

/**
 * Handle the triggered event
 *
 * @param 	{LoDynElement}	c		context
 */
LoDyn._handle		= (c) =>
{
	let req;
	let route;
	let data;
	let headers;
	let reqid;

	headers	= LoDyn.headers;

	if(!LoDyn.check_filters(c, LoDyn.filter_flags.focus))
	{
		event	= new CustomEvent(LoDyn.events.filter_failure, { detail: {
			context: c,
		}});

		c.dispatchEvent(event);
		return;
	}

	route		= LoDyn.verify_origin(c.route);
	data		= null;
	req			= new XMLHttpRequest();

	if(c.data)
	{
		let	params	= [];

		data		= LoDyn.parse_data(c);

		for(let property in data)
		{
			if(!data.hasOwnProperty(property))
				continue;

			params.push(`${property}=${data[property]}`);
		}

		params	= params.join('&');

		switch(c.method)
		{
			case 'POST':
			case 'post':
				headers.push({key: 'Content-Type', val: 'application/x-www-form-urlencoded'});
				data	= params;
				break;

			case 'GET':
			case 'get':
				route	+= `?${params}`;

			default: //fall-through not an error!
				data	= null;
		}
	}

	reqid						= LoDyn.generate_id();

	req.addEventListener('load', () => {
		let event;
		let eventCtx;

		if(req.readyState !== 4)
			return;

		delete LoDyn.requests[reqid];

		if(LoDyn.options.load_cursor && Object.keys(LoDyn.requests).length === 0)
			//document.body.style.cursor	= LoDyn._handle.cursor;
			document.body.classList.remove('loading');

		for(let i = 0, v; v = LoDyn.ajax_callbacks[i]; i++)
			v(req);

		if(c.output && req.status === 200)
			LoDyn.output.handle(c, req.response);

		eventCtx	= { detail: {
			request: req,
			context: c,
		}};

		event	= new CustomEvent(req.status !== 200 ? LoDyn.events.failure : LoDyn.events.success, eventCtx);

		c.dispatchEvent(event);

	});

	req.open(c.method, route, true);

	for(let i = 0, v; v = headers[i]; i++)
		req.setRequestHeader(v.key, v.val);

	req.send(data);

	LoDyn.requests[reqid]		= req;

	if(LoDyn.options.load_cursor)
	{
		// LoDyn._handle.cursor		= LoDyn._handle.cursor || document.body.style.cursor || 'auto';
		// document.body.style.cursor	= "progress";
		document.body.classList.add('loading');
	}

	if(c.deeplink === 'on')
	{
		let route;

		route	= window.location.href.substr(0, window.location.href.indexOf('#'));
		route	+= "#" + btoa(c.serialize(LoDyn.serialize_filter));

		window.history.pushState(LoDyn.generate_id(), 'IGNORED', route);
	}
};

/**
 * Output types:
 * 	- raw		Replace/Append raw response
 * 	- table		Create/Update a table
 * 	- ttable	Create/Update a transposed table
 * 	- overlay	Create an overlay (target not required)
 *
 * @param 	{LoDynElement}	c		context
 * @param 	{string}		r		response
 */
LoDyn.output.handle	= (c, r) =>
{
	switch(c.outputType)
	{
		case 'raw':
			LoDyn.output.raw(c.outputTarget, r, c.truncate);
			break;
		case 'table':
			LoDyn.output.table(c.outputTarget, r);
			break;
		case 'ttable':
			LoDyn.output.ttable(c.outputTarget, r);
			break;
		case 'overlay':
			LoDyn.output.overlay(r, c);
			break;
		default:
			throw `Unknown output type ${c.outputType}`;
	}
};

/**
 * Raw output
 *
 * @param 	{Element}		t		target
 * @param 	{string}		r		response
 * @param 	{string}		m		truncate mode
 */
LoDyn.output.raw	= (t, r, m = 'output') =>
{
	let template;
	let arr;
	let flags;

	if(t === null)
		return;

	flags				= LoDyn.reflow_flags.fadeout;

	arr					= [];
	template 			= document.createElement('template');
	template.innerHTML 	= r.trim();
	for(let i = 0, v; v = template.content.children[i]; i++)
		arr.push(v.cloneNode(true));

	if(m === 'output')
		flags	|= LoDyn.reflow_flags.truncate;

	LoDyn.reflow(t, arr, flags);

};

/**
 * Create an overlay
 *
 * @param 	{string}		r		response
 * @param 	{LoDynElement}	c		context
 */
LoDyn.output.overlay	= (r, c = null) =>
{
	let 	shade;
	let 	content;
	let 	container;
	let 	onkeyup;
	let 	overflow;
	let 	shadeid;
	let 	x;
	let 	span;

	const 	remove = (e) =>
	{
		shade.classList.add('fade-out');

		shade.addEventListener('animationend', function transitionEnd() {
			//document.body.style.overflow	= overflow;

			document.removeEventListener('keyup', onkeyup);
			shade.remove();
		}, false);

		if(c)
			c.eventTarget.removeAttribute('disabled');

		e.stopPropagation();
	};

	shade				= document.createElement('div');
	shadeid				= LoDyn.generate_id();
	shade.id			= shadeid;
	content				= document.createElement('div');
	container			= document.createElement('div');
	content.innerHTML	= r;
	x					= document.createElement('div');
	span				= document.createElement('span');
	span.innerText		= 'X';

	x.classList.add('lodyn-overlay-close');
	x.appendChild(span);
	container.classList.add('lodyn-overlay-container');
	content.classList.add('lodyn-overlay-content');

	if(LoDyn.options.overlay.background)
		content.classList.add('lodyn-overlay-background');

	if(LoDyn.options.overlay.slide !== 'none')
		shade.classList.add(`lodyn-overlay-slide-${LoDyn.options.overlay.slide[0]}`);

	if(LoDyn.options.overlay.fadein === true)
		shade.classList.add('fade-new');

	shade.classList.add('lodyn-overlay');
	shade.appendChild(x);
	container.appendChild(content);
	shade.appendChild(container);

	x.addEventListener('click', remove);

	if(LoDyn.options.overlay.close_click)
	{
		container.style.cursor	= 'pointer';

		container.addEventListener('click', e => {
			if(e.target.parentNode.id !== shadeid)
				return;

			remove(e);
		});
	}

	if(LoDyn.options.overlay.close_key !== null)
	{
		onkeyup	= e => {
			if(e.keyCode !== LoDyn.options.overlay.close_key)
				return;

			remove(e);
		};
		document.addEventListener('keyup', onkeyup);
	}

	overflow						= document.body.style.overflow;
	//document.body.style.overflow	= 'hidden';

	document.body.appendChild(shade);
	//loader.reinit(`#${shadeid}`);

	if(c)
		c.eventTarget.setAttribute('disabled', '');
	c.eventTarget.blur();
};

/**
 * Creates/Updates a table
 * Expects response to be json
 * Always truncates on output
 *
 * @param {Element}			t		target
 * @param {string | array}	r		response
 */
LoDyn.output.table	= (t, r) =>
{
	let keys;
	let tbody;

	if(t === null)
		return;

	if(typeof r === 'string')
	{
		try
		{
			r			= JSON.parse(r);
		}
		catch(t)
		{
			console.log(`JSON invalid: ${r}`);
			return;
		}
	}

	if(!Array.isArray(r) || !r.length)
		return;

	keys 	= Object.keys(r[0]);

	if(!keys.length)
		return;

	tbody			= document.createElement('tbody');

	for(let i = 0, key; key = keys[i]; i++)
	{
		let tr;
		let th;

		tr				= document.createElement('tr');
		th				= document.createElement('th');
		th.innerText	= keys[i];

		tr.appendChild(th);

		for(let j = 0; j < r.length; j++)
		{
			let td;

			td				= document.createElement('td');
			td.innerText	= r[j][key];

			tr.appendChild(td);
		}

		tbody.appendChild(tr);
	}

	LoDyn.reflow(t, [tbody]);
};

/**
 * Creates/Updates a transposed table
 * Expects response to be json
 * Always truncates on output
 *
 * @param {Element}			t		target
 * @param {string | array}	r		response
 */
LoDyn.output.ttable	= (t, r) =>
{
	let keys;
	let tr;
	let thead;
	let tbody;

	if(t === null)
		return;

	if(typeof r === 'string')
	{
		try
		{
			r			= JSON.parse(r);
		}
		catch(e)
		{
			console.log("JSON Invalid: " + r);
			return;
		}
	}

	if(!Array.isArray(r) || !r.length)
		return;

	keys 	= Object.keys(r[0]);

	if(!keys.length)
		return;

	thead	= document.createElement('thead');
	tr		= document.createElement('tr');

	for(let i = 0, v; v = keys[i]; i++)
	{
		let th;

		th				= document.createElement('th');
		th.innerText	= v;

		tr.appendChild(th);
	}

	thead.appendChild(tr);

	tbody			= document.createElement('tbody');

	for(let i = 0, row; row = r[i]; i++)
	{
		tr		= document.createElement('tr');

		for(let j = 0; j < keys.length; j++)
		{
			let td;

			td				= document.createElement('td');
			td.innerText	= row[keys[j]];

			tr.appendChild(td);
		}

		tbody.appendChild(tr);
	}

	LoDyn.reflow(t, [thead, tbody]);
};

LoDyn.output.style	= {};

/**
 * @param 	{CSSStyleDeclaration}		style	computed style
 * @returns {string}
 */
LoDyn.output.style.height	= style =>
{
	return 	parseFloat(style.height.replace(/px/, ''))
		+	parseFloat(style.marginBottom.replace(/px/, ''))
		+	parseFloat(style.marginTop.replace(/px/, ''))
		+	'px';
};

LoDyn.output.style.height_nopad	= style =>
{
	return 	parseFloat(style.height.replace(/px/, ''))
		+	parseFloat(style.marginBottom.replace(/px/, ''))
		+	parseFloat(style.marginTop.replace(/px/, ''))
		- 	parseFloat(style.paddingTop.replace(/px/, ''))
		- 	parseFloat(style.paddingBottom.replace(/px/, ''))
		+	'px';
};

/**
 * @param 	{CSSStyleDeclaration}		style	computed style
 * @returns	{string}
 */
LoDyn.output.style.width	= style =>
{
	return 	parseFloat(style.width.replace(/px/, ''))
		+ 	parseFloat(style.marginLeft.replace(/px/, ''))
		+ 	parseFloat(style.marginRight.replace(/px/, ''))
		+ 	'px';
};

LoDyn.output.style.width_nopad = style =>
{
	return 	parseFloat(style.width.replace(/px/, ''))
		+ 	parseFloat(style.marginLeft.replace(/px/, ''))
		+ 	parseFloat(style.marginRight.replace(/px/, ''))
		- 	parseFloat(style.paddingLeft.replace(/px/, ''))
		- 	parseFloat(style.paddingRight.replace(/px/, ''))
		+ 	'px';
};

/**
 * @param {Element | HTMLElement}		target		target element
 * @param {Array}						content		array of elements that should be appended
 * @param {Number}						flags		flags
 */
LoDyn.reflow	= (target, content, flags = LoDyn.reflow_flags.truncate | LoDyn.reflow_flags.fadeout) =>
{
	let 	fadeout_end;
	let 	fadeout;

	target	= LoDyn.el(target);
	fadeout	= (flags & LoDyn.reflow_flags.fadeout) && !target.classList.contains('reflow-hidden') && target.firstChild !== null;

	fadeout_end	= event =>
	{
		if(fadeout)
		{
			target.removeEventListener('animationend', fadeout_end);
			target.classList.remove('fade-out-s');
			event.stopPropagation();
		}

		requestAnimationFrame(e => {
			let 	style;
			let 	start	= {};
			let 	end		= {};
			let 	frag	= document.createDocumentFragment();
			let 	expand;

			start.padding			= {};
			end.padding				= {};
			style					= getComputedStyle(target);
			target.style.overflow	= 'hidden';

			if(target.reflow_end !== undefined)
			{
				start.x			= LoDyn.output.style.width(style);
				start.y			= LoDyn.output.style.height(style);
				start.padding.l	= style.paddingLeft;
				start.padding.r	= style.paddingRight;
				start.padding.t	= style.paddingTop;
				start.padding.b	= style.paddingBottom;

				LoDyn.reflow.clear_properties(target);

				//target.dispatchEvent(new Event('transitionend'));
				target.removeEventListener('transitionend', target.reflow_end);
				delete target.reflow_end;

				if(target.reflow_timeout !== undefined)
				{
					clearTimeout(target.reflow_timeout);
					target.reflow_timeout	= undefined;
				}

				LoDyn.reflow.remove_expand(target.firstChild);
			}
			else if(target.classList.contains('reflow-hidden'))
			{
				target.classList.remove('reflow-hidden');

				start.x			= 0;
				start.y			= 0;
				start.padding.l	= 0;
				start.padding.r	= 0;
				start.padding.t	= 0;
				start.padding.b	= 0;
			}
			else
			{
				start.x			= LoDyn.output.style.width(style);
				start.y			= LoDyn.output.style.height(style);
				start.padding.l	= style.paddingLeft;
				start.padding.r	= style.paddingRight;
				start.padding.t	= style.paddingTop;
				start.padding.b	= style.paddingBottom;
			}

			target.style.display		= 'none';
			target.style.transition		= 'none';

			target.classList.add('reflow');

			if((flags & LoDyn.reflow_flags.truncate) && !(flags & LoDyn.reflow_flags.flowout)){
				//for(let child; child = target.firstChild;)
				//	target.removeChild(child);
				LoDyn.reflow.truncate(target);
			}

			if(content !== null)
				for(let i = 0, v; v = content[i]; i++)
					frag.appendChild(v);

			if((flags & (LoDyn.reflow_flags.flowout | LoDyn.reflow_flags.flowin)) || (!LoDyn.options.reflow.expand_y && !LoDyn.options.reflow.expand_x))
				target.appendChild(frag);
			else
			{
				expand	= document.createElement('div');

				expand.classList.add('reflow-exp');

				for(; target.childNodes.length > 0;)
					expand.appendChild(target.firstChild);

				expand.appendChild(frag);
				target.appendChild(expand);
			}

			target.style.removeProperty('display');

			if(flags & LoDyn.reflow_flags.flowout)
			{
				end.x			= '0';
				end.y			= '0';
				end.padding.l	= '0';
				end.padding.r	= '0';
				end.padding.t	= '0';
				end.padding.b	= '0';
			}
			else
			{
				style			= getComputedStyle(target);
				end.x			= LoDyn.output.style.width(style);
				end.y			= LoDyn.output.style.height(style);
				end.padding.l	= style.paddingLeft;
				end.padding.r	= style.paddingRight;
				end.padding.t	= style.paddingTop;
				end.padding.b	= style.paddingBottom;
			}

			console.log(JSON.stringify(start));
			console.log(JSON.stringify(end));

			target.style.flex			= '0 0 auto';
			target.style.paddingLeft	= start.padding.l;
			target.style.paddingRight	= start.padding.r;
			target.style.paddingTop		= start.padding.t;
			target.style.paddingBottom	= start.padding.b;
			target.style.width			= start.x;
			target.style.height			= start.y;

			if(flags & (LoDyn.reflow_flags.flowout | LoDyn.reflow_flags.flowin))
			{
				if(flags & LoDyn.reflow_flags.flowout)
					target.style.opacity	= '1';
				else if(flags & LoDyn.reflow_flags.flowin)
					target.style.opacity	= '0';

				// expand.style.height			= '100%';
				// expand.style.width			= '100%';
			}
			else
			{
				if(LoDyn.options.reflow.fadein)
					target.style.opacity	= '0';

				if(expand !== undefined)
				{
					expand.style.height			= LoDyn.options.reflow.expand_y		? '0'	: '100%';
					expand.style.width			= LoDyn.options.reflow.expand_x		? '0'	: '100%';
				}
			}



			requestAnimationFrame(e => {

				let	reflow_end;
				let expand_end;

				target.style.removeProperty('transition');

				target.style.width			= end.x;
				target.style.height			= end.y;
				target.style.paddingLeft	= end.padding.l;
				target.style.paddingRight	= end.padding.r;
				target.style.paddingTop		= end.padding.t;
				target.style.paddingBottom	= end.padding.b;

				if(flags & LoDyn.reflow_flags.flowout)
					target.style.opacity	= '0';
				else if((flags & LoDyn.reflow_flags.flowin) || LoDyn.options.reflow.fadein)
					target.style.opacity	= '1';

				if(expand !== undefined)
				{
					expand.style.height			= '100%';
					expand.style.width			= '100%';

					expand_end	= event =>
					{
						if(event.target !== expand)
							return;

						event.stopPropagation();
						LoDyn.reflow.remove_expand(expand);
					};
				}

				reflow_end	= event =>
				{
					if(event.target !== target)
						return;

					event.stopPropagation();
					LoDyn.reflow.clear_properties(target);

					if(target.innerHTML.length === 0 || (flags & LoDyn.reflow_flags.flowout))
						target.classList.add('reflow-hidden');

					if((flags & LoDyn.reflow_flags.truncate) && (flags & LoDyn.reflow_flags.flowout)) {
						//for(let child; child = target.firstChild;)
						//	target.removeChild(child);
						LoDyn.reflow.truncate(target);
					}

					target.removeEventListener('transitionend', reflow_end);
					delete target.reflow_end;

					if((flags & LoDyn.reflow_flags.remove))
					{
						LoDyn.reflow.truncate(target);
						target.remove();
					}
				};

				target.addEventListener('transitionend', reflow_end, false);
				target.reflow_end	= reflow_end;

				if(expand !== undefined)
					expand.addEventListener('transitionend', expand_end, false);
			});
		});

	};

	if(fadeout)
	{
		target.classList.add('fade-out-s');
		target.addEventListener('animationend', fadeout_end);
	}
	else
		fadeout_end();
};

LoDyn.reflow.clear_properties	= el =>
{
	el.style.removeProperty('display');
	el.style.removeProperty('transition');
	el.style.removeProperty('opacity');
	el.style.removeProperty('padding');
	el.style.removeProperty('paddingLeft');
	el.style.removeProperty('paddingRight');
	el.style.removeProperty('paddingTop');
	el.style.removeProperty('paddingBottom');
	el.style.removeProperty('width');
	el.style.removeProperty('height');
	el.style.removeProperty('overflow');
	el.style.removeProperty('flex');
	el.classList.remove('reflow');
};

LoDyn.reflow.remove_expand	= el =>
{
	if(el !== undefined && el.classList && !el.classList.contains('reflow-exp'))
		return;

	for(; el.childNodes.length > 0;)
		el.parentElement.appendChild(el.firstChild);

	el.remove();
};

LoDyn.reflow.truncate = el => {
	let els	= el.querySelectorAll('lo-dyn');

	for(let i = 0, v; v = els[i]; i++){
		v.removeEventListeners();
	}

	for(let child; child = el.firstChild;){
		el.removeChild(child);
	}
};

/**
 * @param 	{Element}		e		element
 */
LoDyn.flowout	= e =>
{
	LoDyn.reflow(e, null, LoDyn.reflow_flags.flowout | LoDyn.reflow_flags.truncate);
};

LoDyn.remove	= el =>
{
	LoDyn.reflow(el, null, LoDyn.reflow_flags.flowout | LoDyn.reflow_flags.remove);
};

/**
 * @param 	{Element}		e
 */
LoDyn.toggle = el =>
{
	el	= LoDyn.el(el);

	if(el === null)
		return;

	if(el.classList.contains('reflow-hidden') || el.classList.contains('reflow-hiding'))
		LoDyn.show(el);
	else
		LoDyn.hide(el);
};

LoDyn.show	= el =>
{
	el	= LoDyn.el(el);

	if(el === null || el.classList.contains('reflow-showing'))
		return;

	el.classList.remove('reflow-hiding');
	el.classList.add('reflow-showing');
	LoDyn.reflow(el, null, LoDyn.reflow_flags.flowin);
};

LoDyn.hide	= el =>
{
	el	= LoDyn.el(el);

	if(el === null || el.classList.contains('reflow-hidden') || el.classList.contains('reflow-hiding'))
		return;

	el.classList.add('reflow-hiding');
	el.classList.remove('reflow-showing');
	LoDyn.reflow(el, null, LoDyn.reflow_flags.flowout);
};

LoDyn.toggle_no_sib = el =>
{
	el	= LoDyn.el(el);

	if(el === null)
		return;

	for(let parent = el.parentElement, i = 0; i < parent.children.length; i++)
	{
		let child;

		child	= parent.children[i];

		if(child === el)
			continue;

		LoDyn.hide(child);
	}

	LoDyn.toggle(el);
};
