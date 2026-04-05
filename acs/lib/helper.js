function getSiblings(el) {
    let siblings = [];
    if (!el.parentNode) {
        return siblings;
    }

    let sibling = el.parentNode.firstChild;

    while (sibling) {
        if (sibling.nodeType === 1 && sibling !== e) {
            siblings.push(sibling);
        }
        sibling = sibling.nextSibling;
    }

    return siblings;
}

function show(selector) {
    const elements = document.querySelectorAll(selector)
    if (!elements) return;
    elements.forEach(el => {
        el.style.display = ''
    })
}

function hide(selector) {
    const elements = document.querySelectorAll(selector)
    if (!elements) return;
    elements.forEach(el => {
        el.setAttribute('style', 'display:none !important');
    })
}

function toggleClass(el, className) {
    if (el.classList.contains(className)) {
        el.classList.remove(className)
    } else {
        el.classList.add(className)
    }
}

function addClass(selector, className) {
    const elements = document.querySelectorAll(selector)
    if (!elements) return;
    elements.forEach(el => {
        el.classList.add(className)
    })
}

function removeClass(selector, className) {
    const elements = document.querySelectorAll(selector)
    if (!elements) return;
    elements.forEach(el => {
        el.classList.remove(className)
    })
}

function customOperate(selector, fn) {
    const elements = document.querySelectorAll(selector)
    if (!elements) return;
    elements.forEach(el => {
        if (fn) {
            fn(el);
        }
    })
}

function request(method, url, data) {
    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("Transaction-Id", getParameterByName('t'));
        xhr.onload = function() {
            if (this.status >= 200 && this.status < 300) {
                resolve(JSON.parse(xhr.response));
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText,
                    response: JSON.parse(xhr.response)
                });
            }
        };
        xhr.onerror = function() {
            reject({
                status: this.status,
                statusText: xhr.statusText,
                response: JSON.parse(xhr.response)
            });
        };
        if (method == "POST" && data) {
	    var form = new URLSearchParams(JSON.parse(data)).toString()
            xhr.send(form);
        } else {
            xhr.send();
        }
    });
}

function ready(callback) {
    if (document.readyState != 'loading') {
        callback()
    } else if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', callback)
    } else document.attachEvent('onreadystatechange', function() {
        if (document.readyState == 'complete') {
            callback()
        }
    })
}

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';

    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}