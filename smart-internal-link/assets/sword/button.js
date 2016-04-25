(function(w, d, base) {
    if (!base.composer) return;
    var speak = base.languages.MTE,
        name = 'book plugin-smart-internal-link',
        title = speak.plugin_smart_internal_link[0];
    base.composer.button(name, {
        title: title,
        click: function(e, editor) {
            editor.modal('smart-internal-link', function(overlay, modal, header, content, footer) {
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
                input.placeholder = base.task.slug(title[0].toLowerCase());
                header.innerHTML = title[0];
                content.appendChild(input);
                content.appendChild(select);
                ok.innerHTML = speak.actions.ok;
                cancel.innerHTML = speak.actions.cancel;
                var insert = function() {
                    var v = input.value,
                        x = /^(.*?)([?&#])(.*?)$/.exec(v);
                    if (!v.length) return false;
                    if (x && x[1]) {
                        x[1] = base.task.slug(x[1].toLowerCase());
                        v = x[1] + x[2] + x[3];
                    } else {
                        v = base.task.slug(v.toLowerCase());
                    }
                    var str = '{{' + select.value + '.link:' + v + '}}';
                    return editor.grip.tidy(' ', function() {
                        if (s.value.length) {
                            editor.grip.wrap(str, '{{/' + select.value + '}}', function() {
                                // Braces are not allowed in the link text
                                editor.grip.replace(/\{/g, '&#123;', function() {
                                    editor.grip.replace(/\}/g, '&#125;', true);
                                });
                            });
                        } else {
                            editor.grip.insert(str);
                        }
                    }), false;
                };
                editor.event("keydown", input, function(e) {
                    var k = editor.grip.key(e);
                    if (k === 'enter') return insert();
                    if (k === 'escape') return editor.exit(true), false;
                    if (k === 'arrowdown') return ok.focus(), false;
                });
                editor.event("click", ok, insert);
                editor.event("click", cancel, function() {
                    return editor.exit(true), false;
                });
                editor.event("keydown", ok, function(e) {
                    var k = editor.grip.key(e);
                    if (k === 'enter') return insert();
                    if (k === 'escape') return editor.exit(true), false;
                    if (k === 'arrowup') return input.focus(), false;
                    if (k.match(/^arrow(right|down)$/)) return cancel.focus(), false;
                });
                editor.event("keydown", cancel, function(e) {
                    var k = editor.grip.key(e);
                    if (k.match(/^enter|escape$/)) return editor.exit(true), false;
                    if (k.match(/^arrow(left|up)$/)) return ok.focus(), false;
                    if (k === 'arrowdown') return false;
                });
                footer.appendChild(ok);
                footer.appendChild(cancel);
                w.setTimeout(function() {
                    input.focus(), input.select();
                }, .2);
            });
        }
    });
    // `Ctrl + Shift + L` for "smart internal link"
    base.composer.shortcut('ctrl+shift+l', function() {
        return base.composer.grip.config.buttons[name].click(null, base.composer), false;
    });
})(window, document, DASHBOARD);