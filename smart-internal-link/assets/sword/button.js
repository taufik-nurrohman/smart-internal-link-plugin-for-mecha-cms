(function(w, d, base) {
    if (typeof base.composer === "undefined") return;
    var speak = base.languages,
        title = speak.plugin_smart_internal_link[0];
    base.composer.button('book plugin-smart-internal-link', {
        title: title,
        click: function(e, editor) {
            editor.modal('smart-internal-link', function(overlay, modal) {
                var s = editor.grip.selection(),
                    ok = d.createElement('button'),
                    cancel = d.createElement('button'),
                    input = d.createElement('input'),
                    select = d.createElement('select'),
                    scope = speak.plugin_smart_internal_link[1];
                for (var i in scope) {
                    select.innerHTML += '<option value="' + i + '"' + (i === base.segment ? ' selected' : "") + '>' + scope[i] + '</option>';
                }
                input.type = 'text';
                input.placeholder = base.task.slug(title.toLowerCase());
                modal.children[0].innerHTML = title;
                modal.children[1].appendChild(input);
                modal.children[1].appendChild(select);
                ok.innerHTML = editor.grip.config.buttons.ok;
                cancel.innerHTML = editor.grip.config.buttons.cancel;
                var insert = function() {
                    if (!input.value.length) return false;
                    var str = '{{' + select.value + '.link:' + base.task.slug(input.value.toLowerCase(), '-', '#:?=&a-z0-9') + '}}';
                    if (s.value.length) {
                        editor.grip.wrap(str, '{{/' + select.value + '}}', function() {
                            // Braces are not allowed in the link text
                            var noop = function() {};
                            editor.grip.replace(/\{/g, '&#123;', noop);
                            editor.grip.replace(/\}/g, '&#125;', noop);
                            editor.grip.updateHistory();
                        });
                    } else {
                        editor.grip.insert(str);
                    }
                    return false;
                };
                editor.event("keydown", input, function(e) {
                    if (e.keyCode === 13) return insert();
                    if (e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 40) return ok.focus(), false;
                });
                editor.event("click", ok, insert);
                editor.event("click", cancel, function() {
                    return editor.close(true);
                });
                editor.event("keydown", ok, function(e) {
                    if (e.keyCode === 13) return insert();
                    if (e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 38) return input.focus(), false;
                    if (e.keyCode === 39 || e.keyCode === 40) return cancel.focus(), false;
                });
                editor.event("keydown", cancel, function(e) {
                    if (e.keyCode === 13 || e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 37 || e.keyCode === 38) return ok.focus(), false;
                    if (e.keyCode === 40) return false;
                });
                modal.children[2].appendChild(ok);
                modal.children[2].appendChild(cancel);
                w.setTimeout(function() {
                    input.focus();
                    input.select();
                }, 10);
            });
        }
    });
    base.composer.shortcut('CTRL+SHIFT+76', function() {
        return base.composer.grip.config.buttons['book plugin-smart-internal-link'].click(false, base.composer), false;
    });
})(window, document, DASHBOARD);